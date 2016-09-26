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

        $inspector_group = new InspectorGroup;
        $inspector_groups = $inspector_group->findInspectorsByProcessEn($request->process);

        $inspection_group = $this->findInspectionByEn($request->inspection, $process)
            ->getByDivisionWithRelated($division_id);

        return [
            'group' => [
                'id' => $inspection_group->id,
                'inspectorGroups' => $inspector_groups,
                'failures' => $inspection_group->inspection->process->failures->map(function ($failure) {
                    return [
                        'id' => $failure->id,
                        'name' => $failure->name,
                        'type' => $failure->pivot->type
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
                            'id' => $page->figure->id,
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
            DB::table('failure_positions')->insert(array_map(function($n) use ($newPage) {
                    return [
                        'page_id' => $newPage->id,
                        'failure_id' => $n['id'],
                        'point' => $n['point'],
                        'point_sub' => $n['pointSub']
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

            // // create comments
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

