<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Vehicle950A\Choku;
// Models
use App\Models\Vehicle950A\Worker;
// Repositories
use App\Repositories\InspectionResultRepository;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReportController
 * @package App\Http\Controllers\V2\Manager
 */
class ReportController extends Controller
{
    protected $inspectionResult;

    public function __construct (InspectionResultRepository $inspectionResult)
    {
        $this->inspectionResult = $inspectionResult;
    }

    public function check($date)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');

        return [
            'message' => 'success',
            'data' => 'aaa'
        ];
    }

    public function export($process, $inspection, $line, $pn, $date, $choku)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
        $choku_obj = new Choku($date_obj);
        $start = $choku_obj->getDayStart();
        $end = $choku_obj->getDayEnd();

        return $this->inspectionResult->listForReport($process, $inspection, $line, $pn, $start, $end, $choku)->toArray();
    }
}
