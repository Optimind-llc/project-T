<?php

namespace App\Http\Controllers\Vehicle950A\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Choku;
use App\Export;
use App\Result;
// Models
use App\Models\Vehicle950A\Process;
use App\Models\Vehicle950A\Inspector;
use App\Models\Vehicle950A\InspectorGroup;
use App\Models\Vehicle950A\Inspection;
use App\Models\Vehicle950A\InspectionGroup;
use App\Models\Vehicle950A\PartType;
use App\Models\Vehicle950A\Client\InspectionFamily;
use App\Models\Vehicle950A\Client\Page;
use App\Models\Vehicle950A\Client\Part;
use App\Models\Vehicle950A\Client\FailurePage;
use App\Models\Vehicle950A\Client\FailurePosition;
use App\Models\Vehicle950A\Client\ModificationFailurePosition;
use App\Models\Vehicle950A\Client\HolePage;
// Exceptions
use JWTAuth;
use App\Exceptions\JsonException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class InspectionController
 * @package App\Http\Controllers
 */
class InspectionController extends Controller
{
    public function getInspection(Request $request)
    {
        $process_en = $request->process;
        $inspection_en = $request->inspection;
        $pt_names = $request->partNames;

        $ig = InspectionGroup::where('process_en', '=', $process_en)
            ->where('inspection_en', '=', $inspection_en)
            ->first();

        // If inspectionGroup not found
        if (!$ig instanceof InspectionGroup) {
            return \Response::json([
                'message' => 'inspectionGroup Not found'
            ], 400);
        }

        $ig_id = $ig->id;

        $pt = PartType::whereIn('name', $pt_names)
            ->with([
                'figures' => function($q) use ($ig_id){
                    $q->where('ig_id', '=', $ig_id);
                }
            ])
            ->get()
            ->map(function($p) {
                return [
                    'pn' => $p->pn,
                    'pn2' => $p->pn2,
                    'name' => $p->name,
                    'shortName' => $p->short_name,
                    'figures' => $p->figures->map(function($f) {
                        return [
                            'name' => $f->name,
                            'path' => '/img/figures/950A/'.$f->path,
                            'holes' => []
                        ];
                    })
                    
                ];
            });

        return [
            'workers' => $ig->formatedWorkers(),
            'failures' => $ig->sortedFailureTypes(),
            'modifications' => [],
            'hModifications' => [],
            'parts' => $pt,
        ];
    }

    public function history(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'panelId' => ['required', 'alpha_num'],
                'partType' => ['required', 'alpha_num'],
                'id' => ['required']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $heritage = [];
        $group = [];
        $partTypeId = $request->partType;

        $part = Part::where('panel_id', $request->panelId)
            ->where('part_type_id', $partTypeId)
            ->first();

        foreach ($request->id as $id) {
            $formated = null;

            // If the requested part exist
            if ($part instanceof Part) {
                $detail = new Result($part->id, $partTypeId, $id);
                $formated = $detail->setDetails()->formatForClient()->get();
            }

            $name = InspectionGroup::find($id)->inspection->en;
            $line = InspectionGroup::find($id)->line;

            $name = $name . $line;

            $heritage[$name] = 0;

            if (!is_null($formated)) {
                $group[] = $formated;
                $heritage[$name] = 1;
            }
        }

        return [
            'heritage' => $heritage,
            'group' => $group
        ];
    }

