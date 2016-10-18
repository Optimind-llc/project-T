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
use App\Models\Client\FailurePosition;
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

    public function history($inspectionGroupId, $partTypeId, $panelId)
    {
        switch ($inspectionGroupId) {
            case 11: $expect = ['waterStop' => 10]; break;
            case 14: $expect = ['check' => 12, 'specialCheck' => 13]; break;
            default: $expect = []; break;                
        }

        $heritage = [];

        $part = Part::where('panel_id', $panelId)
            ->where('part_type_id', $partTypeId)
            ->first();

        if (!$part instanceof Part) {
            $inspected_array = [];
            $history = [];
        }
        else {
            if ($part->pages->count() == 0) {
                $inspected_array = [];
                $history = [];
            }
            else {
                $inspected = $part->pages()
                    ->join('inspection_families as if', 'pages.family_id', '=', 'if.id')
                    ->select('pages.*', 'if.inspection_group_id')
                    ->whereIn('if.inspection_group_id', array_values($expect))
                    ->with([
                        'failurePositions' => function($q) {
                            $q->select(['id', 'point', 'page_id', 'failure_id']);
                        },
                        'failurePositions.failure' => function($q) {
                            $q->select(['id', 'sort']);
                        }
                    ])
                    ->get();

                $inspected_array = $inspected->map(function($ig) {
                        return $ig->inspection_group_id;
                    })
                    ->toArray();

                $history = $inspected->map(function($page) {
                    return $page->failurePositions->map(function($f) {
                        return [
                            'failurePositionId' => $f->id,
                            'label' => $f->failure->sort,
                            'point' => $f->point
                        ];
                    });
                })
                ->reduce(function ($carry, $failures) {
                    return array_merge($carry, $failures->toArray());
                }, []);
            }
        }

        foreach ($expect as $name => $id) {
            $heritage[$name] = in_array($id, $inspected_array) ? 1 : 0;
        }

        return [
            'heritage' => $heritage,
            'group' => [
                'pages' => [
                    [
                        'history' => $history
                    ]
                ]
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
                        throw new StoreResourceFailedException($part['panelId'].' already be inspected in ather page(page_id = '.$newPage->id.').');
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
            if (count($page['failures']) != 0) {
                foreach ($page['failures'] as $key => $f) {
                    $failure_position = new FailurePosition;
                    $failure_position->page_id = $newPage->id;
                    $failure_position->failure_id = $f['id'];
                    $failure_position->part_id = $getPartIdfromArea($f);
                    $failure_position->point = $matuken($f);
                    $failure_position->save();

                    if (array_key_exists('commentId', $f)) {
                        DB::table('comment_failure_position')->insert([
                            'page_id' => $newPage->id,
                            'failure_position_id' => $failure_position->id,
                            'comment_id' => $f['commentId']
                        ]);
                    }
                }
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
            $failures = $family['pages'][0]['failures'];

            $this->exportCSV($groupId, $panel_id, $itorG, $itor, $status, $failures);
        }

        return 'Excellent';
    }

    public function exportCSV ($gId, $pId, $itorG, $itor, $status, $failures)
    {
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

        // $file_path = base_path('output/'.$file_name.'.csv');
        $file_path = config('path.output').$file_name.'.csv';

        $fail = collect($failures)->groupBy('id')->map(function($f){
            return $f->count();
        })->toArray();

        $all_failures = Process::find('molding')
            ->failures()
            ->orderBy('id')
            ->get();

        // CSVに出力するタイトル行
        // $export_csv_title = array('品番','パネルID','工程','ライン','車種','直','検査者','出荷判定','不良１','不良２','不良３','不良４','不良５','不良６','不良７','不良１','不良２','不良３','不良４','不良５','不良６','不良７','不良８','不良９','不良１０','不良１１','不良１２','不良１３','不良１４','時刻');
        // $res_export = array('67149',$pId,'成型',$line,'680A',$itorG,$itor,$status,$fail[1],$fail[2],$fail[3],$fail[4],$fail[5],$fail[6],$fail[7],$fail[8],$fail[9],$fail[10],$fail[11],$fail[12],$fail[13],$fail[14],$fail[15],$fail[16],$fail[17],$fail[18],$fail[19],$fail[20],$fail[21]);


        $res_export = array('67149',$pId,'成型',$line,'680A',$itorG,$itor,$status);


        foreach ($all_failures as $key => $f) {
            array_push($res_export, array_key_exists($f['id'], $fail) ? $fail[$f['id']] : '');
        }

        if( touch($file_path) ){
            $file = new \SplFileObject( $file_path, 'w' ); 
             
            // foreach( $export_csv_title as $key => $val ){
            //     $export_header[] = mb_convert_encoding($val, 'UTF-8');
            // }

            foreach( $res_export as $key => $val ){
                $export_raw[] = mb_convert_encoding($val, 'UTF-8');
            }
     
            // エンコードしたタイトル行を配列ごとCSVデータ化
            // $file->fputcsv($export_header);
            $file->fputcsv($export_raw);
        }
    }
}

