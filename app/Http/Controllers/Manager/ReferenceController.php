<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle;
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
 * Class ReferenceController
 * @package App\Http\Controllers
 */
class ReferenceController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $hotReload = env('HOT_RELOAD');
        $domain = env('APP_URL');

        return view('manager.index', compact('hotReload', 'domain'));
    }

    /**
     * Get plint list
     */
    public function getPrintList(Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'vehicle' => ['required', 'alpha_num'],
                'group' => ['required', 'alpha_num'],
                'date' => ['required', 'alpha_num']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $date = Carbon::createFromFormat('Y,m,d', '2016,9,30')->today();
// return $date->addDay();
        $inspection_group = InspectionGroup::with(['families'])
            ->where('created_at', '>=', $date)
            ->where('created_at', '<', $date->copy()->addDay(1))
            ->get();

        return $inspection_group;
    }   
}
