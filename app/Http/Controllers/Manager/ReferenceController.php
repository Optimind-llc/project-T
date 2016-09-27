<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
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
        $domain = env('APP_URL');
        $env = env('APP_ENV');

        return view('manager.index', compact('env', 'domain'));
    }

    /**
     * Get user from JWT token
     */
    public function inspection(Request $request)
    {
        return $family;
    }
    
}