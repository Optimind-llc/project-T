<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\Choku;
use App\Models\Vehicle950A\Process;
use App\Models\Vehicle950A\Inspection;
use App\Models\Vehicle950A\PartType;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class InitialController
 * @package App\Http\Controllers\Press\Manager
 */
class InitialController extends Controller
{
    public function index($vehicle)
    {
        if ($vehicle === '950A') {
            $hotReload = env('HOT_RELOAD');
            $domain = env('APP_URL');

            return view('manager.index', compact('hotReload', 'domain'));
        }
    }

    public function all()
    {
        $chokus = Choku::select(['code', 'name', 'status'])->orderBy('name')->get();
        $processes = Process::select(['en', 'name'])->orderBy('sort')->get();
        $inspections = Inspection::select(['en', 'name'])->orderBy('sort')->get();
        $partTypes = PartType::select(['pn', 'en', 'name'])->orderBy('sort')->get();
        $combination = collect(config('part.950A'));
        $combination2 = collect(config('part.950AMapping'));

        return [
            'data' => [
                'chokus' => $chokus,
                'processes' => $processes,
                'inspections' => $inspections,
                'partTypes' => $partTypes,
                'combination' => $combination,
                'combination2' => $combination2
            ]
        ];
    }
}
