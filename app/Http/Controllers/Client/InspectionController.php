<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
// Exceptions
use JWTAuth;
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

    protected function formatInspectors($inspectors) {

        return $inspectors->map(function ($i) {
                return [
                    'id' => $i->id,
                    'name' => $i->name,
                    'code' => $i->code,
                    'group' => $i->group_code,
                    'sort' => $i->pivot->sort
                ];
            })
            ->sortBy('sort')
            ->groupBy('group');
    }

    protected function finishOrAdjust(Request $request, $inspection_group)
    {
        $validator = app('validator')->make(
            $request->all(),
            ['panelId' => ['required', 'alpha_dash']]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        if ($request->inspection == 'finish') {
            $enable_inspection_list = ['water_stop'];
        } else {
            $enable_inspection_list = ['check', 'special_check'];
        }

        // inspection内のgroups配列を取り出す、inner_assyでfilltering済み
        $map1 = function ($i) {
            return $i->groups->filter(function ($g) {
                return $g->division_en == 'inner_assy';
            });
        };

        // inner_assyでないgroupsを削除
        $filter1 = function ($g) {
            return $g->count() > 0;
        };

        // groups[0] が inner_assyなので、そのfamilyを取り出す
        $map2 = function ($g) {
            return $g[0]->families;
        };

        // familyのないgroupsを削除
        $filter2 = function ($f) {
            return $f->count() > 0;
        };

        // pagesが0以上のもののみにする
        $map3 = function ($f) {
            return $f->filter(function ($f) {
                return $f->pages->count() > 0;
            });
        };

        // pageのないfamilyを削除
        $filter3 = function ($f) {
            return $f->count() > 0;
        };

        // pagesが0以上のもののみにする
        $map4 = function ($f) {
            return $f->first()
                ->pages
                ->first()
                ->failurePositions
                ->map(function ($fp) {
                    return [
                        'failurePositionId' => $fp->id,
                        'label' => $fp->failure->sort,                            
                        'point' => $fp->point,
                        'point_sub' => $fp->point_sub
                    ];
                })
                ->toArray();
        };

        $filterInspection = function($i) {
            return $i->en != 'adjust' && $i->groups[0]->families->count() > 0;
        };

        $mergeToOne = function ($a, $b) {
            return array_merge($a, $b);
        };

        $history = $this->findProcessByEn($request->process)
            ->getInspectionsHasSamePanelID(
                $request->division,
                $request->panelId,
                $enable_inspection_list
            )
            ->map($map1)
            ->filter($filter1)
            ->map($map2)
            ->filter($filter2)
            ->map($map3)
            ->filter($filter3)
            ->map($map4)
            ->reduce($mergeToOne, []);

        return [
            'group' => [
                'id' => $inspection_group->id,
                'inspectorGroups' => $this->formatInspectors($inspection_group->inspectors),
                'failures' => $inspection_group->inspection->process->failures->map(function ($failure) {
                    return [
                        'id' => $failure->id,
                        'name' => $failure->name,
                        'type' => $failure->pivot->type
                    ];
                }),
                'comments' => $inspection_group->inspection->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'label' => $comment->sort,
                        'message' => $comment->message
                    ];
                }),
                'pages' => $inspection_group->pageTypes->map(function ($page) use ($history) {
                    return [
                        'id' => $page->id,
                        'parts' => $page->partTypes->map(function ($part) use ($history) {
                            return [
                                'id' => $part->id,
                                'name' => $part->name,
                                'pn' => $part->pn,
                                'vehicle' => $part->vehicle->number
                            ];
                        }),
                        'figure' => [
                            'path' => 'img/figures/'.$page->figure->path,
                            'holes' => $page->figure->holes->map(function ($hole) {
                                return [
                                    'id' => $hole->id,
                                    'point' => $hole->point
                                ];
                            })
                        ],
                        'history' => $history
                    ];
                })
            ]
        ];
    }

    /**
     * Get user from JWT token
     */
    public function inspection(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'division' => ['required', 'alpha_dash'],
                'process' => ['required', 'alpha_dash'],
                'inspection' => ['required', 'alpha_dash'],
                'line' => ['alpha_num']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $inspection_group = $this->findInspectionGroup(
            $request->inspection,
            $request->process,
            $request->division,
            $request->line
        );

        if ($request->inspection == 'finish' || $request->inspection == 'adjust') {
            return $this->finishOrAdjust($request, $inspection_group);
        }

        return [
            'group' => [
                'id' => $inspection_group->id,
                'inspectorGroups' => $this->formatInspectors($inspection_group->inspectors),
                'failures' => $inspection_group->inspection->process->failures->map(function ($failure) {
                    return [
                        'id' => $failure->id,
                        'label' => $failure->sort,
                        'name' => $failure->name,
                        'type' => $failure->pivot->type
                    ];
                }),
                'comments' => $inspection_group->inspection->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'message' => $comment->message
                    ];
                }),
                'pages' => $inspection_group->pageTypes->map(function ($page) {
                    return [
                        'id' => $page->id,
                        'pdf' => [
                            'path' => $page->pdf->path,
                            'area' => $page->pdf->area
                        ],
                        'parts' => $page->partTypes->map(function ($part) {
                            return [
                                'id' => $part->id,
                                'name' => $part->name,
                                'pn' => $part->pn,
                                'vehicle' => $part->vehicle->number
                            ];
                        }),
                        'figure' => [
                            'path' => 'img/figures/'.$page->figure->path,
                            'holes' => $page->figure->holes->map(function ($hole) {
                                return [
                                    'id' => $hole->id,
                                    'point' => $hole->point,
                                    'label' => $hole->label,
                                    'direction' => $hole->direction,
                                    'color' => $hole->color,
                                    'border' => $hole->border,
                                    'shape' => $hole->shape
                                ];
                            })
                        ]
                    ];
                })
            ]
        ];
    }

    /**
     * Get user from JWT token
     */
    public function saveInspection(Request $request)
    {
        $family = $request->family;
        $groupId = $family['groupId'];

        //Duplicate detection
        foreach ($family['pages'] as $page) {
            $page_type_id = $page['pageId'];
            $part_ids = [];

            foreach ($page['parts'] as $part) {
                $newPart = Part::where('panel_id', $part['panelId'])
                    ->where('part_type_id', $part['partTypeId'])
                    ->first();

                if ($newPart instanceof Part) {
                    array_push($part_ids, $newPart->id);
                }
            }

            $newPage = Page::where('page_type_id', $family['pages'][0]['pageId'])
                ->whereHas('parts', function($q) use ($part_ids) {
                    $q->whereIn('id', $part_ids);
                })
                ->first();

            if ($newPage instanceof Page) {
                throw new StoreResourceFailedException('The part already be inspected in ather page(page_id = '.$newPage->id.').');
            }
        }

        $newFamily = new InspectionFamily;
        $newFamily->inspection_group_id = $groupId;
        $newFamily->status = $family['status'];
        $newFamily->inspector_group = $family['inspectorGroup'];
        $newFamily->created_by = $family['inspector'];
        $newFamily->save();

        foreach ($family['pages'] as $key => $page) {
            /*
             * ここからStatusがなくなる
             */
            $newPage = new Page;
            $newPage->page_type_id = $page['pageId'];
            $newPage->table = isset($page['table']) ? $page['table'] : null;
            // $newPage->status = $page['status'];
            $newPage->family_id = $newFamily->id;
            $newPage->save();

            foreach ($page['parts'] as $part) {
                /*
                 * ここにStatusが入る
                 */
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
            if (count($page['failures']) != 0) {
                DB::table('failure_positions')->insert(array_map(function($f) use ($groupId, $newPage, $matuken, $getPartIdfromArea) {

                        // Find process_id type in this inspection_group
                        $inspectionGroup = new InspectionGroup;
                        $process_id = $inspectionGroup->find($groupId)
                            ->inspection()
                            ->first()
                            ->process_id;

                        // Find failure type in this process
                        $failureType = DB::table('failure_process')
                            ->where('failure_id', $f['id'])
                            ->where('process_id', $process_id)
                            ->first()
                            ->type;

                        return [
                            'page_id' => $newPage->id,
                            'failure_id' => $f['id'],
                            'part_id' => $getPartIdfromArea($f),
                            'point' => $matuken($f),
                            'type' => $failureType
                            // 'point_sub' => $f['pointSub'] ? $f['pointSub'] : ''
                        ];
                    },
                    $page['failures'])
                );
            }

            // Create holes
            if (isset($page['holes']) && count($page['holes']) != 0) {
                DB::table('hole_page')->insert(array_map(function($h) use ($newPage) {
                        return [
                            'page_id' => $newPage->id,
                            'hole_id' => $h['id'],
                            'status' => $h['status']
                        ];
                    },
                    $page['holes'])
                );
            }

            // Create comments
            if (isset($page['comments']) && count($page['comments']) != 0) {
                DB::table('comment_failure_position')->insert(array_map(function($c) use ($newPage) {
                        return [
                            'page_id' => $newPage->id,
                            'failure_position_id' => $c['failurePositionId'],
                            'comment_id' => $c['commentId']
                        ];
                    },
                    $page['comments'])
                );
            }
        }

        if ($groupId == 1 || $groupId == 2) {
            $itorG = $family['inspectorGroup'];
            $itor = explode(',',$family['inspector'])[1];
            $status = $family['status'];
            $panel_id = $family['pages'][0]['parts'][0]['panelId'];

            $this->exportCSV($groupId, $panel_id, $itorG, $itor, $status);
        }

        return 'Excellent';
    }

    public function exportCSV ($gId, $pId, $itorG, $itor, $status) {
        // XXXX_IIIII_YYYYMMDD_HHMMSS.pdf
        // XXXX：工程　"M001"＝成形１ライン
        // IIIII：品番　上位５桁　例えば"67149"
        // YYYYMMDD_HHMMSS：　データが登録された　年月日_時分秒

        $now = Carbon::now();
        $now_f = $now->format('Ymd_His');
        $now_c = $now->format('YmdHis');

        $XXXX = $gId == 1 ? 'M0001' : 'M0002';
        $line = $gId == 1 ? '001' : '002';

        $file_name = 'M0001'.'_'.'67149'.'_'.$now_f;
        $file_path = base_path('output/'.$file_name.'.csv');
     
        // CSVに出力するタイトル行
        $export_csv_title = array('品番','パネルID','工程','ライン','車種','直','検査者','出荷判定','重要不良１','重要不良２','重要不良３','重要不良４','重要不良５','重要不良６','重要不良７','不良１','不良２','不良３','不良４','不良５','不良６','不良７','不良８','不良９','不良１０','不良１１','不良１２','不良１３','不良１４','時刻');
        $res_export = array('67149',$pId,'成型',$line,'680A',$itorG,$itor,$status,'','','','','','','','','','','','','','','','','','','','','');

        if( touch($file_path) ){
            $file = new \SplFileObject( $file_path, 'w' ); 
             
            foreach( $export_csv_title as $key => $val ){
                $export_header[] = mb_convert_encoding($val, 'UTF-8');
            }

            foreach( $res_export as $key => $val ){
                $export_raw[] = mb_convert_encoding($val, 'UTF-8');
            }
     
            // エンコードしたタイトル行を配列ごとCSVデータ化
            $file->fputcsv($export_header);
            $file->fputcsv($export_raw);
        }
    }
}

