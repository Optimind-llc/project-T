<?php

namespace App\Http\Controllers\V2\Client;

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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
 * Class InspectionController
 * @package App\Http\Controllers\V2\Client
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

    public function getInspection($vehicle, Request $request)
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
                            'name' => $f->process.'_'.$f->inspection.'_'.$f->pt_pn.'_p'.$f->page,
                            'page' => $f->page,
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

    public function saveInspection($vehicle, Request $request)
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
            }
            else {
                $param = [
                    'part_id' => $targetPart->id,
                    'process' => $process,
                    'inspection' => $inspection,
                    'line' => $line,
                    'ft_ids' => $this->failureType->narrowedIds($process, $inspection, $d2),
                    'mt_ids' => $this->modificationType->narrowedIds($process, $inspection, $d2),
                    'hmt_ids' => $this->holeModificationType->narrowedIds($process, $inspection, $d2),
                    'created_choku' => $choku,
                    'created_by' => $worker,
                    'status' => $part['status'],
                    'comment' => $part['comment']
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

                $this->inspectionResult->create($param, $fs, $ms, $hs);
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

    public function result($vehicle, Request $request)
    {
        $pn = $request->pn;
        $panelId = $request->panelId;
        $targets = $request->targets;

        $part = Part::where('panel_id', $panelId)->where('pn', $pn)->first();

        $partId = -1;
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
                            'figureId' => $f->figure->id,
                            'figurePage' => $f->figure->page
                        ];
                    })->groupBy('figurePage'),
                    'modifications' => $result['modifications']->map(function($m) {
                        return [
                            'id' => $m->id,
                            'x' => $m->failure->x,
                            'y' => $m->failure->y,
                            'typeId' => $m->type_id,
                            'figureId' => $m->figure->id,
                            'figurePage' => $m->figure->page
                        ];
                    })->groupBy('figurePage'),
                    'holes' => $result['holes']->map(function($h) {
                        return [
                            'id' => $h->id,
                            'typeId' => $h->type_id,
                            'status' => $h->status,
                            'holeModificationType' => $h->holeModification->type_id
                        ];
                    }),
                ];
            } else {
                $results[$t['process'].'_'.$t['inspection']] = ['status' => -1];
            }
        }

        return [
            'partId' => $partId,
            'inspectionResults' => $results
        ];
    }

    public function update($vehicle, Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $choku = $request->choku;
        $worker = $request->worker;
        $parts = $request->parts;

        $notFound = [];
        DB::connection('950A')->beginTransaction();
        foreach ($parts as $part) {
            $targetPart = Part::with(['partType'])->find($part['partId']);
            $d2 = $targetPart->partType->division2;

            // Check inspection result exist
            if ($this->inspectionResult->exist($process, $inspection, $part['partId'])) {
                $param = [
                    'part_id' => $targetPart->id,
                    'process' => $process,
                    'inspection' => $inspection,
                    'ft_ids' => $this->failureType->narrowedIds($process, $inspection, $d2),
                    'mt_ids' => $this->modificationType->narrowedIds($process, $inspection, $d2),
                    'hmt_ids' => $this->holeModificationType->narrowedIds($process, $inspection, $d2),
                    'updated_choku' => $choku,
                    'updated_by' => $worker,
                    'status' => $part['status'],
                    'comment' => $part['comment']
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

                $dfs = [];
                if (array_key_exists('deletedF', $part)) {
                    $dfs = $part['deletedF'];
                }

                $dms = [];
                if (array_key_exists('deletedM', $part)) {
                    $dms = $part['deletedM'];
                }

                $this->inspectionResult->update($param, $fs, $ms, $hs, $dfs, $dms);

            }
            else {
                $notFound[] = [
                    'panelId' => $part['panelId'],
                    'name' => $targetPart->partType->name,
                    'pn' => $targetPart->partType->pn
                ];
            }
        }

        if (count($notFound) === 0) {
            DB::connection('950A')->commit();
            return [
                'message' => 'Update inspection succeeded'
            ];
        } else {
            DB::connection('950A')->rollBack();
            return \Response::json([
                'message' => 'Inspection result not found',
                'notFound' => $notFound
            ], 400);
        }
    }

    public function delete($vehicle, Request $request)
    {
        $process = $request->process;
        $inspection = $request->inspection;
        $partIds = $request->partIds;

        foreach ($partIds as $partId) {
            $this->inspectionResult->delete($process, $inspection, $partId);
        }

        return [
            'message' => 'Delete inspection succeeded'
        ];
    }
}
