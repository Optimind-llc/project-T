<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\Worker;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MaintWorkerController
 * @package App\Http\Controllers\V2\Manager
 */
class MaintWorkerController extends Controller
{
    public function get($vhicle)
    {
        $failureTypes = Worker::with('inspections')
            ->get()
            ->map(function($ft) {
                return [
                    'id' => $ft->id,
                    'name' => $ft->name,
                    'yomi' => $ft->yomi,
                    'choku' => $ft->choku_code,
                    'status' => $ft->status,
                    'inspections' => $ft->inspections->map(function($i) {
                        return [
                            'i' => $i->en,
                            'p' => $i->pivot->process,
                            'd' => $i->pivot->division,
                            'sort' => $i->pivot->sort
                        ];
                    })
                ];
            });

        return [
            'data' => $failureTypes,
            'message' => 'success'
        ];
    }

    public function create(Request $request)
    {
        $duplicate = Worker::where('name', $request->name)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 200);
        }

        DB::beginTransaction();
        $failure = new Worker;
        $failure->code = 0;
        $failure->name = $request->name;
        $failure->yomi = $request->yomi;
        $failure->choku_code = $request->choku;
        $failure->save();

        foreach ($request->inspections as $i) {
            $failure->inspections()->attach($i['i'], [
                'process' => $i['p'],
                'division' => $i['d'],
                'sort' => $i['sort']
            ]);
        }
        DB::commit();

        return ['message' => 'success'];
    }

    public function update(Request $request)
    {
        $duplicate = Worker::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 200);
        }

        $failure = Worker::find($request->id);

        if ($failure instanceof Worker) {
            DB::beginTransaction();
            $failure->name = $request->name;
            $failure->yomi = $request->yomi;
            $failure->choku_code = $request->choku;
            $failure->save();

            $failure->inspections()->detach();
            foreach ($request->inspections as $i) {
                $failure->inspections()->attach($i['i'], [
                    'process' => $i['p'],
                    'division' => $i['d'],
                    'sort' => $i['sort']
                ]);
            }
            DB::commit();

            return ['message' => 'success'];
        }

        return ['message' => 'nothing to do'];
    }

    public function activate($id)
    {
        $failure = Failure::find($id);

        if ($failure instanceof Failure) {
            $failure->status = 1;
            $failure->save();

            return ['message' => 'success'];
        }
        return ['message' => 'nothing to do'];
    }

    public function deactivate($id)
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
