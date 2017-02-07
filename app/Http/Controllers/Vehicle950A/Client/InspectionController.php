<?php

namespace App\Http\Controllers\Vehicle950A\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Choku;
// Models
use App\Models\Vehicle950A\Process;
use App\Models\Vehicle950A\Inspection;
use App\Models\Vehicle950A\InspectionResult;
use App\Models\Vehicle950A\PartType;
use App\Models\Vehicle950A\Part;
use App\Models\Vehicle950A\Failure;
use App\Models\Vehicle950A\Modification;
// Repositories
use App\Repositories\WorkerRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\InspectionResultRepository;
// Exceptions
use JWTAuth;
use App\Exceptions\JsonException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
 * Class InspectionController
 * @package App\Http\Controllers
 */
class InspectionController extends Controller
{
    protected $worker;
    protected $failureType;
    protected $modificationType;
    protected $holeModificationType;
    protected $inspectionResult;

    public function __construct (
        WorkerRepository $worker,
        FailureTypeRepository $failureType,
        ModificationTypeRepository $modificationType,
        HoleModificationTypeRepository $holeModificationType,
        InspectionResultRepository $inspectionResult
    )
    {
        $this->worker = $worker;
        $this->failureType = $failureType;
        $this->modificationType = $modificationType;
        $this->holeModificationType = $holeModificationType;
        $this->inspectionResult = $inspectionResult;
    }

    public function getInspection(Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $pt_names = $request->partNames;

        $first_pt = PartType::where('name', '=', $pt_names[0])->first();
        $d1 = $first_pt->division1;
        $d2 = $first_pt->division2;

        $pt = PartType::whereIn('name', $pt_names)
            ->with([
                'figures' => function($q) use ($process, $inspection){
                    $q->where('process', '=', $process)->where('inspection', '=', $inspection);
                },
                'figures.holeTypes' => function($q) {
                    $q->where('hole_types.status', '=', 1);
                }
            ])
            ->get()
            ->map(function($pt) {
                return [
                    'pn' => $pt->pn,
                    'name' => $pt->name,
                    'figures' => $pt->figures->map(function($f) {
                        return [
                            'id' => $f->id,
                            'path' => '/img/figures/950A/'.$f->path,
                            'sizeX' => $f->size_x,
                            'sizeY' => $f->size_y,
                            'holes' => $f->holeTypes->map(function($ht) {
                                return [
                                    'id' => $ht->id,
                                    'x' => $ht->x,
                                    'y' => $ht->y,
                                    'label' => $ht->label,
                                    'direction' => $ht->direction,
                                    'shape' => $ht->shape,
                                    'border' => $ht->border,
                                    'color' => $ht->color
                                ];
                            })
                        ];
                    })
                ];
            });

        return [
            'workers' => $this->worker->formated($process, $inspection, $d1),
            'failures' => $this->failureType->sorted($process, $inspection, $d2),
            'modifications' => $this->modificationType->sorted($process, $inspection, $d2),
            'hModifications' => $this->holeModificationType->sorted($process, $inspection, $d2),
            'partTypes' => $pt,
        ];
    }

    public function saveInspection(Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $line = $request->line;
        $choku = $request->choku;
        $worker = $request->worker;
        $parts = $request->parts;

        $inspected = [];
        DB::connection('950A')->beginTransaction();
        foreach ($parts as $part) {
            $pt = PartType::find($part['pn']);
            $d1 = $pt->division1;
            $d2 = $pt->division2;

            $targetPart = Part::where('pn', '=', $part['pn'])
                ->where('panel_id', '=', $part['panelId'])
                ->first();

            if (!$targetPart instanceof Part) {
                $targetPart = new Part;
                $targetPart->panel_id = $part['panelId'];
                $targetPart->pn = $part['pn'];
                $targetPart->save();
            }

            // Check duplicate
            if ($this->inspectionResult->exist($process, $inspection, $targetPart->id)) {
                $inspected[] = [
                    'panelId' => $part['panelId'],
                    'name' => $targetPart->partType->name,
                    'pn' => $targetPart->partType->pn
                ];
            } else {
                $param = [
                    'part_id' => $targetPart->id,
                    'process' => $process,
                    'inspection' => $inspection,
                    'line' => $line,
                    'ft_ids' => $this->failureType->narrowedIds($process, $inspection, $d2),
                    'created_choku' => $choku,
                    'created_by' => $worker,
                    'status' => $part['status'],
                    'comment' => $part['status']
                ];

                $fs = [];
                if (array_key_exists('failures', $part)) {
                    $fs = $part['failures'];
                }

                $ms = [];
                if (array_key_exists('modifications', $part)) {
                    $ms = $part['modifications'];
                }

                $hs = [];
                if (array_key_exists('holes', $part)) {
                    $hs = $part['holes'];
                }

                $hms = [];
                if (array_key_exists('holeModifications', $part)) {
                    $hms = $part['holeModifications'];
                }

                $this->inspectionResult->create($param, $fs, $ms, $hs, $hms);
            }
        }

        if (count($inspected) === 0) {
            DB::connection('950A')->commit();
            return [
                'message' => 'Save inspection succeeded'
            ];
        } else {
            DB::connection('950A')->rollBack();
            return \Response::json([
                'message' => 'Some parts already be inspected',
                'inspected' => $inspected
            ], 400);
        }
    }

    public function result(Request $request)
    {
        $pn = $request->pn;
        $panelId = $request->panelId;
        $targets = $request->targets;

        $part = Part::where('panel_id', $panelId)->where('pn', $pn)->first();

        $partId = 0;
        if ($part instanceof Part) {
            $partId = $part->id;
        }

        $results = [];
        foreach ($targets as $t) {
            $result = $this->inspectionResult->all($t['process'], $t['inspection'], $partId);

            if ($result) {
                $results[$t['process'].'_'.$t['inspection']] = [
                    'id' => $result['id'],
                    'line' => $result['line'],
                    'status' => $result['status'],
                    'comment' => $result['comment'],
                    'choku' => $result['created_choku'],
                    'createdBy' => $result['created_by'],
                    'createdAt' => $result['created_at']->format('m月d日'),
                    'failures' => $result['failures']->map(function($f) {
                        return [
                            'id' => $f->id,
                            'x' => $f->x,
                            'y' => $f->y,
                            'typeId' => $f->type_id,
                            'figureName' => $f->figure->name
                        ];
                    })->groupBy('figureName')
                ];
            } else {
                $results[$t['process'].'_'.$t['inspection']] = 0;
            }
        }

        return ['inspectionResults' => $results];
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

    public function delete(Request $request)
    {
        return 'aaa';
    }
}
