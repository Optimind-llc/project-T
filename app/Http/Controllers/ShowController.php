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
use App\Models\InspectorGroup;
use App\Models\PageType;
use App\Models\PartType;
use App\Models\Client\PartFamily;
use App\Models\Client\Part;

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

    protected function formatPage($pages, $partTypeId)
    {
        return [
            'failures' => $pages->reduce(function ($carry, $page) use($partTypeId){
                return $carry->merge($page->failurePositions->map(function($fp) use($page) {
                    return [
                        'id' => $fp->failure->id,
                        'label' => $fp->failure->label,
                        'point' => $fp->point,
                        'type' => $fp->type,
                        'part' => $fp->part->partType->id,
                        'choku' => $page->inspector_group
                    ];
                }));
            }, collect([]))
            ->filter(function($fp) use($partTypeId) {
                return $fp['part'] == $partTypeId;
            })
            ->values(),
            'comments' => $pages->reduce(function ($carry, $page) {
                return $carry->merge($page->comments->map(function($cf) use($page) {
                    return [    
                        'id' => $cf->m_id,
                        'message' => $cf->modification->name,
                        'point' => $cf->failurePosition->point,
                        'choku' => $page->inspector_group
                    ];
                }));
            }, collect([])),
            'inlines' => $pages->reduce(function ($carry, $page) {
                return $carry->merge($page->inlines->map(function($i) use($page) {
                    return [
                        'id' => $i->id,
                        'point' => $i->point,
                        'labelPoint' => $i->label_point,
                        'max' => $i->max_tolerance,
                        'min' => $i->min_tolerance,
                        'position' => $i->position,
                        'side' => $i->side,
                        'sort' => $i->sort,
                        'face' => $i->face,
                        'status' => $i->pivot->status,
                        'partId' => $page->parts->first()->id
                    ];
                }));
            }, collect([]))
            ->groupBy('id')
            ->values()
            ->map(function($i) {
                return $i->groupBy('partId')->map(function($p) {
                    return $p->first();
                })->values();
            })
            ->values(),
            'pages' => $pages->count()
        ];
    }

    public function page2($partTypeId, $itionGId, $itorG, Request $request)
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
        $panel_id = $request->panelId;

        //Checke Panel ID isset
        if (isset($request->panelId)) {
            $searched_part = Part::where('panel_id', $request->panelId)
                ->where('part_type_id', $partTypeId)
                ->get();
            
            if ($searched_part->count() === 0) {
               return [
                    'data' => [
                        'pages' => 0,
                        'failureTypes' => [],
                        'failures' => [],
                        'holePoints' => [],
                        'commentTypes' => [],
                        'comments' => [],
                        'holeModificationTypes' => [],
                        'inlines' => []
                    ]
               ];
            }
        }

        if (isset($request->start) && isset($request->end)) {
            $start_at = Carbon::createFromFormat('Y-m-d-H-i-s', $request->start.'-00-00-00')->addHours(2);
            $end_at = Carbon::createFromFormat('Y-m-d-H-i-s', $request->end.'-00-00-00')->addHours(26);
        }
        elseif (!isset($request->panelId)) {
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
        elseif (isset($request->panelId)) {
            $start_at = false;
            $end_at = false;
        }

        switch ($request->itorG) {
            case 'W': $itorG_name = ['白直', '不明']; break;
            case 'Y': $itorG_name = ['黄直', '不明']; break;
            case 'both': $itorG_name = ['白直', '黄直', '不明']; break;
        }

        $page_types = PartType::find($partTypeId)
            ->pageTypes()
            ->with('figure')
            ->where('group_id', $itionGId)
            ->orderBy('number')
            ->get()
            ->filter(function($pt) {
                return $pt->id != 14;
            });

        if ($page_types->count() == 0) {
            return [
                'data' => [
                'pages' => 'notFound',
                    'failures' => [],
                    'failureTypes' => [],
                    'commentTypes' => [],
                    'holePoints' => [],
                    'holeModificationTypes' => [],
                    'inlines' => []
                ]
            ];
        }

        // Get Failure list
        $failureTypes = $this->failures($itionGId)['data'];

        // Get Failure Modifications list
        $modificationTypes = $this->modifications($itionGId)['data'];

        // Get Hole Modifications list
        $holeModificationTypes = InspectionGroup::find($itionGId)->inspection->hModifications->map(function($hm) {
            return [
                'id' => $hm->id,
                'label' => $hm->label,
                'name' => $hm->name,
                'type' => $hm->pivot->type,
                'sort' => $hm->pivot->sort
            ];
        })->toArray();

        foreach( $holeModificationTypes as $key => $row ) {
            $hm_type_array[$key] = $row['type'];
            $hm_label_array[$key] = $row['label'];
            $hm_sort_array[$key] = $row['sort'];
        }

        if (count($holeModificationTypes) !== 0 ) {
            array_multisort($hm_type_array, $hm_sort_array, $hm_label_array, $holeModificationTypes);
        }

        $page_ids = null;
        if (isset($request->panelId)) {
            $page_ids = Part::where('panel_id', $request->panelId)
                ->where('part_type_id', $partTypeId)
                ->first()
                ->pages()
                ->get(['id'])
                ->map(function($p) {
                    return $p->id;
                })
                ->toArray();
        }

        $holePoints = InspectionGroup::find($itionGId)
            ->pageTypes()
            ->select(['figure_id', 'number'])
            ->with([
                'figure' => function($q) {
                    $q->select(['id']);
                },
                'figure.holes' => function($q) use ($partTypeId, $page_ids, $itorG_name, $start_at, $end_at) {
                    $q->where('part_type_id', $partTypeId)
                        ->leftJoin('hole_page as hp', 'holes.id', '=', 'hp.hole_id')
                        ->join('pages', function ($join) use ($page_ids){
                            $join = $join->on('pages.id', '=', 'hp.page_id');
                            if ($page_ids) {
                                $join->whereIn('pages.id', $page_ids);
                            }
                        })
                        ->join('inspection_families as if', function ($join) use ($itorG_name,  $start_at, $end_at){
                            $join = $join->on('if.id', '=', 'pages.family_id')
                                ->whereIn('inspector_group', $itorG_name);
                            if ($start_at) {
                                $join->where('if.updated_at', '>=', $start_at)
                                    ->where('if.updated_at', '<=', $end_at);
                            }
                        })
                        ->leftJoin('hole_page_hole_modification as hphm', function ($join) {
                            $join = $join->on('hp.id', '=', 'hphm.hp_id');
                        })
                        ->select(DB::raw(
                            'holes.id, holes.point, holes.label, holes.direction, figure_id, COUNT(hp.status) as sum, SUM(hp.status = 0) as s0, SUM(hp.status = 1) as s1, SUM(hp.status = 2) as s2, SUM(hphm.hm_id IS NOT NULL) as s3'
                        ))
                        ->groupBy('holes.id');
                },
                'figure.holes.holePages' => function($q) {
                    $q->select(['hole_page.id', 'hole_id', 'page_id']);
                },
                'figure.holes.holePages.holeModification' => function($q) {
                    $q->select(['hole_modifications.id', 'name', 'label']);
                }
            ])
            ->get()
            ->reduce(function($array, $pt) use($page_ids) {
                $holes = $pt->figure->holes->map(function($h) use($pt, $page_ids) {
                    return [
                        'id' => $h->id,
                        'label' => $h->label,
                        'point' => $h->point,
                        'direction' => $h->direction,
                        's1' => $h->s1,
                        's2' => $h->s2,
                        's0' => $h->s0,
                        's3' => $h->s3,
                        'sum' => $h->sum,
                        'modi' => $h->hm_id,
                        'pageNum' => $pt->number,
                        'holes' => $h->holePages->map(function($hp) use ($page_ids) {
                            if ($hp->holeModification->count() == 0) {
                                return 0;
                            }

                            if ($page_ids) {
                                if (in_array($hp->page_id, $page_ids)) {
                                    return $hp->holeModification[0]->id;
                                }
                                return 0;
                            }

                            return $hp->holeModification[0]->id;
                        }),
                    ];
                })
                ->sortBy('label')
                ->toArray();
                return array_merge($array, $holes);
            }, []);

        if ($page_types->count() > 1) {
            $pageTypes = $page_types->map(function($pt) use($partTypeId, $itionGId, $itorG_name, $start_at, $end_at, $panel_id) {
                $pages = $pt->pagesWithRelated($itorG_name, $start_at, $end_at, $panel_id);
                $data = $this->formatPage($pages, $partTypeId);
                $data['path'] = '/img/figures/'.$pt->figure->path;
                $data['pageNum'] = $pt->number;
                return $data;
            })
            ->toArray();

            return [
                'data' => [
                    'pageTypes' => $pageTypes,
                    'failureTypes' => $failureTypes,
                    'commentTypes' => $modificationTypes,
                    'holePoints' => $holePoints,
                    'holeModificationTypes' => $holeModificationTypes,
                    'path' => []
                ]
            ];
        }

        $page_type = $page_types->first();

        $pages = $page_type->pagesWithRelated($itorG_name, $start_at, $end_at, $panel_id);
        $data = $this->formatPage($pages, $partTypeId);
        $data['path'] = '/img/figures/'.$page_type->figure->path;
        $data['pageNum'] = $page_type->number;
        $data['failureTypes'] = $failureTypes;
        $data['commentTypes'] = $modificationTypes;
        $data['holePoints'] = $holePoints;
        $data['holeModificationTypes'] = $holeModificationTypes;

        return [
            'data' => $data
        ];
    }

    public function panelIdSerch($partTypeId, $itionGId, $panelId)
    {
        $part = Part::where('panel_id', $panelId)->where('part_type_id', $partTypeId)->first();

        if (!$part instanceof Part) {
            return ['data' => [
                'count' => 0,
                'parts' => []
            ]];
        }

        $detail = new PartResult($part->id, $partTypeId, $itionGId);
        $formated = $detail->setDetails()->formatForRerefence()->get();

        $inspection_group = InspectionGroup::find($itionGId);
        $f = $inspection_group->sortedFailures();
        $m = $inspection_group->sortedModifications();
        $hm = $inspection_group->sortedHoleModifications();
        $h = [];
        if ($inspection_group->inspection->en == 'ana') {
            $h = PartType::find($partTypeId)->holes()->orderBy('label')->get();
        }

        return ['data' => [
            'count' => 1,
            'f' => $f,
            'm' => $m,
            'h' => $h,
            'hm' => $m,
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
        $start = Carbon::createFromFormat('Y/m/d H:i:s', $request->start.' 02:00:00');
        $end = Carbon::createFromFormat('Y/m/d H:i:s', $request->end.' 02:00:00')->addDay(1);
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
            ->join('inspection_families as if', function($join) use ($itionGId, $start, $end, $tyoku) {
                $join->on('if.id', '=', 'pg.family_id')
                    ->where('if.updated_at', '>=', $start)
                    ->where('if.updated_at', '<', $end)
                    ->whereIn('if.inspector_group', $tyoku)
                    ->where('inspection_group_id', '=', $itionGId);
            })
            ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.inspected_at'])
            ->orderBy('if.inspected_at', 'if.created_at')
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
                },
                'pages.failurePositions',
                'pages.comments'
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
            $h = PartType::find($partTypeId)->holes()->orderBy('label')->get();
        }
        $i = [];
        if ($itionGId == 3 || $itionGId == 9) {
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
        $partTypeId = $request->part_type_id;
        $panelId = $request->panel_id;
        $start = Carbon::createFromFormat('Y-m-d-H i:s', $request->start.' 00:00');
        $end = Carbon::createFromFormat('Y-m-d-H i:s', $request->end.' 00:00');

        $PartFamilis = PartFamily::where('part_families.created_at', '>=', $start)
            ->where('part_families.created_at', '<', $end)
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
        ])->get()->map(function($f) {
            return [
                'familyId' => $f->id,
                'associatedAt' => $f->updated_at->format('Y-m-d H:i:s'),
                'parts' => $f->parts->map(function($p) {
                    return [
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