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
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\HoleTypeRepository;
use App\Repositories\InlineTypeRepository;
// Services
use App\Services\Vehicle950A\GeneratePDF;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReportController
 * @package App\Http\Controllers\V2\Manager
 */
class ReportController extends Controller
{
    protected $inspectionResult;
    protected $failureType;
    protected $modificationType;
    protected $holeModificationType;
    protected $holeType;
    protected $inlineType;

    public function __construct (
        InspectionResultRepository $inspectionResult,
        FailureTypeRepository $failureType,
        ModificationTypeRepository $modificationType,
        HoleModificationTypeRepository $holeModificationType,
        HoleTypeRepository $holeType,
        InlineTypeRepository $inlineType
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->failureType = $failureType;
        $this->modificationType = $modificationType;
        $this->holeModificationType = $holeModificationType;
        $this->holeType = $holeType;
        $this->inlineType = $inlineType;
    }

    public function check($vehicle, Request $request)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $request->date.' 00:00:00');

        $choku = new Choku($date_obj);
        $start = $choku->getDayStart();
        $end = $choku->getDayEnd();

        $irs = $this->inspectionResult
            ->forCheckReport($request->p, 1, $start, $end, $request->choku)
            ->toArray();

        return [
            'message' => 'success',
            'data' => $irs
        ];
    }

    public function export($vehicle, $process, $inspection, $line, $pn, $date, $choku)
    {
        DB::connection('950A')->enableQueryLog();

        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
        $choku_obj = new Choku($date_obj);
        $start = $choku_obj->getDayStart();
        $end = $choku_obj->getDayEnd();

        $irs = $this->inspectionResult->listForReport($process, $inspection, $line, $pn, $start, $end, $choku);
        // return $irs;

        $ft_ids = $irs->map(function($ir){
            return $ir['ft_ids'];
        })->flatten()->unique();
        $failureTypes = $this->failureType->getByIds($ft_ids);

        $mt_ids = $irs->map(function($ir){
            return $ir['mt_ids'];
        })->flatten()->unique();
        $modificationTypes = $this->modificationType->getByIds($mt_ids);

        $hmt_ids = $irs->map(function($ir){
            return $ir['hmt_ids'];
        })->flatten()->unique();
        $holeModificationTypes = $this->holeModificationType->getByIds($hmt_ids);

        $holeTypes = [];
        if ($inspection === 'ana' || $inspection === 'kashimego') {
            $holeTypes = $this->holeType->getAllByPns([$pn], $inspection);
        }

        $inlineTypes = [];
        if ($inspection === 'inline') {
            $inlineTypes = $this->inlineType->getAllByPns([$pn]);
        }

        $pdf_path = 'report_'.$line.'_'.$date.'_'.$choku;
        $pdf = new GeneratePDF($vehicle, $process, $inspection, $pn, $line, $date, $choku);
        $pdf->setFailures($failureTypes, $modificationTypes, $holeModificationTypes, $holeTypes, $inlineTypes);

        if ($process == 'molding' && $inspection == 'gaikan') {
            $partIds = $irs->map(function($ir) {
                return $ir['part_id'];
            });
            $inlineStatus = $this->inspectionResult->getInlineStatusByPartIds($partIds);

            // return $pdf->generateGaikanWithInline($irs, $inlineStatus);
            $pdf->generateGaikanWithInline($irs, $inlineStatus)->output($pdf_path, 'I');
        }
        else if ($inspection == 'ana' || $inspection == 'kashimego') {
            // return $pdf->generateForHole($irs);
            $pdf->generateForHole($irs)->output($pdf_path, 'I');
        }
        else if ($inspection == 'inline') {
            // return $pdf->generateForInline($irs);
            $pdf->generateForInline($irs)->output($pdf_path, 'I');
        }
        else {
            $pdf->generate($irs)->output($pdf_path, 'I');
        }
    }
}
