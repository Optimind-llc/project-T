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
                $processes = Process::select(['en as c', 'name as n'])->get();
                break;

            case 'withFailure':
                $processes = Process::with([
                    'failures' => function($q) {
                        $q->select('id', 'name', 'sort')->get();
                    }])
                    ->select(['en as c', 'name as n'])
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
                'inspectorG' => ['required', 'alpha']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $vehicle = $request->vehicle;
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $inspectorG = $request->inspectorG;

        $inspections = Inspection::with([
                'groups' => function ($q) use ($vehicle, $date) {
                    $q->with([
                        'families' => function ($q) use ($date) {
                            $q->where('created_at', '>=', $date->addHours(1))
                                ->where('created_at', '<', $date->copy()->addDay(1))
                                ->select(['id', 'inspection_group_id']);
                        }
                    ])
                    ->where('vehicle_num', $vehicle);
                }
            ])
            ->get();

        return ['data' => $inspections];
    }
}