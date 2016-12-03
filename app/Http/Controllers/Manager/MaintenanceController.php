<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\InspectionGroup;
use App\Models\Failure;
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
    public function failure(Request $request)
    {
        $failures = Failure::with(['inspections'])
            ->get()
            ->map(function($f) {
                return [
                    'name' => $f->name,
                    'label' => $f->label,
                    'inspections' => $f->inspections
                ];
            });

        return ['data' => $failures];
    }

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
}
