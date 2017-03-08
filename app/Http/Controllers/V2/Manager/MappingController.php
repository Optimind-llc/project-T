<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\HoleTypeRepository;
use App\Repositories\InlineTypeRepository;
// Services
use App\Services\Vehicle950A\Choku;
// Models
use App\Models\Vehicle950A\Figure;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MappingController
 * @package App\Http\Controllers\V2\Manager
 */
class MappingController extends Controller
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

    public function realtime($vehicle, Request $request)
    {
        $p = $request->p;
        $i = $request->i;
        $line = 1;
        $pn = $request->pt;

        $pns = [$pn];
        if ($p === 'holing' || $i === 'inline') {
            switch ($pn) {
                case 6714111020:
                case 6715111020:
                    $pns = [6714111020, 6715111020];
                    break;

                case 6714211020:
                case 6715211020:
                    $pns = [6714211020, 6715211020];
                    break;
            }
        }
        if ($p === 'molding' && $i === 'gaikan') {
            switch ($pn) {
                case 6714111020:
                case 6715111020:
                    $pns = [6714111020, 6715111020];
                    break;

                case 6714211020:
                case 6715211020:
                    $pns = [6714211020, 6715211020];
                    break;
            }
        }

        $choku = new Choku(Carbon::today());
        $start = $choku->getLastChokuStart();
        $end = Carbon::now();

        $chokus = ['W', 'Y', 'B', 'NA'];

        $irs = $this->inspectionResult->forMappingByDate($p, $i, $line, $pns, $start, $end, $chokus);

        $failureTypes = $this->failureType->getByIds($irs['ft_ids']);
        $modificationTypes = $this->modificationType->getByIds($irs['mt_ids']);
        $holeModificationTypes = $this->holeModificationType->getByIds($irs['hmt_ids']);

        $figures = Figure::where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->whereIn('pt_pn', $pns)
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
            $holeTypes = $this->holeType->getAllByPns($pns);
        }

        $inlineTypes = [];
        if ($i === 'inline') {
            $inlineTypes = $this->inlineType->getAllByPns($pns);
        }

        return [
            'data' => [
                'count' => $irs['count'],
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

    public function byDate($vehicle, Request $request)
    {
        $p = $request->p;
        $i = $request->i;
        $line = 1;
        $pn = $request->pt;
        $chokus = $request->c;

        $pns = [$pn];
        if ($p === 'holing' || $i === 'inline') {
            switch ($pn) {
                case 6714111020:
                case 6715111020:
                    $pns = [6714111020, 6715111020];
                    break;

                case 6714211020:
                case 6715211020:
                    $pns = [6714211020, 6715211020];
                    break;
            }
        }
        if ($p === 'molding' && $i === 'gaikan') {
            switch ($pn) {
                case 6714111020:
                case 6715111020:
                    $pns = [6714111020, 6715111020];
                    break;

                case 6714211020:
                case 6715211020:
                    $pns = [6714211020, 6715211020];
                    break;
            }
        }


        $start = Carbon::createFromFormat('Y-m-d H:i:s', $request->s.' 00:00:00')->addHours(3);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $request->e.' 00:00:00')->addHours(27);

        $irs = $this->inspectionResult->forMappingByDate($p, $i, $line, $pns, $start, $end, $chokus);

        $failureTypes = $this->failureType->getByIds($irs['ft_ids']);
        $modificationTypes = $this->modificationType->getByIds($irs['mt_ids']);
        $holeModificationTypes = $this->holeModificationType->getByIds($irs['hmt_ids']);

        $figures = Figure::where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->whereIn('pt_pn', $pns)
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
            $holeTypes = $this->holeType->getAllByPns($pns);
        }

        $inlineTypes = [];
        if ($i === 'inline') {
            $inlineTypes = $this->inlineType->getAllByPns($pns);
        }

        return [
            'data' => [
                'count' => $irs['count'],
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

    public function byPanelId($vehicle, Request $request)
    {
        $p = $request->p;
        $i = $request->i;
        $pn = $request->pt;
        $pns = [$pn];
        $panelId = $request->panelId;

        $irs = $this->inspectionResult->forMappingByPanelId($p, $i, $pn, $panelId);

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
            $holeTypes = $this->holeType->getAllByPns($pns);
        }

        $inlineTypes = [];
        if ($i === 'inline') {
            $inlineTypes = $this->inlineType->getAllByPns($pns);
        }

        return [
            'data' => [
                'count' => $irs['count'],
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