    public function saveInspection(Request $request)
    {
        $family = $request->family;
        $groupId = $family['groupId'];

        $inspectionGroup = InspectionGroup::find($groupId)->toArray();

        //Duplicate detection
        foreach ($family['pages'] as $page) {
            $page_type_id = $page['pageId'];

            foreach ($page['parts'] as $part) {
                $newPart = Part::where('panel_id', $part['panelId'])
                    ->where('part_type_id', $part['partTypeId'])
                    ->first();

                if ($newPart instanceof Part) {
                    $inspectedPages = $newPart->pages()
                        ->with(['family.groups'])
                        ->get();

                    if ($inspectedPages) {
                        $inspections = $inspectedPages->map(function($i) {
                            return [
                                'division' => $i->family->groups->division_en,
                                'id' => $i->family->groups->inspection_id
                            ];
                        })
                        ->filter(function ($i) use ($inspectionGroup) {
                            return $i['division'] == $inspectionGroup['division_en'] && $i['id'] == $inspectionGroup['inspection_id'];
                        });

                        if ($inspections->count() > 0) {
                            return \Response::json([
                                'message' => $part['panelId'].' already be inspected',
                                'panelId' => $part['panelId'],
                                'pn' => $newPart->partType->pn
                            ], 400);
                        }
                    }
                }
            }
        }

        $now = Carbon::now();

        $newFamily = new InspectionFamily;
        $newFamily->inspection_group_id = $groupId;
        $newFamily->status = $family['status'];
        $newFamily->comment = array_key_exists('comment', $family) ? $family['comment'] : null;
        $newFamily->inspector_group = $family['inspectorGroup'];
        $newFamily->created_by = $family['inspector'];
        $newFamily->created_at = $now;
        $newFamily->updated_at = $now;
        $newFamily->save();

        foreach ($family['pages'] as $key => $page) {
            $newPage = new Page;
            $newPage->page_type_id = $page['pageId'];
            $newPage->table = isset($page['table']) ? $page['table'] : null;
            $newPage->family_id = $newFamily->id;
            $newPage->save();

            foreach ($page['parts'] as $part) {
                $newPart = Part::where('panel_id', $part['panelId'])
                    ->where('part_type_id', $part['partTypeId'])
                    ->first();

                if (!$newPart instanceof Part) {
                    $newPart = new Part;
                    $newPart->panel_id = $part['panelId'];
                    $newPart->part_type_id = $part['partTypeId'];
                    $newPart->save();
                }

                $newPage->parts()->attach($newPart->id, [
                    'status' => $part['status'],
                    'comment' => array_key_exists('comment', $part) ? $part['comment'] : null
                ]);
            }

            //Get divided area from page type
            $area = PageType::find($page['pageId'])
                ->partTypes()
                ->get()
                ->map(function($part){
                    return [
                        'id' => $part->id,
                        'area' => explode('/', $part->pivot->area)
                    ];
                })
                ->toArray();

            //Get part_id in newPage
            $newParts = $newPage
                ->parts()
                ->get(['id', 'part_type_id'])
                ->map(function($part) {
                    return [
                        'id' => $part->id,
                        'type_id' => $part->part_type_id,
                        'status' => $part->pivot->status
                    ];
                });

            // Change point to pixel for Matuken
            $matuken = function($f) {
                if (isset($f['point'])) {
                   return $f['point'];
                } elseif (isset($f['pointK'])) {
                    $exploded = explode(',', $f['pointK']);
                    $point = ($exploded[0]*2).','.($exploded[1]*2);
                    return $point;
                } else {
                    return null;
                }
            };

            // Get part_id from point
            $getPartIdfromArea = function($f) use ($matuken, $newParts, $area) {
                if ($matuken($f)) {
                    $exploded = explode(',', $matuken($f));

                    $x = intval($exploded[0]);
                    $y = intval($exploded[1]);

                    $part_type_id = 0;

                    foreach ($area as $a) {
                        $x1 = intval($a['area'][0]);
                        $y1 = intval($a['area'][1]);
                        $x2 = intval($a['area'][2]);
                        $y2 = intval($a['area'][3]);

                        if ($x1 <= $x && $x < $x2 && $y1 <= $y && $y < $y2) {
                            $part_type_id = $a['id'];
                        }
                    }

                    $filtered = $newParts->filter(function ($part) use ($part_type_id) {
                        return $part['type_id'] == $part_type_id && $part['status'] < 2;
                    });

                   return $filtered->first()['id'];
                }
            };

            // Create failure
            if (array_key_exists('failures', $page) && count($page['failures']) !== 0) {
                foreach ($page['failures'] as $f) {
                    if ($getPartIdfromArea($f)) {
                        $new_fp = new FailurePosition;
                        $new_fp->page_id = $newPage->id;
                        $new_fp->failure_id = $f['id'];
                        $new_fp->part_id = $getPartIdfromArea($f);
                        $new_fp->point = $matuken($f);
                        $new_fp->save();

                        if (array_key_exists('commentId', $f)) {
                            DB::table('modification_failure_position')->insert([
                                'page_id' => $newPage->id,
                                'fp_id' => $new_fp->id,
                                'm_id' => $f['commentId'],
                                'comment' => array_key_exists('comment', $f) ? $f['comment'] : ''
                            ]);
                        }
                    }
                }
            }

            // Create holes
            if (array_key_exists('holes', $page) && count($page['holes']) !== 0) {
                foreach ($page['holes'] as $key => $h) {
                    $new_hole_page = new HolePage;
                    $new_hole_page->page_id = $newPage->id;
                    $new_hole_page->hole_id = $h['id'];
                    $new_hole_page->status = $h['status'];
                    $new_hole_page->save();

                    if (array_key_exists('holeModificationId', $h)) {
                        DB::table('hole_page_hole_modification')->insert([
                            'page_id' => $newPage->id,
                            'hp_id' => $new_hole_page->id,
                            'hm_id' => $h['holeModificationId'],
                            'comment' => ""
                        ]);
                    }
                }
            }

            // Create comments
            if (array_key_exists('comments', $page) && count($page['comments']) !== 0) {
                DB::table('modification_failure_position')->insert(array_map(function($c) use ($newPage) {
                        return [
                            'page_id' => $newPage->id,
                            'fp_id' => $c['failurePositionId'],
                            'm_id' => $c['commentId'],
                            'comment' => array_key_exists('comment', $c) ? $c['comment'] : ''
                        ];
                    },
                    $page['comments'])
                );
            }

            // Create hole modification
            if (array_key_exists('holeModifications', $page) && count($page['holeModifications']) !== 0) {
                DB::table('hole_page_hole_modification')->insert(array_map(function($hm) use ($newPage) {
                        return [
                            'page_id' => $newPage->id,
                            'hp_id' => $hm['holePageId'],
                            'hm_id' => $hm['holeModificationId'],
                            'comment' => array_key_exists('comment', $hm) ? $hm['comment'] : ''
                        ];
                    },
                    $page['holeModifications'])
                );
            }
        }

        $export_parts = [];
        foreach ($family['pages'] as $page) {
            foreach ($page['parts'] as $part) {
                $export_parts[$part['partTypeId']] = [
                    'itionGId' => $groupId,
                    'partTypeId' => $part['partTypeId'],
                    'panelId' => $part['panelId']
                ];
            }
        }

        foreach ($export_parts as $key => $p) {
            $export = new Export;
            $export->exportCSV($p['panelId'], $p['partTypeId'], $p['itionGId']);
        }

        return 'Excellent';
    }

