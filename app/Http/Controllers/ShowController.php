<?php

namespace App\Http\Controllers;

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
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * Class ShowController
 * @package App\Http\Controllers
 */
class ShowController extends Controller
{
    protected function findInspection($process_id, $inspection_en)
    {
        $inspection = Inspection::where('process_id', $process_id)
            ->where('en', $inspection_en)
            ->first();

        if (!$inspection instanceof Inspection) {
            throw new NotFoundHttpException('Inspection not found');
        }

        return $inspection;
    }

    protected function findInspectionGroup($vehicle, $process_id, $inspection_en, $division_en, $line = null)
    {
        $group = $this->findInspection($process_id, $inspection_en)
            ->getGroupByVehicleDivisionLine($vehicle, $division_en, $line);

        if (!$group instanceof InspectionGroup) {
            throw new NotFoundHttpException('Inspection group not found');
        }

        return $group;
    }

    protected function processData($option)
    {
        switch ($option) {
            case 'all':
                $processes = Process::select(['id', 'name as n', 'sort as s'])
                    ->orderBy('sort')
                    ->get();
                break;

            case 'withInspection':
                $processes = Process::with([
                    'inspections' => function($q) {
                        $q->select('id', 'name', 'en', 'sort', 'process_id')
                            ->orderBy('sort')
                            ->get();
                    }])
                    ->select(['id', 'name as n', 'sort as s'])
                    ->orderBy('sort')
                    ->get();
                break;

            default:
                throw new NotFoundHttpException('Invalid parameter');
                break;
        }

        return $processes;
    }

    protected function vehicleData($option)
    {
        switch ($option) {
            case 'all':
                $vehicles = Vehicle::select(['number as c', 'name as n'])->get();
                break;

            case 'withParts':
                $vehicles = Vehicle::with([
                    'partTypes' => function($q) {
                        $q->select('id', 'name', 'pn', 'vehicle_num')->get();
                    }])
                    ->select(['number', 'name'])
                    ->get()
                    ->map(function($v) {
                        return [
                            'name' => $v->name,
                            'number' => $v->number,
                            'parts' => $v->partTypes
                        ];
                    });
                break;

            default:
                throw new NotFoundHttpException('Invalid parameter');
                break;
        }

        return $vehicles;
    }

    protected function inspectorGData($option)
    {
        switch ($option) {
            case 'all':
                $vehicles = InspectorGroup::where('status', 1)->select(['code as c', 'name as n'])->get();
                break;

            case 'withInspectors':
                $vehicles = InspectorGroup::with([
                    'inspectors' => function($q) {
                        $query->select(['id', 'name', 'pn']);
                    }])
                    ->select(['code', 'name'])
                    ->get();
                break;

            default:
                throw new NotFoundHttpException('Invalid parameter');
                break;
        }

        return $vehicles;
    }

    protected function inspectorData($option)
    {
        switch ($option) {
            case 'all':
                $inspectors = Inspector::with(['groups'])
                    ->get(['group_code', 'name'])
                    ->map(function($i) {
                        return [
                            'name' => $i->name,
                            'chokuName' => $i->groups->name,
                            'chokuCode' => $i->groups->code
                        ];
                    });
                break;

            case 'withInspectors':
                $inspectors = Inspector::with([
                    'inspectors' => function($q) {
                        $query->select(['id', 'name', 'pn']);
                    }])
                    ->select(['code', 'name'])
                    ->get();
                break;

            default:
                throw new NotFoundHttpException('Invalid parameter');
                break;
        }

        return $inspectors;
    }

    protected function failureData($option)
    {
        switch ($option) {
            case 'all':
                $inspectors = Failure::with(['inspections'])
                    ->get()
                    ->map(function($f) {
                        return [
                            'name' => $f->name,
                            'label' => $f->label,
                            'inspections' => $f->inspections
                        ];
                    });
                break;

            case 'withInspectors':
                $inspectors = Inspector::with([
                    'inspectors' => function($q) {
                        $query->select(['id', 'name', 'pn']);
                    }])
                    ->select(['code', 'name'])
                    ->get();
                break;

            default:
                throw new NotFoundHttpException('Invalid parameter');
                break;
        }

        return $inspectors;
    }

