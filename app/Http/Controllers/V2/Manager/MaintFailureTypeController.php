<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\Worker;
use App\Models\Vehicle950A\Failure;
use App\Models\Vehicle950A\Modification;
use App\Models\Vehicle950A\Hole;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MaintFailureTypeController
 * @package App\Http\Controllers\V2\Manager
 */
class MaintFailureTypeController extends Controller
{
    public function get(Request $request)
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
}
