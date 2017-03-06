<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Repositories
use App\Repositories\InspectionResultRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\HoleTypeRepository;
// Services
use App\Services\Vehicle950A\Choku;
// Models
use App\Models\Vehicle950A\Figure;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MappingController
 * @package App\Http\Controllers\V2\Manager
 */
class MappingController extends Controller
{
    protected $inspectionResult;
    protected $failureType;
    protected $modificationTypes;
    protected $holeModificationTypes;
    protected $holeType;


    public function __construct (
        InspectionResultRepository $inspectionResult,
        FailureTypeRepository $failureType,
        ModificationTypeRepository $modificationTypes,
        HoleModificationTypeRepository $holeModificationTypes,
        HoleTypeRepository $holeType
    )
    {
        $this->inspectionResult = $inspectionResult;
        $this->failureType = $failureType;
        $this->modificationTypes = $modificationTypes;
        $this->holeModificationTypes = $holeModificationTypes;
        $this->holeType = $holeType;
    }

    public function realtime($vehicle, Request $request)
    {
        $p = $request->p;
        $i = $request->i;
        $line = 1;
        $pn = $request->pt;

        $choku = new Choku(Carbon::today());
        $start = $choku->getLastChokuStart();
        $end = Carbon::now();

        $chokus = ['W', 'Y', 'B', 'NA'];

        $irs = $this->inspectionResult->forMappingByDate($p, $i, $line, $pn, $start, $end, $chokus);

        $failureTypes = $this->failureType->getByIds($irs['ft_ids']);
        $modificationTypes = $this->failureType->getByIds($irs['mt_ids']);
        $holeModificationTypes = $this->failureType->getByIds($irs['hmt_ids']);

        $figures = Figure::where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->where('pt_pn', '=', $pn)
            ->orderBy('page')
            ->select(['id', 'page', 'path'])
            ->get()
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'page' => $f->page,
                    'path' => '/img/figures/950A/'.$f->path
                ];
            });

        $holeTypes = $this->holeType->getAllByPn($pn);

        return [
            'data' => [
                'count' => $irs['count'],
                'result' => $irs['result'],
                'figures' => $figures,
                'failureTypes' => $failureTypes,
                'modificationTypes' => $modificationTypes,
                'holeModificationTypes' => $holeModificationTypes,
                'holeTypes' => $holeTypes
            ]
        ];
    }

    public function byDate($vehicle, Request $request)
    {
        $process = $request->p;
        $inspection = $request->i;
        $partType = $request->pt;

        return $request->all();
    }

    public function byPanelId($vehicle, Request $request)
    {
        $process = $request->p;
        $inspection = $request->i;
        $partType = $request->pt;

        return $request->all();
    }
}