    protected function holeData($option)
    {
        switch ($option) {
            case 'all':
                $holes = Hole::with(['figure'])
                    ->get()
                    ->map(function($h) {
                        return [
                            'label' => $h->label,
                            'point' => $h->point,
                            'color' => $h->color,
                            'shape' => $h->shape,
                            'border' => $h->border,
                            'direction' => $h->direction,
                            'figure' => $h->figure->path
                        ];
                    });
                break;

            default:
                throw new NotFoundHttpException('Invalid parameter');
                break;
        }

        return $holes;
    }

    public function tableData(Request $request)
    {
        $data = [];

        if ($request->process)
            $data['process'] = $this->processData($request->process);

        if ($request->vehicle)
            $data['vehicle'] = $this->vehicleData($request->vehicle);

        if ($request->inspectorG)
            $data['inspectorG'] = $this->inspectorGData($request->inspectorG);

        if ($request->inspector)
            $data['inspector'] = $this->inspectorData($request->inspector);

        if ($request->failure)
            $data['failure'] = $this->failureData($request->failure);

        if ($request->hole)
            $data['hole'] = $this->holeData($request->hole);

        return ['data' => $data];
    }

    public function inspectionGroup(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'vehicle' => ['required', 'alpha_dash'],
                'date' => ['required', 'date'],
                'inspectorG' => ['required', 'alpha'],
                'process' => ['required', 'alpha']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $vehicle = $request->vehicle;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date.' 00:00:00');
        $inspectorG = InspectorGroup::find($request->inspectorG)->name;
        $process = $request->process;

        $inspections = Inspection::with([
                'groups' => function ($q) use ($vehicle, $date, $inspectorG) {
                    $q->with([
                        'families' => function ($q) use ($date, $inspectorG) {
                            $q->where('created_at', '>=', $date->addHours(1))
                                ->where('created_at', '<', $date->copy()->addDay(1))
                                ->where('inspector_group', $inspectorG)
                                ->select(['id', 'inspection_group_id']);
                        }
                    ])
                    ->where('vehicle_num', $vehicle);
                },
                'groups.division' => function ($q) {
                    $q->select(['en', 'name']);
                }
            ])
            ->where('process_id', $process)
            ->orderBy('sort')
            ->get()
            ->map(function($i) {
                return [
                    'id' => $i->id,
                    'name' => $i->name,
                    'sort' => $i->sort,
                    'process' => $i->process_id,
                    'groups' => $i->groups
                ];
            });

