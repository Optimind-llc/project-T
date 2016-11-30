<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TCPDF;
use App\Report;
// Models
use App\Models\PartType;
use App\Models\InspectionGroup;
use App\Models\InspectorGroup;
use App\Models\Client\Part;
// Exceptions
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReportController
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{
    public function report($itionGId, $date, $itorG)
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
        $start = $date->addHours(6)->addMinutes(30);
        $end = $date->copy()->addDay(1);
        $itorG = [InspectorGroup::find($itorG)->name];

        if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
            $parts = Part::join('part_page as pp', 'pp.part_id', '=', 'parts.id')
                ->join('pages as pg', 'pg.id', '=', 'pp.page_id')
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->where('if.inspected_at', '>=', $start)
                        ->where('if.inspected_at', '<', $end)
                        ->where('inspection_group_id', '=', $itionGId)
                        ->where('inspector_group', '=', '不明');
                })
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.inspected_at'])
                ->with(['inlines'])
                ->get()
                ->sortByDesc('inspected_at')
                ->groupBy('panel_id')
                ->map(function($part) { return $part->first(); })
                ->sortBy('inspected_at')
                ->values();

        }
        elseif ($itionGId == 1 || $itionGId == 2 || $itionGId == 5 || $itionGId == 6 || $itionGId == 15) {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->where('if.updated_at', '>=', $start)
                        ->where('if.updated_at', '<', $end)
                        ->where('if.inspection_group_id', '=', $itionGId)
                        ->whereIn('if.inspector_group', $itorG);
                })
                ->select(['parts.*', 'pp.status', 'pp.comment as pp_comment', 'pg.page_type_id', 'pg.family_id', 'if.comment', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at'])
                ->with([
                    'failurePositions' => function($q) use ($itionGId, $itorG) {
                        $q->join('pages as pg', 'pg.id', '=', 'failure_positions.page_id')
                            ->join('inspection_families as if', function($join) use ($itionGId, $itorG) {
                                $join->on('if.id', '=', 'pg.family_id')
                                    ->where('inspection_group_id', '=', $itionGId)
                                    ->whereIn('inspector_group', $itorG);
                            })
                            ->select(['failure_positions.id','page_id', 'part_id', 'failure_id']);
                    },
                    'pages' => function($q) {
                        $q->join('inspection_families as if', 'pages.family_id', '=', 'if.id')
                            ->join('inspection_groups as ig', 'if.inspection_group_id', '=', 'ig.id')
                            ->join('inspections as i', function ($join) {
                                $join->on('ig.inspection_id', '=', 'i.id')->where('i.en', '=', 'inline');
                            })
                            ->orderBy('if.inspected_at', 'desc')
                            ->select(['pages.*', 'if.inspected_at', 'i.en']);
                    }
                ])
                ->get();
        }
        elseif ($itionGId == 4 || $itionGId == 8) {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->where('if.updated_at', '>=', $start)
                        ->where('if.updated_at', '<', $end)
                        ->where('if.inspection_group_id', '=', $itionGId)
                        ->whereIn('if.inspector_group', $itorG);
                })
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'pg.family_id', 'if.comment', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at'])
                ->with([
                    'failurePositions' => function($q) use ($itionGId, $itorG) {
                        $q->join('pages as pg', 'pg.id', '=', 'failure_positions.page_id')
                            ->join('inspection_families as if', function($join) use ($itionGId, $itorG) {
                                $join->on('if.id', '=', 'pg.family_id')
                                    ->where('inspection_group_id', '=', $itionGId)
                                    ->whereIn('inspector_group', $itorG);
                            })
                            ->select(['failure_positions.id','page_id', 'part_id', 'failure_id']);
                    },
                    'pages' => function($q) use ($start, $end, $itionGId, $itorG) {
                        $q->join('inspection_families as if', function ($join) use ($start, $end, $itionGId, $itorG) {
                            $join->on('pages.family_id', '=', 'if.id')
                                ->where('if.updated_at', '>=', $start)
                                ->where('if.updated_at', '<', $end)
                                ->whereIn('inspector_group', $itorG)
                                ->where('if.inspection_group_id', '=', $itionGId);
                        })
                        ->select(['pages.*', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.status']);
                    },
                    'pages.holePages' => function($q) {
                        $q->join('holes', 'hole_page.hole_id', '=', 'holes.id')
                            ->select('hole_page.*')
                            ->get();
                    },
                    'pages.holePages.hole' => function($q) {
                        $q->select(['id', 'label', 'part_type_id']);
                    },
                    'pages.holePages.holeModification' => function($q) {
                        $q->select(['hole_modifications.id', 'name']);
                    }
                ])
                ->get();
        }
        elseif ($itionGId == 16 || $itionGId == 10 || $itionGId == 11 || $itionGId == 12 || $itionGId == 14) {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->where('if.updated_at', '>=', $start)
                        ->where('if.updated_at', '<', $end)
                        ->where('if.inspection_group_id', '=', $itionGId)
                        ->whereIn('if.inspector_group', $itorG);
                })
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'pg.family_id', 'if.comment', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at'])
                ->with([
                    'failurePositions' => function($q) use ($itionGId, $itorG) {
                        $q->join('pages as pg', 'pg.id', '=', 'failure_positions.page_id')
                            ->join('inspection_families as if', function($join) use ($itionGId, $itorG) {
                                $join->on('if.id', '=', 'pg.family_id')
                                    ->where('inspection_group_id', '=', $itionGId)
                                    ->whereIn('inspector_group', $itorG);
                            })
                            ->select(['failure_positions.id','page_id', 'part_id', 'failure_id']);
                    },
                    'pages' => function($q) use ($start, $end, $itionGId, $itorG) {
                        $q->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                            $join->on('if.id', '=', 'pages.family_id')
                                ->where('if.updated_at', '>=', $start)
                                ->where('if.updated_at', '<', $end)
                                ->where('if.inspection_group_id', '=', $itionGId)
                                ->whereIn('if.inspector_group', $itorG);
                        })->select(['pages.*']);
                    },
                    'pages.comments',
                    'partType'
                ])
                ->get();
        }

        if ($parts->count() == 0) {
            $tcpdf = new TCPDF;
            $tcpdf->AddPage('L', 'A4');
            $tcpdf->SetFont('kozgopromedium', '', 16);
            $tcpdf->Text(130, 80, '検索結果なし');
            $pdf_path = 'no_data.pdf';
            $tcpdf->output($pdf_path, 'I');
        }

        $now = Carbon::now();
        $inspectionGroup = InspectionGroup::find($itionGId);
        $vehicle = $inspectionGroup->vehicle_num;
        $line = $inspectionGroup->line;
        $ition_name = $inspectionGroup->inspection->name;
        $process_name = $inspectionGroup->inspection->process->name;
        $division = $inspectionGroup->division->name;

        $report = new Report;
        $report->setInfo($vehicle, $process_name, $ition_name, $line, $division, $date, implode(',', $itorG));
        $report->setFailureTypes($inspectionGroup->sortedFailures());
        $report->setModificationTypes($inspectionGroup->sortedModifications());

        switch ($itionGId) {
            case 1:
                $tcpdf = $report->forGaikan($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_gaikan_inner';
                break;
            case 2: 
                $tcpdf = $report->forGaikan($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m002_gaikan_inner';
                break;
            case 3:
                $tcpdf = $report->forInline($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_inline_inner';
                break;
            case 19:
                $tcpdf = $report->forInline($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m002_inline_inner';
                break;
            case 5:
                $tcpdf = $report->forGaikan($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_outer';
                break;
            case 6:
                $tcpdf = $report->forGaikan($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m002_outer';
                break;
            case 4:
                $tcpdf = $report->forAnaSingle($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_inner';
                break;
            case 8:
                $tcpdf = $report->forAnaMulti($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_outer';
                break;
            case 9:
                $tcpdf = $report->forInline($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j001_inline_inner';
                break;
            case 15:
                $tcpdf = $report->forAnaGaikan($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_gaikan_inner';
                break;
            case 16:
                $tcpdf = $report->forJointing($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j_kanicf_assy';
                break;
            case 10:
                $tcpdf = $report->forJointing($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j_shisui_assy';
                break;
            case 11:
                $tcpdf = $report->forJointing($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j_shiage_assy';
                break;
            case 12:
                $tcpdf = $report->forJointing($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j_kensa_assy';
                break;
            case 14:
                $tcpdf = $report->forJointing($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j_tenaoshi_assy';
                break;
        }

// return $tcpdf;

        $tcpdf->output($pdf_path, 'I');
    }

    public function checkReport($itionGId, $date, $itorG_code)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
        $itorG_name = InspectorGroup::find($itorG_code)->name;

        $families = InspectionGroup::find($itionGId)
            ->families()
            ->whereIn('inspector_group', [$itorG_name, '不明'])
            ->where('created_at', '>=', $date_obj->addHours(2))
            ->where('created_at', '<', $date_obj->copy()->addDay(2))
            ->get()
            ->count();

        return $families;
    }
}

