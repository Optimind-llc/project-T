<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Choku;
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

    public function history($inspectionGroupId, $partTypeId, $panelId)
    {
        switch ($inspectionGroupId) {
            case 11: $expect = ['shisui' => 10]; break;
            case 14: $expect = ['shisui' => 10, 'shiage' => 11, 'kensa' => 12, 'tokken' => 13]; break;
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
                            $q->select(['id', 'label']);
                        },
                        'failurePositions.modifications.modification' => function($q) {
                            $q->select(['id', 'name', 'label']);
                        }
                    ])
                    ->get();

                $inspected_array = $inspected->map(function($ig) {
                        return $ig->inspection_group_id;
                    })
                    ->toArray();

                $history = $inspected->map(function($page) {
                    return $page->failurePositions->map(function($fp) {
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
        }

        if ($groupId == 1 || $groupId == 2 || $groupId == 10 || $groupId == 12 || $groupId == 13) {
            $itorG = $family['inspectorGroup'];
            $itor = explode(',',$family['inspector'])[1];
            $status = $family['status'];
            $panel_id = $family['pages'][0]['parts'][0]['panelId'];
            $failures = $family['pages'][0]['failures'];
            $c_failures = collect($failures)->groupBy('id')->map(function($f){
                return $f->count();
            })->toArray();

            $this->exportCSV($groupId, $panel_id, $itorG, $itor, $status, $c_failures);
        }

        if ($groupId == 11 || $groupId == 14) {
            $itorG = $family['inspectorGroup'];
            $itor = explode(',',$family['inspector'])[1];
            $status = $family['status'];
            $panel_id = $family['pages'][0]['parts'][0]['panelId'];
            $failures = $family['pages'][0]['failures'];
            $modifications = $family['pages'][0]['comments'];

            $c_failures = collect($failures)->groupBy('id')->map(function($f){
                return $f->count();
            })->toArray();

            $c_modifications = collect(array_merge($failures, $modifications))
                ->groupBy('commentId')
                ->map(function($m){
                    return $m->count();
                })
                ->toArray();

            $this->exportCSV($groupId, $panel_id, $itorG, $itor, $status, $c_failures, $c_modifications);
        }

        return 'Excellent';
    }

    // public function exportCSV($gId, $pId, $itorG, $itor, $status, $c_failures, $c_modifications = null)
    // {
    //     $now = Carbon::now();
    //     $choku = new Choku($now);
    //     $choku_num = $choku->getChoku();

    //     $dir_path = config('path.'.env('SERVER').'.output');

    //     switch ($gId) {
    //         case 1:
    //             $export = ['67149','47060','A',substr($pId, 1,7),'成型','001'];
    //             $file_name = 'M001_67149_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //         case 2:
    //             $export = ['67149','47060','A',substr($pId, 1,7),'成型','002'];
    //             $file_name = 'M001_67149_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //         case 10:
    //             $export = ['67007','47120','A',substr($pId, 1,7),'接着','止水'];
    //             $file_name = 'J_shisui_67007_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //         case 11:
    //             $export = ['67007','47120','A',substr($pId, 1,7),'接着','仕上'];
    //             $file_name = 'J_shiage_67007_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //         case 12:
    //             $export = ['67007','47120','A',substr($pId, 1,7),'接着','検査'];
    //             $file_name = 'J_kensa_67007_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //         case 13:
    //             $export = ['67007','47120','A',substr($pId, 1,7),'接着','特検'];
    //             $file_name = 'J_tokken_67007_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //         case 14:
    //             $export = ['67007','47120','A',substr($pId, 1,7),'接着','手直し'];
    //             $file_name = 'J_tenaoshi_67007_'.$now->format('Ymd_His');
    //             $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
    //             break;
    //     }

    //     $export = array_merge($export, ['680A',$itorG,$choku_num,$itor,$status]);

    //     $failures = InspectionGroup::find($gId)
    //         ->inspection
    //         ->failures()
    //         ->get(['id', 'label', 'name'])
    //         ->map(function($f) {
    //             return [
    //                 'id' => $f->id,
    //                 'label' => $f->label,
    //                 'name' => $f->name,
    //                 'type' => $f->pivot->type,
    //                 'sort' => $f->pivot->sort
    //             ];
    //         })
    //         ->toArray();
    //     foreach( $failures as $key => $row ) {
    //         $f_type_array[$key] = $row['type'];
    //         $f_label_array[$key] = $row['label'];
    //         $f_sort_array[$key] = $row['sort'];
    //     }
    //     array_multisort($f_type_array, $f_sort_array, $f_label_array, $failures);
    //     foreach ($failures as $f) {
    //         if (array_key_exists(intval($f['id']), $c_failures)) {
    //             $f_sum = $c_failures[$f['id']];
    //         }
    //         else {
    //             $f_sum = '';
    //         }
    //         array_push($export, $f_sum);
    //     }

    //     // push modification result
    //     if (isset($c_modifications)) {
    //         $modifications = InspectionGroup::find($gId)
    //             ->inspection
    //             ->modifications()
    //             ->get(['id', 'label', 'name'])
    //             ->map(function($f) {
    //                 return [
    //                     'id' => $f->id,
    //                     'label' => $f->label,
    //                     'name' => $f->name,
    //                     'type' => $f->pivot->type,
    //                     'sort' => $f->pivot->sort
    //                 ];
    //             })
    //             ->toArray();

    //         foreach( $modifications as $key => $row ) {
    //             $m_type_array[$key] = $row['type'];
    //             $m_label_array[$key] = $row['label'];
    //             $m_sort_array[$key] = $row['sort'];
    //         }
    //         array_multisort($m_type_array, $m_sort_array, $m_label_array, $modifications);

    //         foreach ($modifications as $m) {
    //             if (array_key_exists($m['id'], $c_modifications)) {
    //                 $modi_sum = $c_modifications[$m['id']];
    //             }
    //             else {
    //                 $modi_sum = '';
    //             }

    //             array_push($export, $modi_sum);
    //         }
    //     }


    //     array_push($export, $now->format('Y/m/d H:i:s'));

    //     if( touch($file_path) ){
    //         $file = new \SplFileObject($file_path, 'w');

    //         foreach( $export as $key => $val ){
    //             $export_raw[] = mb_convert_encoding($val, 'SJIS-win');
    //         }

    //         $file->fputcsv($export_raw);
    //     }
    // }

    public function exportCSV($gId, $pId, $itorG, $itor, $status, $c_failures, $c_modifications = null)
    {
        $now = Carbon::now();
        $choku = new Choku($now);
        $choku_num = $choku->getChoku();

        $dir_path = config('path.'.env('SERVER').'.output');

        switch ($gId) {
            case 1:
                $export = ['67149','47060','A',substr($pId, 1,7),'成型','001'];
                $file_name = 'M001_67149_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 2:
                $export = ['67149','47060','A',substr($pId, 1,7),'成型','002'];
                $file_name = 'M001_67149_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Seikei'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 10:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','止水'];
                $file_name = 'J_shisui_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 11:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','仕上'];
                $file_name = 'J_shiage_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 12:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','検査'];
                $file_name = 'J_kensa_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 13:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','特検'];
                $file_name = 'J_tokken_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
            case 14:
                $export = ['67007','47120','A',substr($pId, 1,7),'接着','手直し'];
                $file_name = 'J_tenaoshi_67007_'.$now->format('Ymd_His');
                $file_path = $dir_path.DIRECTORY_SEPARATOR.'Setchaku'.DIRECTORY_SEPARATOR.$file_name.'.csv';
                break;
        }

        $export = array_merge($export, ['680A',$itorG,$choku_num,$itor,$status]);

        $failures = InspectionGroup::find($gId)
            ->inspection
            ->failures()
            ->get(['id', 'label', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'label' => $f->label,
                    'name' => $f->name,
                    'type' => $f->pivot->type,
                    'sort' => $f->pivot->sort
                ];
            })
            ->toArray();
        foreach( $failures as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }
        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failures);
        foreach ($failures as $f) {
            if (array_key_exists(intval($f['id']), $c_failures)) {
                $f_sum = $c_failures[$f['id']];
            }
            else {
                $f_sum = '';
            }
            array_push($export, $f_sum);
        }

        // push modification result
        if (isset($c_modifications)) {
            $modifications = InspectionGroup::find($gId)
                ->inspection
                ->modifications()
                ->get(['id', 'label', 'name'])
                ->map(function($f) {
                    return [
                        'id' => $f->id,
                        'label' => $f->label,
                        'name' => $f->name,
                        'type' => $f->pivot->type,
                        'sort' => $f->pivot->sort
                    ];
                })
                ->toArray();

            foreach( $modifications as $key => $row ) {
                $m_type_array[$key] = $row['type'];
                $m_label_array[$key] = $row['label'];
                $m_sort_array[$key] = $row['sort'];
            }
            array_multisort($m_type_array, $m_sort_array, $m_label_array, $modifications);

            foreach ($modifications as $m) {
                if (array_key_exists($m['id'], $c_modifications)) {
                    $modi_sum = $c_modifications[$m['id']];
                }
                else {
                    $modi_sum = '';
                }

                array_push($export, $modi_sum);
            }
        }


        array_push($export, $now->format('Y/m/d H:i:s'));

        if( touch($file_path) ){
            $file = new \SplFileObject($file_path, 'w');

            foreach( $export as $key => $val ){
                $export_raw[] = mb_convert_encoding($val, 'SJIS-win');
            }

            $file->fputcsv($export_raw);
        }
    }

    public function inspection2($itionG_id)
    {
        $inspection_group = new InspectionGroup;

        return [
            'group' => $inspection_group->findWithRelated($itionG_id)
        ];
    }
}
