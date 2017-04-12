<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\HoleTypeRepository;
use App\Repositories\InlineTypeRepository;
// Models
use App\Models\Vehicle950A\Part;
use App\Models\Vehicle950A\PartFamily;
use App\Models\Vehicle950A\Figure;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AssociationController
 * @package App\Http\Controllers\Press\Manager
 */
class AssociationController extends Controller
{
    protected $inspectionResult;
    protected $failureType;
    protected $modificationType;
    protected $holeModificationType;
    protected $holeType;
    protected $inlineType;

    public function __construct (
        InspectionResultRepository $inspectionResult,
        FailureTypeRepository $failureType,
        ModificationTypeRepository $modificationType,
        HoleModificationTypeRepository $holeModificationType,
        HoleTypeRepository $holeType,
        InlineTypeRepository $inlineType
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->failureType = $failureType;
        $this->modificationType = $modificationType;
        $this->holeModificationType = $holeModificationType;
        $this->holeType = $holeType;
        $this->inlineType = $inlineType;
    }

    public function getFamilyByDate($vehicle, Request $request)
    {
        $type = $request->type;
        $start = Carbon::createFromFormat('Y-m-d-H i:s', $request->start.' 00:00');
        $end = Carbon::createFromFormat('Y-m-d-H i:s', $request->end.' 00:00')->addHours(1);

        $partFamilis = PartFamily::with('parts.partType')
            ->where('part_families.updated_at', '>=', $start)
            ->where('part_families.updated_at', '<', $end)
            ->where('part_families.type', '=', $type)
            ->orderBy('part_families.updated_at')
            ->get()
            ->map(function($pf) {
                return [
                    'id' => $pf->id,
                    'type' => $pf->type,
                    'updatedAt' => $pf->updated_at->toDateTimeString(),
                    'parts' => $pf->parts->map(function($p) {
                        return [
                            'id' => $p->id,
                            'pn' => $p->pn,
                            'panelId' => $p->panel_id,
                            'sort' => $p->partType->sort
                        ];
                    })
                ];
            })
            ->groupBy('type');

        return [
            'data' => $partFamilis
        ];
    }

    public function getFamilyByPanelId($vehicle, Request $request)
    {
        $type = $request->type;
        $pns = $request->pn;
        $panelId = $request->panelId;

        $partFamilis = PartFamily::with('parts.partType')
            ->whereHas('parts', function ($q) use($pns, $panelId) {
                $q->whereIn('pn', $pns)->where('panel_id', 'like', $panelId.'%');
            })
            ->orderBy('part_families.updated_at')
            ->get()
            ->map(function($pf) {
                return [
                    'id' => $pf->id,
                    'type' => $pf->type,
                    'updatedAt' => $pf->updated_at->toDateTimeString(),
                    'parts' => $pf->parts->map(function($p) {
                        return [
                            'id' => $p->id,
                            'pn' => $p->pn,
                            'panelId' => $p->panel_id,
                            'sort' => $p->partType->sort
                        ];
                    })
                ];
            })
            ->groupBy('type');

        return [
            'data' => $partFamilis
        ];
    }

    public function updateFamily($vehicle, Request $request)
    {
        $familyId = $request->id;
        $parts = $request->parts;
        $partInspections = collect(config('part.950A'));

        $family = PartFamily::find($familyId);

        DB::connection('950A')->beginTransaction();
        $toBeReleasedParts = Part::where('family_id', '=', $familyId)->get();

        if ($toBeReleasedParts->count() > 0) {
            foreach ($toBeReleasedParts as $toBeReleasedPart) {
                $toBeReleasedPart->family_id = null;
                $toBeReleasedPart->save();
            }
        }

        $associated = [];
        foreach ($parts as $pn => $panelId) {
            $targetPart = Part::where('pn', '=', $pn)
                ->where('panel_id', '=', $panelId)
                ->first();

            if (is_null($targetPart)) {
                $targetPart = new Part;
                $targetPart->pn = $pn;
                $targetPart->panel_id = $panelId;
                $targetPart->save();
            }

            $target_family_id = $targetPart->family_id;
            if (!is_null($target_family_id) && $target_family_id != $familyId) {
                $associated[] = [
                    'pn' => $targetPart->pn,
                    'panelId' => $targetPart->panel_id
                ];
            }
            else {
                $targetPart->family_id = $familyId;
                $targetPart->save();
            }
        }

        if (count($associated) > 0) {
            DB::connection('950A')->rollBack();
            return response()->json([
                'hasAssociated' => true,
                'message' => 'Include the part already be associated others',
                'parts' => $associated
            ], 200);
        }

        $family->save();

        DB::connection('950A')->commit();
        return response()->json([
            'hasAssociated' => false,
            'message' => 'Update associated family succeed',
            'parts' => null
        ], 200);
    }

    public function mapping($vehicle, Request $request)
    {
        $p = $request->p;
        $i = $request->i;
        $partId = $request->id;
        $pn = $request->pn;

        $irs = $this->inspectionResult->forMappingByPartId($p, $i, $partId);

        $failureTypes = $this->failureType->getByIds($irs['ft_ids']);
        $modificationTypes = $this->modificationType->getByIds($irs['mt_ids']);
        $holeModificationTypes = $this->holeModificationType->getByIds($irs['hmt_ids']);

        $figures = Figure::where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->where('pt_pn', '=', $pn)
            ->orderBy('page')
            ->select(['id', 'page', 'path'])
            ->get()
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'page' => $f->page,
                    'path' => '/img/figures/950A/'.$f->path
                ];
            });

        $holeTypes = [];
        if ($i === 'ana' || $i === 'kashimego') {
            $holeTypes = $this->holeType->getAllByPns($pns, $i);
        }
        if ($p === 'holing' && $i === 'tenaoshi') {
            $holeTypes = $this->holeType->getAllByPns($pns, 'ana');
        }

        $inlineTypes = [];
        if ($i === 'inline') {
            $inlineTypes = $this->inlineType->getAllByPns($pns);
        }

        return [
            'data' => [
                'pn' => $pn,
                'count' => $irs['count'],
                'i' => $i,
                'result' => $irs['result'],
                'figures' => $figures,
                'failureTypes' => $failureTypes,
                'modificationTypes' => $modificationTypes,
                'holeModificationTypes' => $holeModificationTypes,
                'holeTypes' => $holeTypes,
                'inlineTypes' => $inlineTypes
            ]
        ];
    }
}
