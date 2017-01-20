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
            return response()->json([
                'message' => 'JSON in Request body should contain key "association"'
            ], 400);
        }

        // Check
        $associated = [];
        $heritage = [];
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

                // $heritage[] = Part::where('part_type_id', '=', $partTypeId)
                //     ->where('panel_id', '=', $panelId)
                //     ->with([
                //         'pages' => function($q) {
                //             $q->join('inspection_families as if', function($join) {
                //                 $join->on('pages.family_id', '=', 'if.id');
                //             })->join('inspection_groups as ig', function($join) {
                //                 $join->on('if.inspection_group_id', '=', 'ig.id');
                //             })->join('inspections as i', function($join) {
                //                 $join->on('ig.inspection_id', '=', 'i.id');
                //             })
                //             ->select('pages.*', 'if.inspected_at', 'if.inspection_group_id', 'ig.line', 'i.en', 'i.process_id')
                //             ->orderBy('if.inspected_at')
                //             ->get();
                //         }
                //     ])
                //     ->get()
                //     ->map(function($p) {
                //         $heritage = collect([
                //             'molding_gaikan1' => 0,
                //             'molding_gaikan2' => 0,
                //             'molding_inline1' => 0,
                //             'molding_inline2' => 0,
                //             'holing_gaikan' => 0,
                //             'holing_ana' => 0,
                //         ]);
                //         return [
                //             'panelId' => $p->panel_id,
                //             'associated' => $p->family_id === null ? 0 : 1,
                //             'heritage' => $heritage->merge($p->pages->map(function($p) {
                //                 return [
                //                     'name' => $p['process_id'].'_'.$p['en'].$p['line'],
                //                     'pivot' => $p['pivot']
                //                 ];
                //             })->groupBy('name')->map(function($page) {
                //                 return $page->first()['pivot']['status'] === 1 ? 1 : 2;
                //             }))
                //         ];
                //     })
                //     ->toArray();
            }
        }

        if (count($associated) > 0) {
            return response()->json([
                'message' => 'Already be associated others',
                'parts' => $associated
            ], 200);
        }

        // if (count($heritage) > 0) {
        //     return response()->json([
        //         'message' => 'heritage',
        //         'parts' => $heritage
        //     ], 200);
        // }


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

    public function checkStatus(Request $request)
    {
        $association = $request->association;
        if (!$association) {
            return response()->json([
                'message' => 'JSON in Request body should contain key "association"'
            ], 400);
        }

        $associated = [];
        $heritage = [];
        foreach ($association as $pn => $panelId) {
            $partTypeId = PartType::where('pn', $pn)->first()->id;
            $part_obj = Part::where('part_type_id', '=', $partTypeId)
                ->where('panel_id', '=', $panelId)
                ->first();

            if (!is_null($part_obj)) {
                $base = collect([
                    'molding_gaikan1' => 0,
                    'molding_gaikan2' => 0,
                    'molding_inline1' => 0,
                    'molding_inline2' => 0,
                    'holing_gaikan' => 0,
                    'holing_ana' => 0,
                ]);

                $heritage[] = Part::where('part_type_id', '=', $partTypeId)
                    ->where('panel_id', '=', $panelId)
                    ->with([
                        'pages' => function($q) {
                            $q->join('inspection_families as if', function($join) {
                                $join->on('pages.family_id', '=', 'if.id');
                            })->join('inspection_groups as ig', function($join) {
                                $join->on('if.inspection_group_id', '=', 'ig.id');
                            })->join('inspections as i', function($join) {
                                $join->on('ig.inspection_id', '=', 'i.id');
                            })
                            ->select('pages.*', 'if.inspected_at', 'if.inspection_group_id', 'ig.line', 'i.en', 'i.process_id')
                            ->orderBy('if.inspected_at')
                            ->get();
                        }
                    ])
                    ->get()
                    ->map(function($p) use ($base, $partTypeId, $panelId) {
                        return [
                            'partTypeId' => $partTypeId,
                            'panelId' => $panelId,
                            'associated' => $p->family_id === null ? 0 : 1,
                            'heritage' => $base->merge($p->pages->map(function($p) {
                                return [
                                    'name' => $p['process_id'].'_'.$p['en'].$p['line'],
                                    'pivot' => $p['pivot']
                                ];
                            })->groupBy('name')->map(function($page) {
                                return $page->first()['pivot']['status'] === 1 ? 1 : 2;
                            }))
                        ];
                    })
                    ->toArray();
            }
            else {
                $heritage[] = collect([
                    'partTypeId' => $partTypeId,
                    'panelId' => $panelId,
                    'molding_gaikan1' => 0,
                    'molding_gaikan2' => 0,
                    'molding_inline1' => 0,
                    'molding_inline2' => 0,
                    'holing_gaikan' => 0,
                    'holing_ana' => 0,
                ]);
            }
        }

        return response()->json([
            'message' => 'heritage',
            'parts' => $heritage
        ], 200);
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
