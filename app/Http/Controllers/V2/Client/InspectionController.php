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

        $partTypes = [];
        foreach ($pt_names as $pt_name) {
            $d2 = PartType::where('name', '=', $pt_name)->first()->division2;
            $pt = PartType::where('name', '=', $pt_name)
                ->with([
                    'figures' => function($q) use ($process, $inspection) {
                        if ($process === 'holing' && $inspection === 'tenaoshi') {
                            $q->where('process', '=', $process)
                                ->whereIn('inspection', ['maegaikan', 'atogaikan', 'ana', 'tenaoshi']);
                        }
                        else {
                            $q->where('process', '=', $process)
                                ->where('inspection', '=', $inspection);
                        }
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

            $partTypes[] = [
                'failures' => $this->failureType->sorted($process, $inspection, $d2),
                'modifications' => $this->modificationType->sorted($process, $inspection, $d2),
                'hModifications' => $this->holeModificationType->sorted($process, $inspection, $d2),
                'partType' => $pt
            ];
        }

        return [
            'workers' => $this->worker->formated($process, $inspection, $d1),
            'partTypes' => $partTypes
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
                $comment = array_key_exists('comment', $part) ? $part['comment'] : null;
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
                    'comment' => $comment
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

                $uhs = [];
                if (array_key_exists('updatedHoles', $part)) {
                    $uhs = $part['updatedHoles'];
                }

                $this->inspectionResult->create($param, $fs, $ms, $hs, $uhs);
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
        $parts = $request->parts;
        $inspections = $request->inspections;

        $p_results = [];
        foreach ($parts as $part) {
            $part_obj = Part::where('panel_id', $part['panelId'])->where('pn', $part['pn'])->first();

            $partId = -1;
            if ($part_obj instanceof Part) {
                $partId = $part_obj->id;
            }

            $i_results = [];
            foreach ($inspections as $i) {
                $result = $this->inspectionResult->all($i['process'], $i['inspection'], $partId);

                if ($result) {
                    $failures = (object)array();
                    if ($result['failures']->count() > 0) {
                        $failures = $result['failures']->map(function($f) {
                            return [
                                'id' => $f->id,
                                'x' => $f->x,
                                'y' => $f->y,
                                'face' => $f->face,
                                'typeId' => $f->type_id,
                                'typeLabel' => $f->type->label,
                                'figureId' => $f->figure->id,
                                'figurePage' => $f->figure->page
                            ];
                        })
                        ->groupBy('figurePage');
                    }

                    $modifications = (object)array();
                    if ($result['modifications']->count() > 0) {
                        $modifications = $result['modifications']->map(function($m) {
                            return [
                                'id' => $m->id,
                                'failureId' => $m->failure->id,
                                'x' => $m->failure->x,
                                'y' => $m->failure->y,
                                'face' => $m->failure->face,
                                'typeId' => $m->type_id,
                                'typeLabel' => $m->type->label,
                                'figureId' => $m->figure->id,
                                'figurePage' => $m->figure->page
                            ];
                        })
                        ->groupBy('figurePage');
                    }

                    $i_results[$i['process'].'_'.$i['inspection']] = [
                        'id' => $result['id'],
                        'line' => $result['line'],
                        'status' => $result['status'],
                        'comment' => $result['comment'] !== null ? $result['comment'] : '',
                        'choku' => $result['created_choku'],
                        'createdBy' => $result['created_by'],
                        'createdAt' => $result['created_at']->format('m月d日'),
                        'failures' => $failures,
                        'modifications' => $modifications,
                        'holes' => $result['holes']->map(function($h) {
                            $holeModificationType = 0;
                            $holeModificationLabel = 0;
                            if ($h->holeModification) {
                                $holeModificationType = $h->holeModification->type_id;
                                $holeModificationTypeLabel = $h->holeModification->type->label;
                            }
                            return [
                                'id' => $h->id,
                                'typeId' => $h->type_id,
                                'status' => $h->status,
                                'holeModificationType' => $holeModificationType,
                                'holeModificationTypeLabel' => $holeModificationTypeLabel
                            ];
                        }),
                    ];
                } else {
                    $i_results[$i['process'].'_'.$i['inspection']] = ['status' => -1];
                }
            }

            $p_results[] = [
                'pn' => $part['pn'],
                'panelId' => $part['panelId'],
                'partId' => $partId,
                'inspections' => $i_results
            ];
        }

        return [
            'results' => $p_results
        ];
    }

    public function resultWithChildren($vehicle, Request $request)
    {
        $pn = $request->pn;
        $panelId = $request->panelId;
        $iSelf = $request->inspections['self'];
        $iChildren = $request->inspections['children'];

        // Find the part by PN and panelId
        $targetPart = Part::where('panel_id', '=', $panelId)
            ->where('pn', '=', $pn)
            ->first();

        if (is_null($targetPart)) {
            $targetPart = new Part;
            $targetPart->panel_id = $panelId;
            $targetPart->pn = $pn;
            $targetPart->save();
        }

        $iSelf_results = [];
        foreach ($iSelf as $is) {
            $result = $this->inspectionResult->all($is['process'], $is['inspection'], $targetPart->id);

            if ($result) {
                $failures = (object)array();
                if ($result['failures']->count() > 0) {
                    $failures = $result['failures']->map(function($f) {
                        return [
                            'id' => $f->id,
                            'x' => $f->x,
                            'y' => $f->y,
                            'face' => $f->face,
                            'typeId' => $f->type_id,
                            'typeLabel' => $f->type->label,
                            'figureId' => $f->figure->id,
                            'figurePage' => $f->figure->page
                        ];
                    })
                    ->groupBy('figurePage');
                }

                $modifications = (object)array();
                if ($result['modifications']->count() > 0) {
                    $modifications = $result['modifications']->map(function($m) {
                        return [
                            'id' => $m->id,
                            'failureId' => $m->failure->id,
                            'x' => $m->failure->x,
                            'y' => $m->failure->y,
                            'face' => $m->failure->face,
                            'typeId' => $m->type_id,
                            'typeLabel' => $m->type->label,
                            'figureId' => $m->figure->id,
                            'figurePage' => $m->figure->page
                        ];
                    })
                    ->groupBy('figurePage');
                }

                $iSelf_results[$is['process'].'_'.$is['inspection']] = [
                    'id' => $result['id'],
                    'line' => $result['line'],
                    'status' => $result['status'],
                    'comment' => $result['comment'] !== null ? $result['comment'] : '',
                    'choku' => $result['created_choku'],
                    'createdBy' => $result['created_by'],
                    'createdAt' => $result['created_at']->format('m月d日'),
                    'failures' => $failures,
                    'modifications' => $modifications,
                    'holes' => $result['holes']->map(function($h) {
                        $holeModificationType = 0;
                        $holeModificationLabel = 0;
                        if ($h->holeModification) {
                            $holeModificationType = $h->holeModification->type_id;
                            $holeModificationTypeLabel = $h->holeModification->type->label;
                        }
                        return [
                            'id' => $h->id,
                            'typeId' => $h->type_id,
                            'status' => $h->status,
                            'holeModificationType' => $holeModificationType,
                            'holeModificationTypeLabel' => $holeModificationTypeLabel
                        ];
                    }),
                ];
            }
            else {
                $iSelf_results[$is['process'].'_'.$is['inspection']] = ['status' => -1];
            }
        }

        $associated = true;
        if (is_null($targetPart->family)) {
            $associated = false;
            
            switch ($pn) {
                case 6701511020: $childPn1 = 6714111020; $childPn2 = 6715111020; break;
                case 6701611020: $childPn1 = 6714211020; $childPn2 = 6715211020; break;
                case 6440111010: $childPn1 = 6441211010; $childPn2 = 6441111010; break;
                case 6440111020: $childPn1 = 6441211020; $childPn2 = 6441111020; break;
            }

            $childPart = Part::where('panel_id', '=', $panelId)
                ->where('pn', '=', $childPn1)
                ->first();

            $childPartId = is_null($childPart) ? -1 : $childPart->id;

            $childrenParts = [
                [
                    'id' => $childPartId,
                    'pn' => $childPn1,
                    'panelId' => $panelId
                ],[
                    'id' => -1,
                    'pn' => $childPn2,
                    'panelId' => -1
                ]
            ];
        }
        else {
            $childrenParts = $targetPart->family->parts->filter(function($p) use($pn) {
                return $p->pn !== $pn;
            })->map(function($p) {
                return [
                    'id' => $p->id,
                    'pn' => $p->pn,
                    'panelId' => $p->panel_id
                ];
            });
        }


        $iChildren_results = [];
        foreach ($childrenParts as $cp) {
            $iChildren_inspections = [];
            foreach ($iChildren as $ic) {
                $result = $this->inspectionResult->simple($ic['process'], $ic['inspection'], $cp['id']);

                if ($result) {
                    $iChildren_inspections[$ic['process'].'_'.$ic['inspection']] = [
                        'id' => $result['id'],
                        'line' => $result['line'],
                        'status' => $result['status'],
                        'failures' => $result['failures']->count(),
                        'defectiveHoles' => $result['holes']->count()
                    ];
                }
                else {
                    $iChildren_inspections[$ic['process'].'_'.$ic['inspection']] = ['status' => -1];
                }
            }

            $iChildren_results[] = [
                'pn' => $cp['pn'],
                'panelId' => $cp['panelId'],
                'inspections' => $iChildren_inspections
            ];
        }

        return [
            'associated' => $associated,
            'self' => $iSelf_results,
            'children' => $iChildren_results,
            'partId' => $targetPart->id
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
                $comment = array_key_exists('comment', $part) ? $part['comment'] : null;
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
                    'comment' => $comment
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

                $dmbfs = [];
                if (array_key_exists('deletedMByF', $part)) {
                    $dmbfs = $part['deletedMByF'];
                }

                $this->inspectionResult->update($param, $fs, $ms, $hs, $dfs, $dms, $dmbfs);

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
