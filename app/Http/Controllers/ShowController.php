<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
// Models
use App\Models\Failure;
use App\Models\Process;
use App\Models\Inspection;
use App\Models\Vehicle;
use App\Models\InspectorGroup;
use App\Models\InspectionGroup;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ShowController
 * @package App\Http\Controllers
 */
class ShowController extends Controller
{
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

    public function showTableData(Request $request)
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
                                ->select(['id', 'inspection_group_id', 'line']);
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
                    'en' => $i->en,
                    'name' => $i->name,
                    'sort' => $i->sort,
                    'process' => $i->process_id,
                    'groups' => $i->groups
                ];
            });

        return ['data' => $inspections];
    }
}