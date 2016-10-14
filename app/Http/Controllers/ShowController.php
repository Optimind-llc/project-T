<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle;
use App\Models\Process;
use App\Models\Inspection;
use App\Models\InspectionGroup;
use App\Models\InspectorGroup;
use App\Models\PageType;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * Class ShowController
 * @package App\Http\Controllers
 */
class ShowController extends Controller
{
    protected function findInspection($process_id, $inspection_en) {
        $inspection = Inspection::where('process_id', $process_id)
            ->where('en', $inspection_en)
            ->first();

        if (!$inspection instanceof Inspection) {
            throw new NotFoundHttpException('Inspection not found');
        }

        return $inspection;
    }

    protected function findInspectionGroup($vehicle, $process_id, $inspection_en, $division_en, $line = null) {
        $group = $this->findInspection($process_id, $inspection_en)
            ->getGroupByVehicleDivisionLine($vehicle, $division_en, $line);

        if (!$group instanceof InspectionGroup) {
            throw new NotFoundHttpException('Inspection group not found');
        }

        return $group;
    }

    protected function processData($option) {
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

    protected function vehicleData($option) {
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

    protected function inspectorGData($option) {
        switch ($option) {
            case 'all':
                $vehicles = InspectorGroup::select(['code as c', 'name as n'])->get();
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

    public function tableData(Request $request)
    {
        $data = [];

        if ($request->process)
            $data['process'] = $this->processData($request->process);

        if ($request->vehicle)
            $data['vehicle'] = $this->vehicleData($request->vehicle);

        if ($request->inspectorG)
            $data['inspectorG'] = $this->inspectorGData($request->inspectorG);

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

    /*
     * Get page types from vehicle_code, process_en, inspection_en, division_en, line
     */
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

    public function page($pageType, $itorG, Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'start' => ['alpha_dash'],
                'end' => ['alpha_dash'],
                'panelId' => ['alpha_dash']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $now = Carbon::now();

        if (isset($request->start) && isset($request->end)) {
            $start_at = Carbon::createFromFormat('Y-m-d-H-i', $request->start);
            $end_at = Carbon::createFromFormat('Y-m-d-H-i', $request->end);
        }
        else {
            $today = Carbon::today();
            $t2_end_at = $today->copy()->addHours(1);
            $t1_start_at = $today->copy()->addHours(6);
            $t1_end_at = $today->copy()->addHours(15)->addMinutes(30);
            $t2_start_at = $today->copy()->addHours(16)->addMinutes(30);

            if ($now->lt($t2_end_at)) {
                $start_at = $t1_start_at->copy()->subDay();
            }
            elseif ($now->lt($t1_end_at)) {
                $start_at = $t2_start_at->copy()->subDay();
            }
            else {
                $start_at = $t1_start_at;
            }

            $end_at = $now;
        }

        switch ($request->itorG) {
            case 'W': $itorG_name = ['白直', '不明']; break;
            case 'Y': $itorG_name = ['黄直', '不明']; break;
            case 'both': $itorG_name = ['白直', '黄直', '不明']; break;
        }

        $panel_id = $request->panelId;

        $page_type = PageType::with([
                'figure' => function ($q) {
                    $q->select(['id', 'path']);
                },
                'partTypes' => function ($q) {
                    $q->select(['id', 'pn', 'name']);
                },
                'group' => function ($q) {
                    $q->select(['id', 'division_en', 'vehicle_num', 'line', 'inspection_id']);
                },
                'group.inspection' => function ($q) {
                    $q->select(['id', 'name', 'process_id']);
                },
                'group.inspection.process' => function ($q) {
                    $q->select(['id', 'name']);
                },
                'group.inspection.process.failures' => function ($q) {
                    $q->select(['id', 'name', 'sort']);
                },
                'pages' => function ($q) use ($start_at, $end_at, $itorG_name, $panel_id) {
                    $q->join('inspection_families as f', function ($join) use ($itorG_name) {
                        $join->on('pages.family_id', '=', 'f.id')
                            ->whereIn('f.inspector_group', $itorG_name);
                    })
                    ->join('part_page as pp', function ($join) use ($itorG_name) {
                        $join->on('pp.page_id', '=', 'pages.id');
                    })
                    ->join('parts', function ($join) use ($itorG_name, $panel_id) {
                        $aaa = $join->on('pp.part_id', '=', 'parts.id');
                        if ($panel_id) {
                            $aaa->where('parts.panel_id', '=', $panel_id);
                        }
                    })
                    ->where('pages.created_at', '>=', $start_at)
                    ->where('pages.created_at', '<=', $end_at)
                    ->groupBy('id')
                    ->select([
                        'f.id as family_id',
                        'pages.id as id',
                        'pages.page_type_id',
                        'pp.status',
                        'parts.panel_id'
                    ]);
                },
                'pages.holes' => function ($q) {
                    $q->select(['id', 'point', 'label', 'direction', 'part_type_id']);
                },
                'pages.holes.partType' => function ($q) {
                    $q->select(['id', 'pn']);
                },
                'pages.failurePositions' => function ($q) {
                    $q->select(['id', 'point', 'type', 'page_id', 'part_id', 'failure_id']);
                },
                'pages.failurePositions.failure' => function ($q) {
                    $q->select(['id', 'name', 'sort']);
                },
                'pages.failurePositions.part' => function ($q) {
                    $q->select(['id', 'panel_id', 'part_type_id']);
                },
                'pages.failurePositions.part.partType' => function ($q) {
                    $q->select(['id', 'name', 'pn']);
                },
                'pages.inlines' => function ($q) {
                    $q->select(['inlines.id', 'point', 'label_point', 'side', 'face', 'standard_tolerance', 'sort', 'part_type_id']);
                },
            ])
            ->find($pageType);

        if (!$page_type instanceof PageType) {
            throw new NotFoundHttpException('Page type not found');
        }

        $collection = collect();
        $collection2 = collect();
        $collection3 = collect();

        $page_type = [
            'id' => $page_type->id,
            'pages' => $page_type->pages->count(),
            'number' => $page_type->number,
            'path' => '/img/figures/'.$page_type->figure->path,
            'line' => $page_type->group->line,
            'vehicle' => $page_type->group->vehicle_num,
            'inspection' => $page_type->group->inspection->name,
            'process' => $page_type->group->inspection->process->name,
            'parts' => $page_type->partTypes->map(function($part) {
                return [
                    'pn' => $part->pn,
                    'name' => $part->name,
                    'area' => $part->pivot->area
                ];
            }),
            'partsDetail' => $page_type->pages->map(function($page) {
                return [
                    'status' => $page->status,
                    'panelId' => $page->panel_id
                ];
            }),
            'holes' => $page_type->pages->reduce(function ($carry, $page) {
                return $carry->merge($page->holes->map(function($hole) {
                    return [
                        'id' => $hole->id,
                        'point' => $hole->point,
                        'label' => $hole->label,
                        'direction' => $hole->direction,
                        'part' => $hole->partType->pn,
                        'status' => $hole->pivot->status
                    ];
                }));
            }, $collection)
            ->groupBy('part')
            ->map(function($hole) {
                return $hole->groupBy('id');
            }),
            'inlines' => $page_type->pages->reduce(function ($carry, $page) {
                return $carry->merge($page->inlines->map(function($hole) {
                    return [
                        'id' => $hole->id,
                        'point' => $hole->point,
                        'labelPoint' => $hole->label_point,
                        'side' => $hole->side,
                        'face' => $hole->face,
                        'tolerance' => $hole->standard_tolerance,
                        'sort' => $hole->sort,
                        'status' => $hole->pivot->status
                    ];
                }));
            }, $collection2)
            ->groupBy('id'),
            'failures' => $page_type->pages->reduce(function ($carry, $page) {
                return $carry->merge($page->failurePositions->map(function($failure) {
                    return [
                        'id' => $failure->failure->id,
                        'sort' => $failure->failure->sort,
                        'point' => $failure->point,
                        'type' => $failure->type,
                        'part' => $failure->part->partType->pn
                    ];
                }));
            }, $collection3)
            ->groupBy('part'),
            'failureTypes' => $page_type->group->inspection->process->failures->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'sort' => $f->sort,
                    'type' => $f->pivot->type
                ];
            })
        ];

        return ['data' => $page_type];
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