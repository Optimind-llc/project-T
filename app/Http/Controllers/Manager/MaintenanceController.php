<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\InspectionGroup;
use App\Models\Failure;
use App\Models\Hole;
use App\Models\Figure;
use App\Models\Modification;
use App\Models\Inspector;
use App\Models\Client\FailurePosition;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MaintenanceController
 * @package App\Http\Controllers
 */
class MaintenanceController extends Controller
{
    public function inspector(Request $request)
    {
        $yomi = $request->yomi;
        $choku = $request->choku;
        $itionG = $request->itionG;
        $status = $request->status;

        $inspectors = Inspector::where(function($q) use ($yomi) {
            if ($yomi !== '') {
                $q->where('yomi', 'like', $yomi.'%');
            }
        })
        ->whereIn('group_code', $choku)
        ->whereIn('status', $status)
        ->with([
            'groups',
            'inspectionGroup' => function($q) {
                $q->select(['id']);
            }
        ]);

        if ($itionG !== 'all') {
            $inspectors = $inspectors->whereHas('inspectionGroup', function($q) use ($itionG) {
                $q->where('id', '=', $itionG);
            });
        }

        $inspectors = $inspectors->get()->map(function($i) {
            return [
                'id' => $i->id,
                'name' => $i->name,
                'yomi' => $i->yomi,
                'chokuName' => $i->groups->name,
                'chokuCode' => $i->groups->code,
                'status' => $i->status,
                'ig' => $i->inspectionGroup->map(function($ig) {
                    return [
                        'id' => $ig->id,
                        'sort' => $ig->pivot->sort
                    ];
                }),
            ];
        });

        return ['data' => $inspectors];
    }

