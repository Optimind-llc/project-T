<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Vehicle950A\Choku;
// Models
use App\Models\Vehicle950A\InspectionResult;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\HoleTypeRepository;
use App\Repositories\InlineTypeRepository;
// Services
use App\Services\Vehicle950A\GeneratePDF;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReferenceController
 * @package App\Http\Controllers\V2\Manager
 */
class ReferenceController extends Controller
{
    protected $inspectionResult;
    protected $failureType;
    protected $modificationType;
    protected $holeModificationType;
    protected $holeType;
    protected $inlineType;

    public function __construct (
        InspectionResultRepository $inspectionResult,
        FailureTypeRepository $failureType,
        ModificationTypeRepository $modificationType,
        HoleModificationTypeRepository $holeModificationType,
        HoleTypeRepository $holeType,
        InlineTypeRepository $inlineType
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->failureType = $failureType;
        $this->modificationType = $modificationType;
        $this->holeModificationType = $holeModificationType;
        $this->holeType = $holeType;
        $this->inlineType = $inlineType;
    }

    public function advanced($vehicle, Request $request)
    {
        $pn = $request->pn;
        $start = Carbon::createFromFormat('Y-m-d-H-i-s', $request->start.'-00-00-00')->addHours(2);
        $end = Carbon::createFromFormat('Y-m-d-H-i-s', $request->end.'-00-00-00')->addHours(26);
        $ftIds = $request->fs;
        $mtIds = $request->ms;

        $irs = InspectionResult::where('latest', '=', 1)
            ->where('process', '=', $request->p)
            ->where('inspection', '=', $request->i)
            ->whereHas('part', function($q) use($pn) {
                $q->where('pn', '=', $pn);
            })
            ->whereIn('status', $request->status)
            ->whereIn('created_choku', $request->chokus)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->with([
                'part' => function($q) {
                    return $q->select('id', 'pn', 'panel_id');
                },
                'part.partType' => function($q) {
                    return $q->select('pn', 'name');
                },
                'failures' => function($q) {
                    return $q->select('ir_id', 'type_id', 'id');
                },
                'modifications' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'holes' => function($q) {
                    return $q->select('id', 'ir_id', 'type_id', 'status');
                },
                'holeModifications' => function($q) {
                    return $q->select('ir_id', 'type_id', 'hole_id');
                },
                'inlines' => function($q) {
                    return $q->select('id', 'ir_id', 'type_id', 'status');
                }
            ]);

        $count = $irs->count();

        $irs = $irs->take($request->take)->skip($request->skip)->orderBy('id');

            if (count($ftIds) > 0) {
                $irs = $irs->whereHas('failures', function($q) use($ftIds) {
                    $q->whereIn('type_id', $ftIds);
                });
            }

            if (count($mtIds) > 0) {
                $irs = $irs->whereHas('modifications', function($q) use($mtIds) {
                    $q->whereIn('type_id', $mtIds);
                });
            }

            $irs = $irs->get()->map(function($ir) use($vehicle) {
                return [
                    'id' => $ir->id,
                    'v' => $vehicle,
                    'pn' => $ir->part->pn,
                    'name' => $ir->part->partType->name,
                    'panel_id' => $ir->part->panel_id,
                    'choku' => $ir->created_choku,
                    'cBy' => $ir->created_by,
                    'uBy' => $ir->updated_by,
                    'status' => $ir->status,
                    'comment' => $ir->comment,
                    'iAt' => $ir->inspected_at->toDateTimeString(),
                    'uAt' => $ir->updated_at->toDateTimeString(),
                    'ft_ids' => unserialize($ir->ft_ids),
                    'mt_ids' => unserialize($ir->mt_ids),
                    'hmt_ids' => unserialize($ir->hmt_ids),
                    'ht_ids' => $ir->holes->map(function($h) {
                        return $h->type_id;
                    }),
                    'it_ids' => $ir->inlines->map(function($i) {
                        return $i->type_id;
                    }),
                    'fs' => array_count_values($ir->failures->map(function($f) {
                        return $f->type_id;
                    })->toArray()),
                    'ms' => array_count_values($ir->modifications->map(function($m) {
                        return $m->type_id;
                    })->toArray()),
                    'hms' => array_count_values($ir->holeModifications->map(function($hm) {
                        return $hm->type_id;
                    })->toArray()),
                    'hs' => $ir->holes->keyBy('type_id')->map(function($h) {
                        return $h->status;
                    }),
                    'is' => $ir->inlines->keyBy('type_id')->map(function($i) {
                        return $i->status;
                    })
                ];
            });

        $ft_ids = $irs->map(function($ir){
            return $ir['ft_ids'];
        })->flatten()->unique();
        $failureTypes = $this->failureType->getByIds($ft_ids);

        $mt_ids = $irs->map(function($ir){
            return $ir['mt_ids'];
        })->flatten()->unique();
        $modificationTypes = $this->modificationType->getByIds($mt_ids);

        $hmt_ids = $irs->map(function($ir){
            return $ir['hmt_ids'];
        })->flatten()->unique();
        $holeModificationTypes = $this->holeModificationType->getByIds($hmt_ids);

        $ht_ids = $irs->map(function($ir){
            return $ir['ht_ids'];
        })->flatten()->unique();
        $holeTypes = $this->holeType->getByIds($ht_ids);

        $it_ids = $irs->map(function($ir){
            return $ir['it_ids'];
        })->flatten()->unique();
        $inlineTypes = $this->inlineType->getByIds($it_ids);

        return [
            'data' => [
                'count' => $count,
                'results' => $irs,
                'fts' => $failureTypes,
                'mts' => $modificationTypes,
                'hmts' => $holeModificationTypes,
                'hts' => $holeTypes,
                'its' => $inlineTypes               
            ],
            'status' => 1,
            'message' => 'success'
        ];
    }

