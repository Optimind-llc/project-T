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
                            'comment' => array_key_exists('comment', $f) ? $c['comment'] : ''
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

            // $this->exportCSV($groupId, $panel_id, $itorG, $itor, $status, $failures);
        }

        return 'Excellent';
    }

    public function exportCSV($gId, $pId, $itorG, $itor, $status, $failures)
    {
      $now = Carbon::now();
      $now_f = $now->format('Ymd_His');
      $now_c = $now->format('Ymd H:i:s');
      $now_t = $now->format('Hi');

      if ($now_t > 200 && $now_t <= 1615) {
        $tyoku = 1;
      } else {
        $tyoku = 2;
      }

      $XXXX = $gId == 1 ? 'M0001' : 'M0002';
      $line = $gId == 1 ? '001' : '002';

      $file_name = $XXXX.'_'.'67149'.'_'.$now_f;

      $file_path = base_path('output/'.$file_name.'.csv');
      // $file_path = 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output'.DIRECTORY_SEPARATOR.$file_name.'.csv';

      $fail = collect($failures)->groupBy('id')->map(function($f){
        return $f->count();
      })->toArray();

      $all_failures = Process::where('id', 'molding')
        ->first()
        ->failures()
        ->orderBy('sort')
        ->get(['id', 'sort', 'name'])
        ->map(function($f) {
          return [
            'id' => $f->id,
            'sort' => $f->sort,
            'name' => $f->name,
            'type' => $f->pivot->type
          ];
        })
        ->sortBy('type')
        ->values()
        ->all();

        foreach( $all_failures as $key => $row ) {
          $tmp_type_array[$key] = $row["type"];
          $tmp_sort_array[$key] = $row["sort"];
        }

        array_multisort($tmp_type_array, $tmp_sort_array, $all_failures);

        $res_export = array('67149','47060','A',substr($pId, 1,7),'成型',$line,'680A',$itorG,$tyoku,$itor,$status);

        foreach ($all_failures as $key => $f) {
            array_push($res_export, array_key_exists($f['id'], $fail) ? $fail[$f['id']] : '');
        }

        array_push($res_export, $now_c);


        if( touch($file_path) ){
            $file = new \SplFileObject( $file_path, 'w' ); 

            foreach( $res_export as $key => $val ){
                // $export_raw[] = mb_convert_encoding($val, 'UTF-8');
                mb_convert_encoding($hensu, 'SJIS-win');
            }
            $file->fputcsv($export_raw);
        }
    }
}
