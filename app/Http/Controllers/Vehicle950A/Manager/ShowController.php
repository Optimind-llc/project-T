<?php

namespace App\Http\Controllers\Vehicle950A\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Process;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ShowController
 * @package App\Http\Controllers\Vehicle950A\Manager
 */
class ShowController extends Controller
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
}
