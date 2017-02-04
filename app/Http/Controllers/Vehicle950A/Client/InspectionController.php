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
use App\Models\Vehicle950A\Worker;
use App\Models\Vehicle950A\PartType;
use App\Models\Vehicle950A\Part;
use App\Models\Vehicle950A\FailureType;
use App\Models\Vehicle950A\Failure;
use App\Models\Vehicle950A\ModificationType;
use App\Models\Vehicle950A\Modification;
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
    public function getInspection(Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $pt_names = $request->partNames;

        $first_pt = PartType::where('name', '=', $pt_names[0])->first();
        $division1 = $first_pt->division1;
        $division2 = $first_pt->division2;

        $pt = PartType::whereIn('name', $pt_names)
            ->with([
                'figures' => function($q) use ($process, $inspection){
                    $q->where('process', '=', $process)->where('inspection', '=', $inspection);
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
                            'name' => $f->name,
                            'path' => '/img/figures/950A/'.$f->path,
                            'sizeX' => $f->size_x,
                            'sizeY' => $f->size_y,
                            'holes' => []
                        ];
                    })
                    
                ];
            });

        // $worker = new Worker;
        $failureType = new FailureType;
        $modificationType = new ModificationType;

        $workers = Worker::formated($process, $inspection, $division1)->get()->formated();
        return [
            // 'workers' => $worker->formatedWorkers($process, $inspection, $division1)->toArray(),
            'workers' => $workers,
            'failures' => $failureType->sortedFailureTypes($process, $inspection, $division2)->toArray(),
            'modifications' => $modificationType->sortedModificationTypes($process, $inspection, $division2)->toArray(),
            'hModifications' => [],
            'partTypes' => $pt,
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
        $now = Carbon::now();
        $process = $request->process;
        $inspection = $request->inspection;
        $line = $request->line;
        $choku = $request->choku;
        $worker = $request->worker;
        $parts = $request->parts;

        // Check duplicate
        foreach ($parts as $part) {
            $targetPart = Part::where('pn', '=', $part['pn'])
                ->where('panel_id', '=', $part['panelId'])
                ->first();

            if ($targetPart instanceof Part) {
                $ir = InspectionResult::where('process', '=', $process)
                    ->where('inspection', '=', $inspection)
                    ->where('part_id', '=', $targetPart->id)
                    ->first();

                if ($ir instanceof InspectionResult) {
                    return \Response::json([
                        'message' => $part['panelId'].' already be inspected',
                        'panelId' => $part['panelId'],
                        'name' => $targetPart->partType->name,
                        'pn' => $targetPart->partType->pn
                    ], 400);
                }
            }
        }

        // Save inspection
        foreach ($parts as $part) {
            $targetPart = Part::where('pn', '=', $part['pn'])
                ->where('panel_id', '=', $part['panelId'])
                ->first();

            if (!$targetPart instanceof Part) {
                $targetPart = new Part;
                $targetPart->panel_id = $part['panelId'];
                $targetPart->pn = $part['pn'];
                $targetPart->save();
            }

            $newResult = new InspectionResult;
            $newResult->part_id = $targetPart->id;
            $newResult->process = $process;
            $newResult->inspection = $inspection;
            $newResult->line = $line;
            $newResult->ft_ids = '';
            $newResult->created_choku = $choku;
            $newResult->created_by = $worker;
            $newResult->status = $part['status'];
            $newResult->comment = $part['comment'];
            $newResult->inspected_at = $now;
            $newResult->created_at = $now;
            $newResult->updated_at = $now;
            $newResult->save();

            // Create failure
            if (array_key_exists('failures', $part) && count($part['failures']) !== 0) {
                foreach ($part['failures'] as $f) {
                    $new_f = new Failure;
                    $new_f->ir_id = $newResult->id;
                    $new_f->part_id = $targetPart->id;
                    $new_f->figure_id = $f['figureId'];
                    $new_f->x = $f['x'];
                    $new_f->y = $f['y'];
                    $new_f->type_id = $f['failureTypeId'];
                    $new_f->save();

                    if (array_key_exists('modificationTypeId', $f) && $f['modificationTypeId'] !== null && $f['modificationTypeId'] !== 0 ) {
                        $new_m = new Modification;
                        $new_m->ir_id = $newResult->id;
                        $new_m->part_id = $targetPart->id;
                        $new_m->figure_id = $f['figureId'];
                        $new_m->type_id = $f['modificationTypeId'];
                        $new_m->failure_id = $new_f->id;
                        $new_m->save();
                    }
                }
            }
        }

        return [
            'message' => 'Save inspection succeeded'
        ];
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