    public function byPanelId($vehicle, Request $request)
    {
        $pn = $request->pn;
        $panelId = $request->panelId;

        $irs = InspectionResult::with([
                'part' => function($q) {
                    return $q->select('id', 'pn', 'panel_id');
                },
                'part.partType' => function($q) {
                    return $q->select('pn', 'name');
                },
                'failures' => function($q) {
                    return $q->select('ir_id', 'type_id', 'id');
                },
                'modifications' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'holes' => function($q) {
                    return $q->select('id', 'ir_id', 'type_id', 'status');
                },
                'holeModifications' => function($q) {
                    return $q->select('ir_id', 'type_id', 'hole_id');
                },
                'inlines' => function($q) {
                    return $q->select('id', 'ir_id', 'type_id', 'status');
                }
            ])
            ->where('latest', '=', 1)
            ->where('process', '=', $request->p)
            ->where('inspection', '=', $request->i)
            ->whereHas('part', function($q) use($pn, $panelId) {
                $q->where('pn', '=', $pn)->where('panel_id', 'like', $panelId.'%');
            })
            ->get()
            ->map(function($ir) use($vehicle) {
                return [
                    'v' => $vehicle,
                    'pn' => $ir->part->pn,
                    'name' => $ir->part->partType->name,
                    'panel_id' => $ir->part->panel_id,
                    'choku' => $ir->created_choku,
                    'cBy' => $ir->created_by,
                    'uBy' => $ir->updated_by,
                    'status' => $ir->status,
                    'comment' => $ir->comment,
                    'iAt' => $ir->inspected_at->toDateTimeString(),
                    'uAt' => $ir->updated_at->toDateTimeString(),
                    'ft_ids' => unserialize($ir->ft_ids),
                    'mt_ids' => unserialize($ir->mt_ids),
                    'hmt_ids' => unserialize($ir->hmt_ids),
                    'ht_ids' => $ir->holes->map(function($h) {
                        return $h->type_id;
                    }),
                    'it_ids' => $ir->inlines->map(function($i) {
                        return $i->type_id;
                    }),
                    'fs' => array_count_values($ir->failures->map(function($f) {
                        return $f->type_id;
                    })->toArray()),
                    'ms' => array_count_values($ir->modifications->map(function($m) {
                        return $m->type_id;
                    })->toArray()),
                    'hms' => array_count_values($ir->holeModifications->map(function($hm) {
                        return $hm->type_id;
                    })->toArray()),
                    'hs' => $ir->holes->keyBy('type_id')->map(function($h) {
                        return $h->status;
                    }),
                    'is' => $ir->inlines->keyBy('type_id')->map(function($i) {
                        return $i->status;
                    })
                ];
            });

        $ft_ids = $irs->map(function($ir){
            return $ir['ft_ids'];
        })->flatten()->unique();
        $failureTypes = $this->failureType->getByIds($ft_ids);

        $mt_ids = $irs->map(function($ir){
            return $ir['mt_ids'];
        })->flatten()->unique();
        $modificationTypes = $this->modificationType->getByIds($mt_ids);

        $hmt_ids = $irs->map(function($ir){
            return $ir['hmt_ids'];
        })->flatten()->unique();
        $holeModificationTypes = $this->holeModificationType->getByIds($hmt_ids);

        $ht_ids = $irs->map(function($ir){
            return $ir['ht_ids'];
        })->flatten()->unique();
        $holeTypes = $this->holeType->getByIds($ht_ids);

        $it_ids = $irs->map(function($ir){
            return $ir['it_ids'];
        })->flatten()->unique();
        $inlineTypes = $this->inlineType->getByIds($it_ids);

        return [
            'data' => [
                'count' => $irs->count(),
                'results' => $irs,
                'fts' => $failureTypes,
                'mts' => $modificationTypes,
                'hmts' => $holeModificationTypes,
                'hts' => $holeTypes,
                'its' => $inlineTypes              
            ],
            'status' => 1,
            'message' => 'success'
        ];
    }
}