        return ['data' => $inspections];
    }

    public function allInspectionGroupNow(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'vehicle' => ['required', 'alpha_dash']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $vehicle = $request->vehicle;
        $now = Carbon::now();

        $today = Carbon::today();
        $t2_end_at = $today->copy()->addHours(1);
        $t1_start_at = $today->copy()->addHours(6);
        $t1_end_at = $today->copy()->addHours(15)->addMinutes(30);
        $t2_start_at = $today->copy()->addHours(16)->addMinutes(30);

        if ($now->lt($t2_end_at)) {
            $from = $t1_start_at->copy()->subDay();

        } elseif ($now->lt($t1_end_at)) {
            $from = $t2_start_at->copy()->subDay();

        } else {
            $from = $t1_start_at;
        }

        $processes = Process::with([
                'inspections' => function($q) {
                    $q->whereNotIn('en', ['inline'])
                        ->orderBy('sort')
                        ->select(['id', 'name', 'process_id']);
                },
                'inspections.groups' => function($q) use ($vehicle, $from) {
                    $q->join('inspection_families as f', function ($join) use ($from) {
                            $join->on('inspection_groups.id', '=', 'f.inspection_group_id')
                                ->where('f.created_at', '>=', $from);
                        })
                        ->with(['division' => function ($q) {
                            $q->select(['en', 'name']);
                        }])
                        ->select(DB::raw('inspection_groups.id, inspection_groups.division_en, inspection_groups.line, inspection_groups.inspection_id, COUNT(f.id) AS count_f'))
                        ->where('vehicle_num', $vehicle)
                        ->groupBy('inspection_groups.id');
                }
            ])
            ->orderBy('sort')
            ->select(['id', 'name'])
            ->get();

        return ['data' => $processes];
    }

    public function pageType($vehicle, $process, $inspection, $division, $line = null)
    {
        $page_types = $this->findInspectionGroup($vehicle, $process, $inspection, $division, $line)
            ->pageTypes()
            ->with([
                'figure' => function ($q) {
                    $q->select(['id', 'path']);
                },
                'partTypes' => function ($q) {
                    $q->select(['id', 'pn', 'name']);
                }
            ])
            ->select(['id', 'number', 'figure_id'])
            ->get()
            ->map(function($page) {
                return [
                    'id' => $page->id,
                    'number' => $page->number,
                    'path' => '/img/figures/'.$page->figure->path,
                    'parts' => $page->partTypes->map(function($part) {
                        return [
                            'pn' => $part->pn,
                            'name' => $part->name
                        ];
                    })
                ];
            });

        return ['data' => $page_types];
    }

    public function panelIdMapping($partTypeId, $itionGId, $itorG, $panelId)
    {
        switch ($itorG) {
            case 'W': $tyoku = ['白直', '不明']; break;
            case 'Y': $tyoku = ['黄直', '不明']; break;
            case 'B': $tyoku = ['黒直', '不明']; break;
            case 'both': $tyoku = ['白直', '黄直', '黒直', '不明']; break;
        }

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

        $page_type_ids = $page_types->map(function($pt) {
            return $pt->id;
        })
        ->toArray();

        $page_ids = Page::whereHas('parts', function($q) use ($panelId){
            $q->where('parts.panel_id', '=', $panelId);
        })
        ->with(['family'])
        ->get()
        ->filter(function($p) use ($itionGId) {
            return $p->page_type_id != 14 && $p->family->inspection_group_id == $itionGId;
        })
        ->map(function($p) {
            return $p->id;
        })
        ->toArray();

        if (count($page_ids) == 0) {
            return [
                'data' => [
                    'count' => 0,
                    'page_types' => [],
                    'failures' => [],
                    'modifications' => [],
                    'holes' => [],
                    'ft' => [],
                    'mt' => [],
                    'hmt' => [],
                    'i' => []
                ]
            ];
        }

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
            $part = Part::where('panel_id', '=', $panelId)
                ->where('part_type_id', '=', $partTypeId)
                ->first();

            if ($part) {
                $inlines = DB::table('inline_page')->where('part_id', '=', $part->id)
                    ->where('part_id', '=', $part->id)
                    ->orderBy('inspected_at', 'desc')
                    ->get();

                $inlines = collect($inlines)->groupBy('inline_id')->map(function($i) {
                    return [$i->first()->status];
                });
            }                
        }

        $inspectionGroup = InspectionGroup::find($itionGId);

        $failureTypes = $inspectionGroup->sortedFailures();
        $modificationTypes = $inspectionGroup->sortedModifications();
        $holeModificationTypes = $inspectionGroup->sortedHoleModifications();

        $inlinesInfo = [];
        if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
            $inlinesInfo = Inline::where('part_type_id', '=', $partTypeId)->get();
        }

        return [
            'data' => [
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
                'i' => $inlinesInfo
            ]
        ];
    }

    public function advancedMapping($partTypeId, $itionGId, $itorG, Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'start' => ['alpha_dash'],
                'end' => ['alpha_dash']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $now = Carbon::now();

        if (isset($request->start) && isset($request->end)) {
            $start_at = Carbon::createFromFormat('Y-m-d-H-i-s', $request->start.'-00-00-00')->addHours(2);
            $end_at = Carbon::createFromFormat('Y-m-d-H-i-s', $request->end.'-00-00-00')->addHours(26);
        }
        else {
            $today = Carbon::today();

            $t3_end_at = $today->copy()->addHours(6)->addMinutes(30);
            $t1_start_at = $today->copy()->addHours(6)->addMinutes(30);
            $t1_end_at = $today->copy()->addHours(14)->addMinutes(00);
            $t2_start_at = $today->copy()->addHours(14)->addMinutes(00);
            $t2_end_at = $today->copy()->addHours(22)->addMinutes(15);
            $t3_start_at = $today->copy()->addHours(22)->addMinutes(15);

            if ($now->lt($t3_end_at)) {
                if ($now->dayOfWeek === 1) {
                    $start_at = $t2_start_at->copy()->subDays(3);
                }
                else {
                    $start_at = $t2_start_at->copy()->subDay();
                }
            }
            elseif ($now->lt($t1_end_at)) {
                $start_at = $t3_start_at->copy()->subDay();
            }
            elseif ($now->lt($t2_end_at)) {
                $start_at = $t1_start_at->copy();
            }
            else {
                if ($now->dayOfWeek === 1) {
                    $start_at = $t2_start_at->copy()->subDays(3);
                }
                else {
                    $start_at = $t2_start_at->copy()->subDay();
                }
            }

            $end_at = $now;
        }

        switch ($request->itorG) {
            case 'W': $tyoku = ['白直', '不明']; break;
            case 'Y': $tyoku = ['黄直', '不明']; break;
            case 'B': $tyoku = ['黒直', '不明']; break;
            case 'both': $tyoku = ['白直', '黄直', '黒直', '不明']; break;
        }

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

        $page_type_ids = $page_types->map(function($pt) {
            return $pt->id;
        })
        ->toArray();

        $page_ids = Page::join('inspection_families as if', function($join) use ($itionGId, $tyoku, $start_at, $end_at) {
            $join->on('pages.family_id', '=', 'if.id')
                ->whereIn('if.inspector_group', $tyoku)
                ->where('if.inspection_group_id', '=', $itionGId)
                ->where('if.updated_at', '>=', $start_at)
                ->where('if.updated_at', '<', $end_at);
        })
        ->join('part_page as pp', function($join) {
            $join->on('pages.id', '=', 'pp.page_id');
        })
        ->join('parts', function($join) use ($partTypeId) {
            $join->on('parts.id', '=', 'pp.part_id')->where('parts.part_type_id', '=', $partTypeId);
        })
        ->select('pages.id', 'pages.page_type_id')
        ->get()
        ->filter(function($p) {
            return $p->page_type_id != 14;
        })
        ->map(function($p) {
            return $p->id;
        })
        ->toArray();

        if (count($page_ids) == 0) {
            return [
                'data' => [
                    'message' => '',
                    'count' => 0,
                    'page_types' => [],
                    'failures' => [],
                    'modifications' => [],
                    'holes' => [],
                    'ft' => [],
                    'mt' => [],
                    'hmt' => [],
                    'i' => []
                ]
            ];
        }

        if (count($page_ids) > 5600) {
            return [
                'data' => [
                    'message' => 'over limit',
                    'count' => count($page_ids),
                    'page_types' => [],
                    'failures' => [],
                    'modifications' => [],
                    'holes' => [],
                    'ft' => [],
                    'mt' => [],
                    'hmt' => [],
                    'i' => []
                ]
            ];
        }

        $failures = FailurePosition::whereIn('page_id', $page_ids)
            ->with([
                'part' => function($q) {
                    $q->select(['id', 'parts.part_type_id']);
                },
                'page' => function($q) {
                    $q->select(['id', 'family_id', 'pages.page_type_id']);
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
                    $q->select(['id', 'family_id', 'pages.page_type_id']);
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
                        ->where('status', '!=', 1)
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
            $inlines = Inline::where('part_type_id', '=', $partTypeId)
                ->join('inline_page as ip', function($join) {
                    $join->on('ip.inline_id', '=', 'inlines.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('ip.page_id', '=', 'pg.id');
                })
                ->join('inspection_families as if', function($join) use ($itionGId, $start_at, $end_at) {
                    $join->on('pg.family_id', '=', 'if.id')
                        ->where('if.inspection_group_id', '=', $itionGId)
                        ->where('ip.inspected_at', '>', $start_at)
                        ->where('ip.inspected_at', '<=', $end_at);
                })
                ->select(['inlines.id', 'ip.status', 'ip.part_id', 'ip.inspected_at', 'if.inspector_group'])
                ->orderBy('ip.inspected_at', 'desc')
                ->get()
                ->map(function($i) {
                    return [
                        'id' => $i->id,
                        'part_id' => $i->part_id,
                        'inspected_at' => $i->inspected_at,
                        'status' => $i->status
                    ];
                })
                ->groupBy('id')
                ->map(function($i) {
                    return $i->groupBy('part_id')
                        ->map(function($i) {
                            return $i->first()['status'];
                        })
                        ->values();
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

        return [
            'data' => [
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
                'i' => $inlinesInfo
            ]
        ];
    }

    public function panelIdSerch($partTypeId, $itionGId, $panelId)
    {
        $part = Part::where('panel_id', $panelId)->where('part_type_id', $partTypeId)->first();

        $inspection_group = InspectionGroup::find($itionGId);
        $f = $inspection_group->sortedFailures();
        $m = $inspection_group->sortedModifications();
        $hm = $inspection_group->sortedHoleModifications();
        $h = [];
        if ($inspection_group->inspection->en == 'ana') {
            $h = PartType::find($partTypeId)->holes()->orderBy('label')->get();
        }
        $i = [];
        if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
            $i = PartType::find($partTypeId)->inlines;
        }

        if (!$part instanceof Part) {
            return ['data' => [
                'count' => 0,
                'f' => $f,
                'm' => $m,
                'h' => $h,
                'hm' => $hm,
                'i' => $i,
                'parts' => []
            ]];
        }

        $detail = new PartResult($part->id, $partTypeId, $itionGId);
        $formated = $detail->setDetails()->formatForRerefence()->get();

        if (!$formated) {
            return ['data' => [
                'count' => 0,
                'f' => $f,
                'm' => $m,
                'h' => $h,
                'hm' => $hm,
                'i' => $i,
                'parts' => []
            ]];
        }

        return ['data' => [
            'count' => 1,
            'f' => $f,
            'm' => $m,
            'h' => $h,
            'hm' => $hm,
            'i' => $i,
            'parts' => [$formated]
        ]];
    }

    public function advancedSerch($partTypeId, $itionGId, Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'tyoku' => ['required', 'array'],
                'judgement' => ['required', 'array'],
                'start' => ['required', 'date'],
                'end' => ['required', 'date'],
                'f' => ['array'],
                'm' => ['array'],
                'hm' => ['array']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $judgement = $request->judgement;
        $tyoku = $request->tyoku;
        array_push($tyoku, '不明');
        $start = Carbon::createFromFormat('Y/m/d H:i:s', $request->start.' 06:30:00');
        $end = Carbon::createFromFormat('Y/m/d H:i:s', $request->end.' 08:30:00')->addDay(1);
        $f = $request->f;
        $m = $request->m;

        $parts = $page_types = PartType::find($partTypeId)
            ->parts()
            ->join('part_page as pp', function($join) use ($judgement) {
                $join->on('pp.part_id', '=', 'parts.id')->whereIn('pp.status', $judgement);
            })
            ->join('pages as pg', function($join) {
                $join->on('pg.id', '=', 'pp.page_id');
            })
            ->join('inspection_families as if', function($join) use ($itionGId, $start, $end, $tyoku, $judgement) {
                $join->on('if.id', '=', 'pg.family_id')
                    ->whereIn('if.inspector_group', $tyoku)
                    ->whereIn('if.status', $judgement)
                    ->where('inspection_group_id', '=', $itionGId)
                    ->whereNull('if.deleted_at')
                    ->where(function($q) use ($start, $end) {
                        $q->whereNotNull('if.inspected_at')->orWhere(function($q) use ($start, $end) {
                            $q->where('if.created_at', '>=', $start)->where('if.created_at', '<', $end);
                        });
                    })
                    ->where(function($q) use ($start, $end) {
                        $q->whereNull('if.inspected_at')->orWhere(function($q) use ($start, $end) {
                            $q->where('if.created_at', '>=', $start)->where('if.created_at', '<', $end);
                        });
                    });
            })
            ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.inspected_at'])
            ->orderBy('if.inspected_at', 'asc', 'if.created_at', 'asc')
            ->groupBy('parts.id')
            ->with([
                'pages' => function($q) use ($itionGId, $partTypeId, $f, $m) {
                    $q = $q->join('inspection_families as if', function($join) use ($itionGId) {
                        $join->on('if.id', '=', 'pages.family_id')->where('inspection_group_id', '=', $itionGId);
                    });
                    if (count($f) != 0) {
                        $q = $q->whereHas('failurePositions', function($q) use($partTypeId, $f) {
                            $q->join('parts as p', 'p.id', '=', 'failure_positions.part_id')
                                ->where('p.part_type_id', '=', $partTypeId)
                                ->whereIn('failure_id', $f);
                        });
                    }
                    if (count($m) != 0) {
                        $q = $q->whereHas('comments', function($q) use($partTypeId, $m) {
                            $q->join('failure_positions as fp', 'fp.id', '=', 'modification_failure_position.fp_id')
                                ->join('parts as p', 'p.id', '=', 'fp.part_id')
                                ->where('p.part_type_id', '=', $partTypeId)
                                ->whereIn('m_id', $m);
                        });
                    }
                    $q->get();
                }
            ])
            ->get()
            ->filter(function($part) {
                return $part->pages->count() > 0;
            })
            ->map(function($part) {
                return $part->id;
            })
            ->values()
            ->sort();

        $count = $parts->count();
        $data = [];

        if ($count > 1000) {
            for ($i=0; $i < 1000; $i++) {
                $detail = new PartResult($parts[$i], $partTypeId, $itionGId);
                $formated = $detail->setDetails()->formatForRerefence()->get();
                $data[] = $formated;
            }
        }
        else {
            foreach ($parts as $part_id) {
                $detail = new PartResult($part_id, $partTypeId, $itionGId);
                $formated = $detail->setDetails()->formatForRerefence()->get();
                $data[] = $formated;
            }
        }

        $inspection_group = InspectionGroup::find($itionGId);
        $f = $inspection_group->sortedFailures();
        $m = $inspection_group->sortedModifications();
        $hm = $inspection_group->sortedHoleModifications();
        $h = [];
        if ($inspection_group->inspection->en == 'ana') {
            $h = PartType::find($partTypeId)
                ->holes()
                ->where('figure_id', '!=', 9)
                ->orderBy('label')->get();
        }
        $i = [];
        if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
            $i = PartType::find($partTypeId)->inlines;
        }

        return ['data' => [
            'count' => $count,
            'f' => $f,
            'm' => $m,
            'h' => $h,
            'hm' => $hm,
            'i' => $i,
            'parts' => $data
        ]];
    }

    public function failures($itionGId)
    {
        $failureTypes = InspectionGroup::find($itionGId)->inspection->failures->map(function($f) {
            return [
                'id' => $f->id,
                'label' => $f->label,
                'name' => $f->name,
                'type' => $f->pivot->type,
                'sort' => $f->pivot->sort
            ];
        })->toArray();

        if (count($failureTypes) == 0) {
            return ['data' => []];
        }

        foreach( $failureTypes as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }

        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failureTypes);

        return ['data' => $failureTypes];
    }

    public function modifications($itionGId)
    {
        $modificationTypes = InspectionGroup::find($itionGId)->inspection->modifications->map(function($m) {
            return [
                'id' => $m->id,
                'label' => intval($m->label),
                'name' => $m->name,
                'type' => $m->pivot->type,
                'sort' => $m->pivot->sort
            ];
        })->toArray();

        if (count($modificationTypes) == 0) {
            return ['data' => []];
        }

        foreach( $modificationTypes as $key => $row ) {
            $m_type_array[$key] = $row['type'];
            $m_label_array[$key] = $row['label'];
            $m_sort_array[$key] = $row['sort'];
        }

        if (count($modificationTypes) !== 0 ) {
            array_multisort($m_type_array, $m_sort_array, $m_label_array, $modificationTypes);
        }

        return ['data' => $modificationTypes];
    }

    public function partFamily(Request $request)
    {
        $partTypeId = $request->partTypeId;
        $panelId = $request->panelId;

        if ($request->start) {
            $start = Carbon::createFromFormat('Y-m-d-H i:s', $request->start.' 00:00');
        } else {
            $start = Carbon::createFromFormat('Y-m-d-H i:s', '2000-01-01-00 00:00');
        }
        if ($request->end) {
            $end = Carbon::createFromFormat('Y-m-d-H i:s', $request->end.' 00:00')->addHours(1);
        } else {
            $end = Carbon::now();
        }

        $PartFamilis = PartFamily::where('part_families.updated_at', '>=', $start)
            ->where('part_families.updated_at', '<', $end)
            ->join('parts', function ($join) use ($partTypeId, $panelId) {
                if ($partTypeId !== null && $panelId !== null) {
                    $join->on('part_families.id', '=', 'parts.family_id')
                        ->where('part_type_id', '=', $partTypeId)
                        ->where('panel_id', 'like', $panelId.'%');
                }
                else {
                    $join->on('part_families.id', '=', 'parts.family_id');
                }
            })
            ->get()
            ->map(function($pf) {
                return $pf->family_id;
            })
            ->toArray();

        $data = PartFamily::whereIn('id', $PartFamilis)->with([
            'parts.partType'
        ])
        ->orderBy('part_families.updated_at')
        ->get()
        ->map(function($f) {
            return [
                'familyId' => $f->id,
                'associatedAt' => $f->updated_at->format('Y-m-d H:i:s'),
                'parts' => $f->parts->map(function($p) {
                    return [
                        'id' => $p->id,
                        'panelId' => $p->panel_id,
                        'pn' => $p->partType->pn
                    ];
                })->groupBy('pn')
            ];
        });

        return ['data' => $data];
    }

    public function test()
    {
        $itorG_name = ['不明'];

        return PageType::with([
            'pages' => function ($q) use ($itorG_name) {
                $q->join('part_page as pp', function ($join) use ($itorG_name) {
                    $join->on('pp.page_id', '=', 'pages.id');
                })
                ->join('parts', function ($join) use ($itorG_name) {
                    $join->on('pp.part_id', '=', 'parts.id')
                        ->where('parts.panel_id', '=', 'B0000002');
                })
                ->select([
                    'pages.id as id',
                    'pages.family_id',
                    'pages.page_type_id',
                    'parts.panel_id',
                    'parts.part_type_id'
                ])
                ->groupBy('id')
                ->get();
            }
        ])
        ->find(8);
    }
}