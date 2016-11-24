<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\PartType;
use App\Models\Client\Part;
use App\Models\Client\PartFamily;
// Exceptions
use Dingo\Api\Exception\StoreResourceFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AssociationController
 * @package App\Http\Controllers
 */
class AssociationController extends Controller
{
    public function saveAssociation(Request $request)
    {
        $association = $request->association;
        if (!$association) {
            throw new StoreResourceFailedException('JSON in Request body should contain association key');
        }

        // Check
        $associated = [];
        foreach ($association as $pn => $panelId) {
            $partTypeId = PartType::where('pn', $pn)->first()->id;
            $part_obj = Part::where('part_type_id', '=', $partTypeId)
                ->where('panel_id', '=', $panelId)
                ->first();

            if (!is_null($part_obj)) {
                $part_obj_family_id = $part_obj->family_id;
                if (!is_null($part_obj_family_id)) {
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
                'parts' => $associated
            ], 200);
        }

        $association['67007'] = $association['67149'];

        $parts = [];
    	foreach ($association as $pn => $panel_id) {
            $part_type = PartType::where('pn', $pn)->first();
    		$newPart = $part_type->parts()
    			->where('panel_id', $panel_id)
    			->first();

	        if ($newPart instanceof Part) {
	        	$family = $newPart->family;

	        	if ($family instanceof PartFamily) {
                    return response()->json([
                        'message' => 'Already be associated others',
                        'parts' => [
                            'pn' => $newPart->partType->pn,
                            'name' => $newPart->partType->name,
                            'partTypeId' => $newPart->part_type_id,
                            'panelId' => $newPart->panel_id
                        ]
                    ], 200);
	        	}
	        }
            else {
                $newPart = new Part;
                $newPart->panel_id = $panel_id;
                $newPart->part_type_id = $part_type->id;
                $newPart->save();
            }

            array_push($parts, $newPart);
    	}

        $newFamily = new PartFamily;
        $newFamily->associated_by = null;
        $newFamily->save();

        foreach ($parts as $part) {
            $newFamily->parts()->save($part);
        }

        return [
            'message' => 'success'
        ];
    }

    public function getFamily(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'partTypeId' => ['required', 'alpha_num'],
                'panelId' => ['required', 'alpha_num']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
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

    public function updateFamily(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'id' => ['required', 'alpha_num'],
                'parts' => ['required']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
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
        foreach ($parts as $key => $part) {
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
                'parts' => $associated
            ], 200);
        }

        DB::beginTransaction();
        foreach ($parts as $key => $part) {
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

            $toBeReleasedParts = Part::where('part_type_id', '=', $part['partTypeId'])
                ->where('family_id', '=', $familyId)
                ->get();

            if ($toBeReleasedParts->count() > 0) {
                foreach ($toBeReleasedParts as $toBeReleasedPart) {
                    if ($toBeReleasedPart->panel_id != $part['panelId']) {
                        $toBeReleasedPart->family_id = null;
                        $toBeReleasedPart->save();
                    }
                }
            }

            $part_obj->family_id = $familyId;
            $part_obj->save();
        }
        DB::commit();

        return [
            'message' => 'success'
        ];
    }
}
