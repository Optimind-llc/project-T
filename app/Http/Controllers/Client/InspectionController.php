<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Choku;
use App\Export;
// Models
use App\Models\Process;
use App\Models\Inspector;
use App\Models\InspectorGroup;
use App\Models\Inspection;
use App\Models\InspectionGroup;
use App\Models\PageType;
use App\Models\Client\InspectionFamily;
use App\Models\Client\Page;
use App\Models\Client\Part;
use App\Models\Client\FailurePage;
use App\Models\Client\FailurePosition;
use App\Models\Client\HolePage;
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
    protected function findProcessByEn($en) {
        $process = Process::where('id', $en)->first();

        if (!$process instanceof Process) {
            throw new NotFoundHttpException('Process not found');
        }

        return $process;
    }

    protected function findInspectionGroup($inspection_en, $process_en, $division_en, $line = null) {
        $inspection = $this->findProcessByEn($process_en)
            ->inspections()
            ->where('en', $inspection_en)
            ->first();

        if (!$inspection instanceof Inspection) {
            throw new NotFoundHttpException('Inspection not found');
        }

        $group = $inspection->getByDivisionWithRelated($division_en, $line);

        if (!$group instanceof InspectionGroup) {
            throw new NotFoundHttpException('Inspection group not found');
        }

        return $group;
    }

    public function inspection($itionG_id)
    {
        $inspection_group = new InspectionGroup;

        return [
            'group' => $inspection_group->findWithRelated($itionG_id)
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
        $part_type_id = $request->partType;

        $part = Part::where('panel_id', $request->panelId)
            ->where('part_type_id', $part_type_id)
            ->first();

        if (!$part instanceof Part) {
            $inspected_array = [];
            $pages = [['history' => []]];
        }
        else {
            if ($part->pages->count() == 0) {
                $inspected_array = [];
                $pages = [['history' => []]];
            }
            else {
                $inspected = $part->pages()
                    ->join('inspection_families as if', 'pages.family_id', '=', 'if.id')
                    ->select('pages.*', 'if.inspection_group_id')
                    ->whereIn('if.inspection_group_id', $request->id)
                    ->with([
                        'failurePositions' => function($q) use ($part_type_id) {
                            $q->whereHas('part', function ($q) use ($part_type_id) {
                                $q->where('part_type_id', '=', $part_type_id);
                            })
                            ->select(['id', 'point', 'page_id', 'failure_id'])
                            ->get();
                        },
                        'failurePositions.failure' => function($q) {
                            $q->select(['id', 'label']);
                        },
                        'failurePositions.modifications.modification' => function($q) {
                            $q->select(['id', 'name', 'label']);
                        },
                        'holes' => function($q) use ($part_type_id) {
                            $q->whereHas('partType', function ($q) use ($part_type_id) {
                                $q->where('id', '=', $part_type_id);
                            })->get();
                        }
                    ])
                    ->get();

                $inspected_array = $inspected->map(function($ig) {
                        return $ig->inspection_group_id;
                    })
                    ->toArray();

                $pages = $inspected->map(function($page) {
                    return [
                        'pageTypeId' => $page->page_type_id,
                        'history' => $page->failurePositions->map(function($fp) {
                            $cLabel = "";
                            if ($fp->modifications->count() !== 0) {
                                $cLabel = $fp->modifications->first()->modification->label;
                            }

                            return [
                                'failurePositionId' => $fp->id,
                                'label' => $fp->failure->label,
                                'point' => $fp->point,
                                'cLabel' => $cLabel
                            ];
                        }),
                        'holeHistory' => $page->holes->map(function($h) {
                            return [
                                'id' => $h->id,
                                'holePageId' => $h->pivot->id,
                                'status' => $h->pivot->status
                            ];
                        })
                    ];
                });
            }
        }

        foreach ($request->id as $id) {
            $name = InspectionGroup::find($id)->inspection->en;
            $heritage[$name] = in_array($id, $inspected_array) ? 1 : 0;
        }

        return [
            'heritage' => $heritage,
            'group' => [
                'pages' => $pages
            ]
        ];
    }

    public function saveInspection(Request $request)
    {
        $family = $request->family;
        $groupId = $family['groupId'];

        //Duplicate detection
        foreach ($family['pages'] as $page) {
            $page_type_id = $page['pageId'];

            foreach ($page['parts'] as $part) {
                $newPart = Part::where('panel_id', $part['panelId'])
                    ->where('part_type_id', $part['partTypeId'])
                    ->first();

                if ($newPart instanceof Part) {
                    $newPage = Page::where('page_type_id', $page['pageId'])
                        ->whereHas('parts', function($q) use ($newPart) {
                            $q->where('id', $newPart->id);
                        })
                        ->first();

                    if ($newPage instanceof Page) {
                        return \Response::json([
                            'message' => $part['panelId'].' already be inspected in ather page(page_id = '.$newPage->id.').',
                            'pageId' => $newPage->id,
                            'panelId' => $part['panelId'],
                            'pn' => $newPart->partType->pn
                        ], 400);
                        throw new JsonException($part['panelId'].' already be inspected in ather page(page_id = '.$newPage->id.').');
                    }
                }
            }
        }

        $newFamily = new InspectionFamily;
        $newFamily->inspection_group_id = $groupId;
        $newFamily->status = $family['status'];
        $newFamily->inspector_group = $family['inspectorGroup'];
        $newFamily->created_by = $family['inspector'];
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

                $newPage->parts()->attach($newPart->id, ['status' => $part['status']]);
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
                        'type_id' => $part->part_type_id
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
                        return $part['type_id'] == $part_type_id;
                    });

                   return $filtered->first()['id'];
                }
            };

            // Create failure
            if (array_key_exists('failures', $page) && count($page['failures']) !== 0) {
                foreach ($page['failures'] as $f) {
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
                // DB::table('hole_page')->insert(array_map(function($h) use ($newPage) {
                //         return [
                //             'page_id' => $newPage->id,
                //             'hole_id' => $h['id'],
                //             'status' => $h['status']
                //         ];
                //     },
                //     $page['holes'])
                // );
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
}