    public function createInspector(Request $request)
    {
        $duplicate = Inspector::where('name', $request->name)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate inspector name'
            ], 400);
        }

        $inspector = new Inspector;
        $inspector->name = $request->name;
        $inspector->yomi = $request->yomi;
        $inspector->group_code = $request->choku;
        $inspector->save();

        foreach ($request->itionG as $ig) {
            $inspector->inspectionGroup()->attach($ig['id'], ['sort' => $ig['sort']]);
        }

        return ['message' => 'success'];
    }

    public function updateInspector(Request $request)
    {
        $duplicate = Inspector::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate inspector name'
            ], 400);
        }

        $inspector = Inspector::find($request->id);

        if ($inspector instanceof Inspector) {
            $inspector->name = $request->name;
            $inspector->yomi = $request->yomi;
            $inspector->group_code = $request->choku;
            $inspector->save();

            $inspector->inspectionGroup()->detach();
            foreach ($request->itionG as $ig) {
                $inspector->inspectionGroup()->attach($ig['id'], ['sort' => $ig['sort']]);
            }

            return ['message' => 'success'];
        }

        return ['message' => 'nothing to do'];
    }

    public function activateInspector($id)
    {
        $inspector = Inspector::find($id);

        if ($inspector instanceof Inspector) {
            $inspector->status = 1;
            $inspector->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }

    public function deactivateInspector($id)
    {
        $inspector = Inspector::find($id);

        if ($inspector instanceof Inspector) {
            $inspector->status = 0;
            $inspector->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }



    public function failures(Request $request)
    {
        $name = $request->name;
        $inspection = $request->inspection;
        $status = $request->status;

        $failures = Failure::whereIn('status', $status)
            ->where(function($q) use ($name) {
                if ($name !== '') {
                    $q->where('name', 'like', $name.'%');
                }
            });

        if ($inspection !== 'all') {
            $failures = $failures->whereHas('inspections', function($q) use ($inspection) {
                $q->where('id', '=', $inspection);
            });
        }

        $failures = $failures->orderBy('label')->get()->map(function($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'label' => $f->label,
                'status' => $f->status,
                'inspections' => $f->inspections->map(function($i) {
                    return [
                        'id' => $i->id,
                        'type' => $i->pivot->type,
                        'sort' => $i->pivot->sort
                    ];
                })
            ];
        });

        return ['data' => $failures];
    }

    public function createFailure(Request $request)
    {
        $duplicate = Failure::where('name', $request->name)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 400);
        }

        $duplicate = Failure::where('label', $request->label)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure label'
            ], 400);
        }

        $maxFailures = [
            1  => [1 => 7,  2 => 14, 'name' => '成形工程:外観検査'],
            10 => [1 => 7,  2 => 14, 'name' => '穴あけ工程:外観検査'],
            3  => [1 => 10, 2 => 0,  'name' => '穴あけ工程:外観検査'],
            12 => [1 => 10, 2 => 0,  'name' => ''],
            11 => [1 => 0,  2 => 21, 'name' => '接着工程:簡易CF'],
            5  => [1 => 0,  2 => 21, 'name' => '接着工程:止水'],
            6  => [1 => 0,  2 => 21, 'name' => '接着工程:仕上'],
            7  => [1 => 0,  2 => 21, 'name' => '接着工程:検査'],
            8  => [1 => 0,  2 => 21, 'name' => ''],
            9  => [1 => 0,  2 => 21, 'name' => '接着工程:手直']
        ];

        DB::beginTransaction();
        $failure = new Failure;
        $failure->name = $request->name;
        $failure->label = $request->label;
        $failure->save();

        foreach ($request->inspections as $i) {

            $fi = DB::table('failure_inspection')
                ->where('inspection_id', $i['id'])
                ->where('type', $i['type'])
                ->count();

            if ($fi >= $maxFailures[$i['id']][$i['type']]) {
                DB::rollBack();
                return \Response::json([
                    'status' => 'error',
                    'message' => 'over limit of failures',
                    'meta' => [
                        'inspection' => $maxFailures[$i['id']]['name'],
                        'limit' => $maxFailures[$i['id']][$i['type']]
                    ]
                ], 400);
            }

            $failure->inspections()->attach($i['id'], [
                'sort' => $i['sort'],
                'type' => $i['type']
            ]);
        }
        DB::commit();

        return ['message' => 'success'];
    }

    public function updateFailure(Request $request)
    {
        $duplicate = Failure::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 400);
        }

        $duplicate = Failure::where('id', '!=', $request->id)
            ->where('label', $request->label)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure label'
            ], 400);
        }

        $failure = Failure::find($request->id);

        $maxFailures = [
            1  => [1 => 7,  2 => 14, 'name' => '成形工程:外観検査'],
            10 => [1 => 7,  2 => 14, 'name' => '穴あけ工程:外観検査'],
            3  => [1 => 10, 2 => 0,  'name' => '穴あけ工程:外観検査'],
            12 => [1 => 10, 2 => 0,  'name' => ''],
            11 => [1 => 0,  2 => 21, 'name' => '接着工程:簡易CF'],
            5  => [1 => 0,  2 => 21, 'name' => '接着工程:止水'],
            6  => [1 => 0,  2 => 21, 'name' => '接着工程:仕上'],
            7  => [1 => 0,  2 => 21, 'name' => '接着工程:検査'],
            8  => [1 => 0,  2 => 21, 'name' => ''],
            9  => [1 => 0,  2 => 21, 'name' => '接着工程:手直']
        ];

        if ($failure instanceof Failure) {
            DB::beginTransaction();
            $failure->name = $request->name;
            $failure->label = $request->label;
            $failure->save();

            $failure->inspections()->detach();
            foreach ($request->inspections as $i) {
                $fi = DB::table('failure_inspection')
                    ->where('inspection_id', $i['id'])
                    ->where('type', $i['type'])
                    ->count();

                if ($fi >= $maxFailures[$i['id']][$i['type']]) {
                    DB::rollBack();
                    return \Response::json([
                        'status' => 'error',
                        'message' => 'over limit of failures',
                        'meta' => [
                            'inspection' => $maxFailures[$i['id']]['name'],
                            'limit' => $maxFailures[$i['id']][$i['type']]
                        ]
                    ], 400);
                }

                $failure->inspections()->attach($i['id'], [
                    'sort' => $i['sort'],
                    'type' => $i['type']
                ]);
            }
            DB::commit();

            return ['message' => 'success'];
        }

        return ['message' => 'nothing to do'];
    }

    public function activateFailure($id)
    {
        $failure = Failure::find($id);

        if ($failure instanceof Failure) {
            $failure->status = 1;
            $failure->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }

    public function deactivateFailure($id)
    {
        $failure = Failure::find($id);

        if ($failure instanceof Failure) {
            $failure->status = 0;
            $failure->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }



    public function modifications(Request $request)
    {
        $name = $request->name;
        $inspection = $request->inspection;
        $status = $request->status;

        $modifications = Modification::whereIn('status', $status)
            ->where(function($q) use ($name) {
                if ($name !== '') {
                    $q->where('name', 'like', $name.'%');
                }
            });

        if ($inspection !== 'all') {
            $modifications = $modifications->whereHas('inspections', function($q) use ($inspection) {
                $q->where('id', '=', $inspection);
            });
        }

        $modifications = $modifications->orderBy('label')->get()->map(function($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'label' => $f->label,
                'status' => $f->status,
                'inspections' => $f->inspections->map(function($i) {
                    return [
                        'id' => $i->id,
                        'type' => $i->pivot->type,
                        'sort' => $i->pivot->sort
                    ];
                })
            ];
        });

        return ['data' => $modifications];
    }

    public function createModification(Request $request)
    {
        $duplicate = Modification::where('name', $request->name)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 400);
        }

        $duplicate = Modification::where('label', $request->label)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure label'
            ], 400);
        }

        $maxModifications = [
            11 => [1 => 1,  2 => 11, 'name' => '接着工程:簡易CF'],
            5  => [1 => 1,  2 => 11, 'name' => '接着工程:止水'],
            6  => [1 => 1,  2 => 11, 'name' => '接着工程:仕上'],
            7  => [1 => 1,  2 => 11, 'name' => '接着工程:検査'],
            8  => [1 => 1,  2 => 11, 'name' => ''],
            9  => [1 => 1,  2 => 11, 'name' => '接着工程:手直']
        ];

        DB::beginTransaction();
        $failure = new Modification;
        $failure->name = $request->name;
        $failure->label = $request->label;
        $failure->save();

        foreach ($request->inspections as $i) {
            $mi = DB::table('modification_inspection')
                ->where('inspection_id', $i['id'])
                ->where('type', $i['type'])
                ->count();

            if ($mi >= $maxModifications[$i['id']][$i['type']]) {
                DB::rollBack();
                return \Response::json([
                    'status' => 'error',
                    'message' => 'over limit of failures',
                    'meta' => [
                        'inspection' => $maxModifications[$i['id']]['name'],
                        'limit' => $maxModifications[$i['id']][$i['type']]
                    ]
                ], 400);
            }

            $failure->inspections()->attach($i['id'], [
                'sort' => $i['sort'],
                'type' => $i['type']
            ]);
        }
        DB::commit();

        return ['message' => 'success'];
    }

    public function updateModification(Request $request)
    {
        $duplicate = Modification::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 400);
        }

        $duplicate = Modification::where('id', '!=', $request->id)
            ->where('label', $request->label)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure label'
            ], 400);
        }

        $modification = Modification::find($request->id);

        $maxModifications = [
            11 => ['limit' => 12, 'name' => '接着工程:簡易CF'],
            5  => ['limit' => 12, 'name' => '接着工程:止水'],
            6  => ['limit' => 12, 'name' => '接着工程:仕上'],
            7  => ['limit' => 12, 'name' => '接着工程:検査'],
            8  => ['limit' => 12, 'name' => ''],
            9  => ['limit' => 12, 'name' => '接着工程:手直']
        ];

        if ($modification instanceof Modification) {
            DB::beginTransaction();
            $modification->name = $request->name;
            $modification->label = $request->label;
            $modification->save();

            $modification->inspections()->detach();
            foreach ($request->inspections as $i) {
                $mi = DB::table('modification_inspection')
                    ->where('inspection_id', $i['id'])
                    ->where('type', $i['type'])
                    ->count();

                if ($mi >= $maxModifications[$i['id']]['limit']) {
                    DB::rollBack();
                    return \Response::json([
                        'status' => 'error',
                        'message' => 'over limit of failures',
                        'meta' => [
                            'inspection' => $maxModifications[$i['id']]['name'],
                            'limit' => $maxModifications[$i['id']][$i['type']]
                        ]
                    ], 400);
                }

                $modification->inspections()->attach($i['id'], [
                    'sort' => $i['sort'],
                    'type' => $i['type']
                ]);
            }
            DB::commit();

            return ['message' => 'success'];
        }

        return ['message' => 'nothing to do'];
    }

    public function activateModification($id)
    {
        $failure = Modification::find($id);

        if ($failure instanceof Modification) {
            $failure->status = 1;
            $failure->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }

    public function deactivateModification($id)
    {
        $failure = Modification::find($id);

        if ($failure instanceof Modification) {
            $failure->status = 0;
            $failure->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }


    public function holes(Request $request)
    {
        $figureId = $request->figureId;
        $status = $request->status;

        $holes = Hole::where('figure_id', '=', $figureId)
            ->whereIn('status', $status)
            ->orderBy('label')
            ->with(['partType' => function($q) {
                $q->select(['id', 'name']);
            }])
            ->get()
            ->map(function($h) {
                return [
                    'id' => $h->id,
                    'label' => $h->label,
                    'border' => $h->border,
                    'color' => $h->color,
                    'direction' => $h->direction,
                    'point' => $h->point,
                    'shape' => $h->shape,
                    'status' => $h->status,
                    'partName' => $h->partType->name
                ];
            })
            ->sortBy('partName')
            ->values();

        $path = Figure::find($figureId)->path;

        return ['data' => [
            'path' => '/img/figures/'.$path,
            'holes' => $holes
        ]];
    }

    public function activateHole($id)
    {
        $hole = Hole::find($id);

        if ($hole instanceof Hole) {
            $hole->status = 1;
            $hole->save();

            return ['message' => 'success'];
        }

        return ['message' => 'nothing to do'];
    }

    public function deactivateHole($id)
    {
        $hole = Hole::find($id);

        if ($hole instanceof Hole) {
            $hole->status = 0;
            $hole->save();

            return ['message' => 'success'];
        }

        return ['message' => 'nothing to do'];
    }

    public function updateHole(Request $request)
    {
        $x = $request->point[0];
        $y = $request->point[1];
        $point = $x.','.$y;

        $hole = Hole::find($request->id);
        $hole->label = $request->label;
        $hole->point = $point;
        $hole->direction = $request->direction;
        $hole->shape = $request->shape;
        $hole->border = $request->border;
        $hole->color = $request->color;
        $hole->save();

        return ['message' => 'nothing to do'];
    }
}
