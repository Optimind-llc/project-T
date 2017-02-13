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
            case 'doorR': $parts['1'] = $parts['6714111020']; break;
            case 'doorL': $parts['2'] = $parts['6714211020']; break;
            case 'luggageSTD': $parts['3'] = $parts['6441211010']; break;
            case 'luggageARW': $parts['4'] = $parts['6441211020']; break;
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
        $validator = app('validator')->make(
            $request->all(),
            [
                'partTypeId' => ['required', 'alpha_num'],
                'panelId' => ['required', 'alpha_num']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad structure JSON in Request body'
            ], 400);
        }

        $part = Part::where('part_type_id', '=', $request->partTypeId)
            ->where('panel_id', '=', $request->panelId)
            ->first();

        if (is_null($part)) {
            return response()->json([
                'message' => 'Not be inspected',
                'panelId' => $request->panelId
            ], 200);
        }

        $family = $part->family()
            ->with(['parts.partType'])
            ->first();

        if (is_null($family)) {
            return response()->json([
                'message' => 'Not be associated',
                'panelId' => $request->panelId
            ], 200);
        }

        return [
            'message' => 'success',
            'familyId' => $family->id,
            'parts' => $family->parts->map(function($p) {
                return [
                    'vehicle' => $p->partType->vehicle_num,
                    'name' => $p->partType->name,
                    'pn' => $p->partType->pn,
                    'pn2' => $p->partType->pn2,
                    'panelId' => $p->panel_id
                ];
            })
        ];
    }

    public function updateFamily($vehicle, Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'id' => ['required', 'alpha_num'],
                'parts' => ['required']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'JSON in Request body should contain key "association"'
            ], 400);
        }

        $familyId = $request->id;
        $parts = collect($request->parts);

        $innerPanelId = $parts->first(function ($key, $value) {
            return $value['partTypeId'] === 1;
        })['panelId'];

        $parts->push([
            'partTypeId' => 7,
            'panelId' => $innerPanelId
        ]);

        // Check
        $associated = [];
        foreach ($parts as $part) {
            $part_obj = Part::where('part_type_id', '=', $part['partTypeId'])
                ->where('panel_id', '=', $part['panelId'])
                ->first();

            if (!is_null($part_obj)) {
                $part_obj_family_id = $part_obj->family_id;
                if (!is_null($part_obj_family_id) && $part_obj_family_id != $familyId) {
                    $associated[] = [
                        'pn' => $part_obj->partType->pn,
                        'name' => $part_obj->partType->name,
                        'partTypeId' => $part_obj->part_type_id,
                        'panelId' => $part_obj->panel_id
                    ];
                }
            }
        }

        if (count($associated) > 0) {
            return response()->json([
                'message' => 'Already be associated others',
                'parts' => collect($associated)->filter(function($p) {
                    return $p['partTypeId'] != 7;
                })
            ], 200);
        }

        DB::beginTransaction();

        $toBeReleasedParts = Part::where('family_id', '=', $familyId)->get();

        if ($toBeReleasedParts->count() > 0) {
            foreach ($toBeReleasedParts as $toBeReleasedPart) {
                $toBeReleasedPart->family_id = null;
                $toBeReleasedPart->save();
            }
        }

        foreach ($parts as $key => $part) {
            if ($part['panelId'] != '') {
                $part_obj = Part::where('part_type_id', '=', $part['partTypeId'])
                    ->where('panel_id', '=', $part['panelId'])
                    ->first();

                if (is_null($part_obj)) {
                    $part_obj = new Part;
                    $part_obj->panel_id = $part['panelId'];
                    $part_obj->part_type_id = $part['partTypeId'];
                    $part_obj->save();
                }

                $part_obj_family_id = $part_obj->family_id;
                if (!is_null($part_obj_family_id) && $part_obj_family_id != $familyId) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Already be associated others',
                        'parts' => [
                            'pn' => $part_obj->partType->pn,
                            'name' => $part_obj->partType->name,
                            'partTypeId' => $part['partTypeId'],
                            'panelId' => $part['panelId']
                        ]
                    ], 200);
                }
            }

            $part_obj->family_id = $familyId;
            $part_obj->save();
        }

        DB::commit();

        $family = PartFamily::find($familyId);
        $family->updated_at = Carbon::now();
        $family->save();

        return [
            'message' => 'success'
        ];
    }
}
