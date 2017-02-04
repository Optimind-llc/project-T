<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\PartResult;
// Models
use App\Models\Vehicle;
use App\Models\Process;
use App\Models\Inspection;
use App\Models\InspectionGroup;
use App\Models\Inspector;
use App\Models\InspectorGroup;
use App\Models\PageType;
use App\Models\PartType;
use App\Models\Failure;
use App\Models\Hole;
use App\Models\Inline;
use App\Models\Client\PartFamily;
use App\Models\Client\Part;
use App\Models\Client\Page;
use App\Models\Client\FailurePosition;
use App\Models\Client\HolePage;
use App\Models\Client\ModificationFailurePosition;
use App\Models\Client\HolePageHoleModification;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MappingController
 * @package App\Http\Controllers
 */
class MappingController extends Controller
{
    public function partIdMapping(Request $request)
    {
        $partId = $request->partId;
        $itionGIds = $request->itionGId;

        $part_obj = Part::find($partId);
        $partTypeId = $part_obj->part_type_id;

        $data = [];
        foreach ($itionGIds as $itionGId) {
            $page_types = PageType::where('group_id', '=', $itionGId)
                ->whereHas('partTypes', function($q) use ($partTypeId){
                    $q->where('part_types.id', '=', $partTypeId);
                })
                ->with(['figure'])
                ->get(['id', 'number', 'figure_id'])
                ->filter(function($pt) {
                    return $pt->id != 14;
                })
                ->values();

            $page_type_ids = $page_types->map(function($pt) { return $pt->id; })->toArray();


            $page_ids = Page::whereHas('parts', function($q) use ($partId){
                $q->where('parts.id', '=', $partId);
            })
            ->with(['family', 'parts'])
            ->get()
            ->filter(function($p) use ($itionGId) {
                return $p->page_type_id != 14 && $p->family->inspection_group_id == $itionGId;
            });

            if ($page_ids->count() > 0) {
                $family = $page_ids->first()->family;
                $status = $page_ids->first()
                    ->parts
                    ->filter(function($p) use ($partId) {
                        return $p->id === $partId;
                    })
                    ->map(function($p) {
                        return $p->pivot->status;
                    })
                    ->first();

                $page_ids = $page_ids->map(function($p) {
                    return $p->id;
                })
                ->toArray();

                $failures = FailurePosition::whereIn('page_id', $page_ids)
                    ->with([
                        'part' => function($q) {
                            $q->select(['id', 'parts.part_type_id']);
                        },
                        'page' => function($q) {
                            $q->select(['id', 'family_id', 'page_type_id']);
                        },
                        'page.family' => function($q) {
                            $q->select(['id', 'inspector_group']);
                        }
                    ])
                    ->get(['id', 'point', 'page_id', 'part_id', 'failure_id'])
                    ->filter(function($fp) use ($partTypeId) {
                        return $fp->part->part_type_id == $partTypeId;
                    })
                    ->map(function($fp) {
                        return [
                            'id' => $fp->failure_id,
                            'p' => $fp->point,
                            'pg' => $fp->page->page_type_id,
                            'c' => $fp->page->family->inspector_group,
                            'pt' => $fp->part->part_type_id
                        ];
                    })
                    ->values();

                $modifications = ModificationFailurePosition::whereIn('page_id', $page_ids)
                    ->with([
                        'failurePosition' => function($q) {
                            $q->select(['id', 'point']);
                        },
                        'page' => function($q) {
                            $q->select(['id', 'family_id', 'page_type_id']);
                        },
                        'page.family' => function($q) {
                            $q->select(['id', 'inspector_group']);
                        }
                    ])
                    ->get()
                    ->map(function($mf) {
                        return [
                            'id' => $mf->m_id,
                            'p' => $mf->failurePosition->point,
                            'c' => $mf->page->family->inspector_group
                        ];
                    });

                $holes = Hole::where('holes.part_type_id', '=', $partTypeId)
                    ->join('figures as f', function($join) {
                        $join->on('f.id', '=', 'holes.figure_id');
                    })
                    ->join('page_types as pt', function($join) use ($page_type_ids) {
                        $join->on('pt.figure_id', '=', 'f.id')->whereIn('pt.id', $page_type_ids);
                    })
                    ->select(['holes.*', 'pt.id as page_type_id'])
                    ->with([
                        'holePages' => function($q) use ($page_ids) {
                            $q->whereIn('page_id', $page_ids)
                                // ->where('status', '!=', 1)
                                ->select(['id', 'hole_id', 'page_id', 'status']);
                        },
                        'holePages.holeModification' => function($q){
                            $q->select(['hole_page_hole_modification.id', 'hp_id']);
                        }
                    ])
                    ->get(['id', 'point', 'holes.part_type_id', 'figure_id'])
                    ->map(function($h) {
                        return [
                            'id' => $h->id,
                            'l' => $h->label,
                            'p' => $h->point,
                            'd' => $h->direction,
                            's' => $h->holePages->map(function($hp) {
                                if ($hp->holeModification->count() > 0) {
                                    return [
                                        's' => $hp->status,
                                        'hm' => $hp->holeModification[0]->pivot->hm_id
                                    ];                            
                                }

                                return [
                                    's' => $hp->status,
                                    'hm' => null
                                ];
                            }),
                            'pg' => $h->page_type_id
                        ];
                    });

                $inlines = [];
                if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
                    $inlines = DB::table('inline_page')->where('part_id', '=', $partId)
                        ->orderBy('inspected_at', 'desc')
                        ->get();

                    $inlines = collect($inlines)->groupBy('inline_id')->map(function($i) {
                        return [$i->first()->status];
                    });
                }

                $inspectionGroup = InspectionGroup::find($itionGId);

                $failureTypes = $inspectionGroup->sortedFailures();
                $modificationTypes = $inspectionGroup->sortedModifications();
                $holeModificationTypes = $inspectionGroup->sortedHoleModifications();

                $inlinesInfo = [];
                if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
                    $inlinesInfo = Inline::where('part_type_id', '=', $partTypeId)->get();
                }

                $data[] = [
                    'count' => count($page_ids),
                    'pageTypes' => $page_types->map(function($p) {
                        return [
                            'id' => $p->id,
                            'n' => $p->number,
                            'path' => $p->figure->path
                        ];
                    }),
                    'failures' => $failures,
                    'modifications' => $modifications,
                    'holes' => $holes,
                    'inlines' => $inlines,
                    'ft' => $failureTypes,
                    'mt' => $modificationTypes,
                    'hmt' => $holeModificationTypes,
                    'i' => $inlinesInfo,
                    'family' => $family,
                    'status' => $status
                ];
            }
        }

        if (count($data) === 0) {
            return \Response::json([
                'data' => [0 => [
                    'count' => 0,
                    'page_types' => [],
                    'failures' => [],
                    'modifications' => [],
                    'holes' => [],
                    'ft' => [],
                    'mt' => [],
                    'hmt' => [],
                    'i' => []
                ]]
            ], 400);
        }

        return ['data' => $data];
    }
}