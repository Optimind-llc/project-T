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
use App\Models\Division;
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

    protected function findInspectionByEn($en, Process $process) {
        $inspection = $process->inspections()->where('en', $en)->first();

        if (!$inspection instanceof Inspection) {
            throw new NotFoundHttpException('Inspection not found');
        }

        return $inspection;
    }

    protected function findDivisionByEn($en) {
        $division = Division::where('en', $en)->first();

        if (!$division instanceof Division) {
            throw new NotFoundHttpException('Division not found');
        }

        return $division;
    }

    /**
     * Get user from JWT token
     */
    public function inspectorGroup()
    {
        return InspectorGroup::with([
                'inspector' => function ($query) {
                    $query->select('inspectors.id', 'inspectors.group_id', 'name', 'code');
                }
            ])
            ->get()
            ->map(function ($group, $key) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'code' => $group->code,
                    'startAt' => $group->start_at,
                    'endAt' => $group->end_at,
                    'inspectors' => $group['inspector']->map(function ($inspector, $key) {
                        return [
                            'id' => $inspector->id,
                            'name' => $inspector->name,
                            'code' => $inspector->code
                        ];
                    })
                ];
            })
            ->toArray();
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

        $division_id = $this->findDivisionByEn($request->division)->id;
        $process = $this->findProcessByEn($request->process);
        $inspection = $this->findInspectionByEn($request->inspection, $process);

        $inspection_groups = $inspection->groups()
            ->where('division_id', $division_id)
            ->with([
                'pageTypes',
                'pageTypes.partTypes',
                'pageTypes.partTypes.vehicle',
                'pageTypes.figure',
                'pageTypes.figure.holes',
                'inspection.process.failures'
            ])
            ->get()
            ->map(function ($group, $key) {
                return [
                    'id' => $group->id,
                    'failures' => $group->inspection->process->failures->map(function ($failure, $key) {
                        return [
                            'id' => $failure->id,
                            'name' => $failure->name,
                            'type' => $failure->pivot->type
                        ];
                    }),
                    'pages' => $group->pageTypes->map(function ($page, $key) {
                        return [
                            'id' => $page->id,
                            'parts' => $page->partTypes->map(function ($part, $key) {
                                return [
                                    'id' => $part->id,
                                    'name' => $part->name,
                                    'pn' => $part->pn,
                                    'vehicle' => $part->vehicle->number
                                ];
                            }),
                            'figure' => [
                                'id' => $page->figure->id,
                                'path' => 'images/figures/'.$page->figure->path,
                                'holes' => $page->figure->holes->map(function ($hole, $key) {
                                    return [
                                        'id' => $hole->id,
                                        'point' => $hole->point
                                    ];
                                })
                            ]
                        ];
                    })
                ];
            })
            ->toArray();

        return [
            "group" => $inspection_groups[0]
        ];
    }

    /**
     * Get user from JWT token
     */
    public function saveInspection(Request $request)
    {
        $family = $request['family'];

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
            DB::table('failure_page')->insert(array_map(function($n) use ($newPage) {
                    return [
                        'page_id' => $newPage->id,
                        'failure_id' => $n['id'],
                        'point' => $n['point'],
                        'point_sub' => $n['point_sub']
                    ];
                },
                $page['failures'])
            );

            // create holes
            if (isset($page['holes'])) {
                DB::table('hole_page')->insert(array_map(function($n) use ($newPage) {
                        return [
                            'page_id' => $newPage->id,
                            'hole_id' => $n['id'],
                            'status' => $n['status']
                        ];
                    },
                    $page['holes'])
                );
            }

            // create comments
            // if (isset($page['holes'])) {
            //     $newPage->holes()->attach(1);
            //     DB::table('hole_page')->insert(array_map(function($n) use ($newPage) {
            //             return [
            //                 'page_id' => $newPage->id,
            //                 'hole_id' => $n['id'],
            //                 'status' => $n['status']
            //             ];
            //         },
            //         $page['failures'])
            //     );
            // }
        }

        return $family;
    }
    
}

