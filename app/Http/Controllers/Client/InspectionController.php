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
use App\Models\Client\InspectionFamily;
use App\Models\Client\Page;
use App\Models\Client\Part;
use App\Models\Client\FailurePage;
// Exceptions
use JWTAuth;
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
        $process = Process::where('en', $en)->first();

        if (!$process instanceof Process) {
            throw new NotFoundHttpException('Process not found');
        }

        return $process;
    }

    protected function findInspectionGroup($inspection_en, Process $process, $division_en) {
        $inspection = $process->inspections()->where('en', $inspection_en)->first();

        if (!$inspection instanceof Inspection) {
            throw new NotFoundHttpException('Inspection not found');
        }

        $group = $inspection->getByDivisionWithRelated($division_en);

        if (!$group instanceof InspectionGroup) {
            throw new NotFoundHttpException('Inspection group not found');
        }

        return $group;
    }

    protected function formatInspectors($inspectors) {

        return $inspectors->map(function ($i) {
                return [
                    'id' => $i->id,
                    'name' => $i->name,
                    'code' => $i->code,
                    'group' => $i->group_code,
                    'sort' => $i->pivot->sort
                ];
            })
            ->sortBy('sort')
            ->groupBy('group');
    }

    protected function adjust(Request $request, $inspection_group)
    {
        $validator = app('validator')->make(
            $request->all(),
            ['panelId' => ['required', 'alpha_dash']]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $filterInspection = function($i) {
            return $i->groups[0]->families->count() > 0;
        };

        $mapInspection = function ($i) {
            return $i->groups[0]
                ->families
                ->filter(function ($f) {
                    return $f->pages->count() > 0;
                })[0]
                ->pages[0]
                ->failurePositions
                ->map(function ($fp) use ($i){
                    return [
                        'failurePositionId' => $fp->id,
                        'inspectionName' => $i->name,
                        'failureName' => $fp->failure->name,                            
                        'point' => $fp->point,
                        'point_sub' => $fp->point_sub
                    ];
                })
                ->toArray();
        };

        $mergeToOne = function ($a, $b) {
            return array_merge($a, $b);
        };

        $history = $this->findProcessByEn($request->process)
            ->getAllInspectionswithRelated($request->division, $request->panelId)
            ->filter($filterInspection)
            ->map($mapInspection)
            ->reduce($mergeToOne, []);

        return [
            'group' => [
                'id' => $inspection_group->id,
                'inspectorGroups' => $this->formatInspectors($inspection_group->inspectors),
                'failures' => $inspection_group->inspection->process->failures->map(function ($failure) {
                    return [
                        'id' => $failure->id,
                        'name' => $failure->name,
                        'type' => $failure->pivot->type
                    ];
                }),
                'comments' => $inspection_group->inspection->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'message' => $comment->message
                    ];
                }),
                'pages' => $inspection_group->pageTypes->map(function ($page) use ($history) {
                    return [
                        'id' => $page->id,
                        'parts' => $page->partTypes->map(function ($part) use ($history) {
                            return [
                                'id' => $part->id,
                                'name' => $part->name,
                                'pn' => $part->pn,
                                'vehicle' => $part->vehicle->number
                            ];
                        }),
                        'figure' => [
                            'path' => 'images/figures/'.$page->figure->path,
                            'holes' => $page->figure->holes->map(function ($hole) {
                                return [
                                    'id' => $hole->id,
                                    'point' => $hole->point
                                ];
                            })
                        ],
                        'history' => $history
                    ];
                })
            ]
        ];
    }

    /**
     * Get user from JWT token
     */
    public function inspection(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'division' => ['required', 'alpha_dash'],
                'process' => ['required', 'alpha_dash'],
                'inspection' => ['required', 'alpha_dash']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $process = $this->findProcessByEn($request->process);
        $inspection_group = $this->findInspectionGroup(
            $request->inspection,
            $process,
            $request->division
        );

        if ($request->inspection == 'adjust') {
            return $this->adjust($request, $inspection_group);
        }

        return [
            'group' => [
                'id' => $inspection_group->id,
                'inspectorGroups' => $this->formatInspectors($inspection_group->inspectors),
                'failures' => $inspection_group->inspection->process->failures->map(function ($failure) {
                    return [
                        'id' => $failure->id,
                        'name' => $failure->name,
                        'type' => $failure->pivot->type
                    ];
                }),
                'comments' => $inspection_group->inspection->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'message' => $comment->message
                    ];
                }),
                'pages' => $inspection_group->pageTypes->map(function ($page) {
                    return [
                        'id' => $page->id,
                        'parts' => $page->partTypes->map(function ($part) {
                            return [
                                'id' => $part->id,
                                'name' => $part->name,
                                'pn' => $part->pn,
                                'vehicle' => $part->vehicle->number
                            ];
                        }),
                        'figure' => [
                            'path' => 'images/figures/'.$page->figure->path,
                            'holes' => $page->figure->holes->map(function ($hole) {
                                return [
                                    'id' => $hole->id,
                                    'point' => $hole->point
                                ];
                            })
                        ]
                    ];
                })
            ]
        ];
    }

    /**
     * Get user from JWT token
     */
    public function saveInspection(Request $request)
    {
        $family = $request->family;

        //duplicate detection
        $dupIF = InspectionFamily::where('inspection_group_id', $family['groupId'])->first();

        if ($dupIF instanceof InspectionFamily) {
            $dupP = $dupIF->pages()
                ->where('page_type_id', $family['pages'][0]['pageId'])
                ->whereHas('parts', function($q) use ($family) {
                    $q->where('panel_id', $family['pages'][0]['parts'][0]['panelId']);
                })
                ->count();

            if ($dupP !== 0) {
                throw new StoreResourceFailedException('The part was already inspected in this process');
            }
        }

        $newFamily = new InspectionFamily;
        $newFamily->inspection_group_id = $family['groupId'];
        $newFamily->line = $family['line'];
        $newFamily->inspector_group = $family['inspectorGroup'];
        $newFamily->created_by = $family['inspector'];
        $newFamily->save();

        foreach ($family['pages'] as $key => $page) {
            $newPage = new Page;
            $newPage->page_type_id = $page['pageId'];
            $newPage->table = isset($page['table']) ? $page['table'] : null;
            $newPage->status = $page['status'];
            $newPage->status = $page['status'];
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

                $newPage->parts()->attach($newPart->id);
            }

            // create failure
            DB::table('failure_positions')->insert(array_map(function($f) use ($newPage) {
                    return [
                        'page_id' => $newPage->id,
                        'failure_id' => $f['id'],
                        'point' => $f['point'],
                        'point_sub' => $f['pointSub']
                    ];
                },
                $page['failures'])
            );

            // create holes
            if (isset($page['holes'])) {
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

            // create comments
            if (isset($page['comments'])) {
                DB::table('comment_failure_position')->insert(array_map(function($c) use ($newPage) {
                        return [
                            'page_id' => $newPage->id,
                            'failure_position_id' => $c['failurePositionId'],
                            'comment_id' => $c['commentId']
                        ];
                    },
                    $page['comments'])
                );
            }
        }

        return $family;
    }
    
}