    public function updateInspection(Request $request)
    {
        $family = $request->family;
        $familyId = $family['familyId'];

        $family_odj = InspectionFamily::find($familyId);
        $family_odj->status = $family['status'];
        $family_odj->comment = array_key_exists('comment', $family) ? $family['comment'] : null;
        // $family_odj->inspector_group = $family['choku'];
        $family_odj->updated_by = $family['updatedBy'];
        $family_odj->save();

        if (array_key_exists('deletedM', $family) && count($family['deletedM']) !== 0) {
            foreach ($family['deletedM'] as $fp_id) {
                $fp = FailurePosition::find($fp_id);
                if ($fp instanceof FailurePosition) {
                    $mfp = $fp->modifications();
                    if (!is_null($mfp)) {
                        $mfp->delete();
                    }
                } 
            }
        }

        if (array_key_exists('deletedF', $family) && count($family['deletedF']) !== 0) {
            foreach ($family['deletedF'] as $fp_id) {
                $fp = FailurePosition::find($fp_id);
                if ($fp instanceof FailurePosition) {
                    $mfp = $fp->modifications();
                    if (!is_null($mfp)) {
                        $mfp->delete();
                    }
                    $fp->delete();
                }   
            }
        }

        foreach ($family['pages'] as $page) {
            $pageId = $page['pageId'];
            $page_odj = Page::find($pageId);
            $parts_obj = $page_odj->parts()
                ->get(['id', 'part_type_id'])
                ->map(function($part) {
                    return [
                        'id' => $part->id,
                        'type_id' => $part->part_type_id
                    ];
                })
                ->keyBy('type_id');

            foreach ($page['parts'] as $part) {
                DB::table('part_page')->where('page_id', $pageId)
                    ->where('part_id', $part['partId'])
                    ->update([
                        'status' => $part['status'],
                        'comment' => array_key_exists('comment', $part) ? $part['comment'] : null
                    ]);
            }

            // Get divided area from page type
            $area = $page_odj->pageType->partTypes->map(function($part){
                return [
                    'id' => $part->id,
                    'area' => explode('/', $part->pivot->area)
                ];
            });

            // Get part_id from point
            $getPartIdfromArea = function($f) use ($parts_obj, $area) {
                $exploded = explode(',', $f['point']);

                $x = intval($exploded[0]);
                $y = intval($exploded[1]);

                $type_id = 0;
                foreach ($area as $a) {
                    $x1 = intval($a['area'][0]);
                    $y1 = intval($a['area'][1]);
                    $x2 = intval($a['area'][2]);
                    $y2 = intval($a['area'][3]);

                    if ($x1 <= $x && $x < $x2 && $y1 <= $y && $y < $y2) {
                        $type_id = $a['id'];
                    }
                }

                return $parts_obj[$type_id]['id'];
            };

            // Create failure
            if (array_key_exists('failures', $page) && count($page['failures']) !== 0) {
                foreach ($page['failures'] as $f) {
                    $new_fp = new FailurePosition;
                    $new_fp->page_id = $pageId;
                    $new_fp->failure_id = $f['id'];
                    $new_fp->part_id = $getPartIdfromArea($f);
                    $new_fp->point = $f['point'];
                    $new_fp->save();

                    if (array_key_exists('commentId', $f)) {
                        DB::table('modification_failure_position')->insert([
                            'page_id' => $pageId,
                            'fp_id' => $new_fp->id,
                            'm_id' => $f['commentId'],
                            'comment' => array_key_exists('comment', $f) ? $f['comment'] : ''
                        ]);
                    }
                }
            }

            // Update holes
            if (array_key_exists('holes', $page) && count($page['holes']) !== 0) {
                foreach ($page['holes'] as $h) {
                    $hole_page = HolePage::find($h['holePageId']);
                    $hole_page->status = $h['status'];
                    $hole_page->save();

                    DB::table('hole_page_hole_modification')->where('hp_id', '=', $h['holePageId'])->delete();

                    if (array_key_exists('holeModificationId', $h)) {
                        DB::table('hole_page_hole_modification')->insert([
                            'page_id' => $pageId,
                            'hp_id' => $h['holePageId'],
                            'hm_id' => $h['holeModificationId'],
                            'comment' => ""
                        ]);
                    }
                }
            }

            // Create comments
            if (array_key_exists('comments', $page) && count($page['comments']) !== 0) {
                DB::table('modification_failure_position')->insert(array_map(function($m) use ($pageId) {
                        return [
                            'page_id' => $pageId,
                            'fp_id' => $m['failurePositionId'],
                            'm_id' => $m['commentId'],
                            'comment' => array_key_exists('comment', $m) ? $m['comment'] : ''
                        ];
                    },
                    $page['comments'])
                );
            }
        }

        $groupId = $family_odj->inspection_group_id;
        $export_parts = [];
        foreach ($family['pages'] as $page) {
            foreach ($page['parts'] as $part) {
                $parts_obj = Part::find($part['partId']);
                $export_parts[$parts_obj->part_type_id] = [
                    'itionGId' => $groupId,
                    'partTypeId' => $parts_obj->part_type_id,
                    'panelId' => $parts_obj->panel_id
                ];
            }
        }

        foreach ($export_parts as $key => $p) {
            $export = new Export;
            $export->exportCSV($p['panelId'], $p['partTypeId'], $p['itionGId']);
        }
    }

    public function deleteInspection(Request $request)
    {
        $familyId = $request->id;

        $family = InspectionFamily::find($familyId);

        if ($family) {
            $family->deleted_at = Carbon::now();
            $family->save();

            return \Response::json([
                'message' => 'success'
            ], 200);
        }

            return \Response::json([
                'message' => 'Nothing to delete'
            ], 200);
    }
}
