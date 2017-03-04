<?php

namespace App\Http\Controllers\V2\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\InspectionResult;
use App\Models\Vehicle950A\PartType;
use App\Models\Vehicle950A\Part;
use App\Models\Vehicle950A\PartFamily;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AssociationController
 * @package App\Http\Controllers\V2\Client
 */
class AssociationController extends Controller
{
    public function check($vehicle, Request $request)
    {
        $parts = $request->parts;
        $partInspections = collect(config('part.950A'));

        $heritage = [];
        foreach ($parts as $pn => $panelId) {
            $p_obj = PartType::where('part_types.pn', '=', $pn)
                ->leftJoin('parts as p', function ($join) use ($panelId) {
                    $join->on('part_types.pn', '=', 'p.pn')->where('p.panel_id', '=', $panelId);
                })
                ->select(['part_types.en', 'p.id', 'p.family_id'])
                ->first()
                ->toArray();

            $irs = collect([]);
            if (!is_null($p_obj['id'])) {
                $irs = InspectionResult::where('part_id', '=', $p_obj['id'])
                    ->where('latest', '=', 1)
                    ->get(['process', 'inspection', 'status']);
            }

            $associated = isset($p_obj['family_id']);

            $history = $partInspections->filter(function($pi) use($p_obj) {
                return $pi['part'] === $p_obj['en'];
            })->map(function($pi) use($irs) {
                $matched = $irs->first(function($key, $ir) use($pi) {
                    return $ir['process'] === $pi['process'] && $ir['inspection'] === $pi['inspection'];
                });

                $status = $matched ? $matched['status'] : -1;
                return [
                    'process' => $pi['process'],
                    'inspection' => $pi['inspection'],
                    'status' => $status
                ];
            })->values();

            $heritage[$pn] = [
                'associated' => $associated,
                'history' => $history
            ];
        }

        return $heritage;
    }

    public function save($vehicle, Request $request)
    {
        $type = $request->type;
        $parts = $request->parts;

        switch ($type) {
            case 'doorR': $parts['6701511020'] = $parts['6714111020']; break;
            case 'doorL': $parts['6701611020'] = $parts['6714211020']; break;
            case 'luggageSTD': $parts['6440111010'] = $parts['6441211010']; break;
            case 'luggageARW': $parts['6440111020'] = $parts['6441211020']; break;
            default:
                return \Response::json([
                    'message' => 'Incorrect type given',
                    'yourRequestBody' => $request->all()
                ], 400);
        }

        $toBeAssociated = [];
    	foreach ($parts as $pn => $panel_id) {
            $targetPart = Part::where('pn', '=', $pn)
                ->where('panel_id', '=', $panel_id)
                ->first();

	        if ($targetPart instanceof Part) {
	        	if ($targetPart->family_id) {
                    return [
                        'message' => 'Already be associated',
                        'parts' => [
                            'pn' => $pn,
                            'name' => $targetPart->partType->name,
                            'panelId' => $panel_id
                        ]
                    ];
	        	}
	        } else {
                $targetPart = new Part;
                $targetPart->panel_id = $panel_id;
                $targetPart->pn = $pn;
                $targetPart->save();
            }

            array_push($toBeAssociated, $targetPart);
    	}

        $newFamily = new PartFamily;
        $newFamily->type = $type;
        $newFamily->save();

        foreach ($toBeAssociated as $part) {
            $newFamily->parts()->save($part);
        }

        return [
            'message' => 'Associate succeeded'
        ];
    }

    public function getFamily($vehicle, Request $request)
    {
        $pn = $request->pn;
        $panelId = $request->panelId;

        $part = Part::where('pn', '=', $pn)
            ->where('panel_id', '=', $panelId)
            ->first();

        if (is_null($part)) {
            return response()->json([
                'associated' => false,                
                'message' => 'Not be associated',
                'pn' => $pn,
                'panelId' => $panelId
            ], 200);
        }

        $family = $part->family()
            ->with(['parts.partType'])
            ->first();

        if (is_null($family)) {
            return response()->json([
                'associated' => false,    
                'message' => 'Not be associated',
                'pn' => $pn,
                'panelId' => $panelId
            ], 200);
        }

        return [
            'associated' => true,
            'familyType' => $family->type,
            'familyId' => $family->id,
            'parts' => $family->parts->map(function($p) {
                return [
                    'name' => $p->partType->name,
                    'pn' => $p->partType->pn,
                    'panelId' => $p->panel_id
                ];
            }),
            'message' => 'success'
        ];
    }

    public function updateFamily($vehicle, Request $request)
    {
        $familyId = $request->familyId;
        $parts = $request->parts;
        $partInspections = collect(config('part.950A'));

        $family = PartFamily::find($familyId);
        $familyType = $family->type;

        switch ($familyType) {
            case 'doorR': $parts['6701511020'] = $parts['6714111020']; break;
            case 'doorL': $parts['6701611020'] = $parts['6714211020']; break;
            case 'luggageSTD': $parts['6440111010'] = $parts['6441211010']; break;
            case 'luggageARW': $parts['6440111020'] = $parts['6441211020']; break;
            default:
                return \Response::json([
                    'message' => 'Incorrect type given',
                    'yourRequestBody' => $request->all()
                ], 400);
        }

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

        DB::connection('950A')->commit();
        return response()->json([
            'hasAssociated' => false,
            'message' => 'Update associated family succeed'
        ], 200);
    }
}
