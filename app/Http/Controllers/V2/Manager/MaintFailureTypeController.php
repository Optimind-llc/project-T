<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\FailureType;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MaintFailureTypeController
 * @package App\Http\Controllers\V2\Manager
 */
class MaintFailureTypeController extends Controller
{
    public function get($vhicle)
    {
        $failureTypes = FailureType::with('inspections')
            ->get()
            ->map(function($ft) {
                return [
                    'id' => $ft->id,
                    'name' => $ft->name,
                    'label' => $ft->label,
                    'status' => $ft->status,
                    'inspections' => $ft->inspections->map(function($i) {
                        return [
                            'i' => $i->en,
                            'p' => $i->pivot->process,
                            'd' => $i->pivot->division,
                            'type' => $i->pivot->type,
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
        $duplicate = FailureType::where('name', $request->name)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 200);
        }

        $duplicate = FailureType::where('label', $request->label)->get()->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure label'
            ], 200);
        }

        $maxFailures = [
            'molding' => [
                'gaikan' => 21
            ],
            'holing' => [
                'maegaikan' => 21,
                'atogaikan' => 21,
                'ana' => 21,
                'tenaoshi' => 21
            ],
            'jointing' => [
                'kashimego' => 21,
                'gaishushiage' => 21,
                'pateshufukugo' => 21,
                'suikengo' => 21,
                'tosoukeirego' => 21,
                'setchakugo' => 21,
                'gaikan' => 21,
                'tenaoshi' => 21
            ]
        ];

        DB::beginTransaction();
        $failure = new FailureType;
        $failure->name = $request->name;
        $failure->label = $request->label;
        $failure->save();

        foreach ($request->inspections as $i) {

            $fi = DB::connection('950A')
                ->table('ft_related')
                ->where('process', $i['p'])
                ->where('inspection', $i['i'])
                ->where('division', $i['d'])
                ->count();

            if ($fi >= $maxFailures[$i['p']][$i['i']]) {
                DB::rollBack();
                return \Response::json([
                    'status' => 'error',
                    'message' => 'over limit of failures',
                    'meta' => [
                        'limit' => $maxFailures[$i['p']][$i['i']],
                        'p' => $i['p'],
                        'i' => $i['i'],
                        'd' => $i['d'],
                    ]
                ], 200);
            }

            $failure->inspections()->attach($i['i'], [
                'process' => $i['p'],
                'division' => $i['d'],
                'sort' => $i['sort'],
                'type' => $i['type']
            ]);
        }
        DB::commit();

        return ['message' => 'success'];
    }

    public function update(Request $request)
    {
        $duplicate = FailureType::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure name'
            ], 200);
        }

        $duplicate = FailureType::where('id', '!=', $request->id)
            ->where('label', $request->label)
            ->get()
            ->count();

        if ($duplicate > 0) {
            return \Response::json([
                'status' => 'error',
                'message' => 'duplicate failure label'
            ], 200);
        }

        $failure = FailureType::find($request->id);

        $maxFailures = [
            'molding' => [
                'gaikan' => 21
            ],
            'holing' => [
                'maegaikan' => 21,
                'atogaikan' => 21,
                'ana' => 21,
                'tenaoshi' => 21
            ],
            'jointing' => [
                'kashimego' => 21,
                'gaishushiage' => 21,
                'pateshufukugo' => 21,
                'suikengo' => 21,
                'tosoukeirego' => 21,
                'setchakugo' => 21,
                'gaikan' => 21,
                'tenaoshi' => 21
            ]
        ];

        if ($failure instanceof FailureType) {
            DB::beginTransaction();
            $failure->name = $request->name;
            $failure->label = $request->label;
            $failure->save();

            $failure->inspections()->detach();
            foreach ($request->inspections as $i) {
                $fi = DB::connection('950A')
                    ->table('ft_related')
                    ->where('process', $i['p'])
                    ->where('inspection', $i['i'])
                    ->where('division', $i['d'])
                    ->count();

                if ($fi >= $maxFailures[$i['p']][$i['i']]) {
                    DB::rollBack();
                    return \Response::json([
                        'status' => 'error',
                        'message' => 'over limit of failures',
                        'meta' => [
                            'limit' => $maxFailures[$i['p']][$i['i']],
                            'p' => $i['p'],
                            'i' => $i['i'],
                            'd' => $i['d'],
                        ]
                    ], 200);
                }

                $failure->inspections()->attach($i['i'], [
                    'process' => $i['p'],
                    'division' => $i['d'],
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
