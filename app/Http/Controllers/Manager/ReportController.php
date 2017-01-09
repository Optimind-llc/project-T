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
use App\Models\Client\Page;
use App\Models\Client\Part;
use App\Models\Client\InspectionFamily;
// Exceptions
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
        $start = $date->copy()->addMinutes(15);
        $end = $date->copy()->addDay(1)->addHours(8)->addMinutes(30);
        $itorG = [InspectorGroup::find($itorG)->name];

        if ($itionGId == 3 || $itionGId == 9 || $itionGId == 19) {
            $itorGWithF = $itorG;
            array_push($itorGWithF, '不明');

            $parts = Page::join('inspection_families as if', function($join) use ($itionGId, $start, $end, $itorGWithF) {
                $join->on('pages.family_id', '=', 'if.id')
                    ->whereIn('if.inspector_group', $itorGWithF)
                    ->where('if.inspection_group_id', '=', $itionGId)
                    ->whereNull('if.deleted_at')
                    ->where('if.created_at', '>=', $start)
                    ->where('if.created_at', '<', $end);
            })
            ->join('part_page as pp', function($join) {
                $join->on('pages.id', '=', 'pp.page_id');
            })
            ->join('parts', function($join) {
                $join->on('parts.id', '=', 'pp.part_id');
            })
            ->orderBy('if.inspected_at', 'desc')
            ->select('pages.id', 'pages.page_type_id', 'if.inspector_group', 'if.inspected_at', 'if.status', 'if.created_at', 'pp.part_id', 'parts.panel_id')
            ->with(['inlines'])
            ->get()
            ->groupBy('part_id')
            ->map(function($p) {
                return $p->first();
            })
            ->values()
            ->sortBy('created_at');

            $count1 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(9)) && $p->created_at->lte($date->copy()->addHours(13));
            })->count();

            $count2 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(16)->addMinutes(30)) && $p->created_at->lte($date->copy()->addHours(21)->addMinutes(15));
            })->count();

            $count3 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(24)->addMinutes(45)) && $p->created_at->lte($date->copy()->addHours(29)->addMinutes(30));
            })->count();

            if ($count1 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return ($p->inspector_group == '不明' && $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay(1)->addHours(8)->addMinutes(30))) || ($p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30)));
                });
            }
            elseif ($count2 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return ($p->inspector_group == '不明' && $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay(1)->addHours(8)->addMinutes(30))) || ($p->created_at->gte($date->copy()->addHours(6)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30)));
                });
            }
            elseif ($count3 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return ($p->inspector_group == '不明' && $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay(1)->addHours(8)->addMinutes(30))) || ($p->created_at->gte($date->copy()->addHours(8)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(8)->addMinutes(30)));
                });
            }
            else {
                $parts = $parts->filter(function($p) use ($date) {
                    return false;
                });
            }
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
                        ->whereNull('if.deleted_at')
                        ->where('if.created_at', '>=', $start)
                        ->where('if.created_at', '<', $end)
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
                ->orderBy('if.created_at')
                ->get();

            $count1 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(9)) && $p->created_at->lte($date->copy()->addHours(13));
            })->count();

            $count2 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(16)->addMinutes(30)) && $p->created_at->lte($date->copy()->addHours(21)->addMinutes(15));
            })->count();

            $count3 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(24)->addMinutes(45)) && $p->created_at->lte($date->copy()->addDay()->addHours(6)->addMinutes(20));
            })->count();

            if ($count1 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count2 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count3 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(8)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(8)->addMinutes(30));
                });
            }
            else {
                $parts = $parts->filter(function($p) use ($date) {
                    return false;
                });
            }
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
                        ->whereNull('if.deleted_at')
                        ->where('if.created_at', '>=', $start)
                        ->where('if.created_at', '<', $end)
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
                                ->where('if.created_at', '>=', $start)
                                ->where('if.created_at', '<', $end)
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
                ->orderBy('if.created_at')
                ->get();

            $count1 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(9)) && $p->created_at->lte($date->copy()->addHours(13));
            })->count();

            $count2 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(16)->addMinutes(30)) && $p->created_at->lte($date->copy()->addHours(21)->addMinutes(15));
            })->count();

            $count3 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(24)->addMinutes(45)) && $p->created_at->lte($date->copy()->addHours(29)->addMinutes(30));
            })->count();

            if ($count1 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count2 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count3 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(8)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(8)->addMinutes(30));
                });
            }
            else {
                $parts = $parts->filter(function($p) use ($date) {
                    return false;
                });
            }
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
                        ->whereNull('if.deleted_at')
                        ->where('if.created_at', '>=', $start)
                        ->where('if.created_at', '<', $end)
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
                                ->where('if.created_at', '>=', $start)
                                ->where('if.created_at', '<', $end)
                                ->where('if.inspection_group_id', '=', $itionGId)
                                ->whereIn('if.inspector_group', $itorG);
                        })->select(['pages.*']);
                    },
                    'pages.comments',
                    'partType'
                ])
                ->orderBy('if.created_at')
                ->get();

            $count1 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(9)) && $p->created_at->lte($date->copy()->addHours(13));
            })->count();

            $count2 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(16)->addMinutes(30)) && $p->created_at->lte($date->copy()->addHours(21)->addMinutes(15));
            })->count();

            $count3 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(24)->addMinutes(45)) && $p->created_at->lte($date->copy()->addHours(29)->addMinutes(30));
            })->count();

            if ($count1 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count2 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count3 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(8)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(8)->addMinutes(30));
                });
            }
            else {
                $parts = $parts->filter(function($p) use ($date) {
                    return false;
                });
            }
        }
        elseif ($itionGId == 'through') {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->whereNull('if.deleted_at')
                        ->where('if.created_at', '>=', $start)
                        ->where('if.created_at', '<', $end)
                        ->where('if.inspection_group_id', '=', 16)
                        ->whereIn('if.inspector_group', $itorG);
                })
                ->select(['parts.id', 'if.created_at'])
                ->orderBy('if.inspected_at')
                ->get();

            $count1 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(9)) && $p->created_at->lte($date->copy()->addHours(13));
            })->count();

            $count2 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(16)->addMinutes(30)) && $p->created_at->lte($date->copy()->addHours(21)->addMinutes(15));
            })->count();

            $count3 = $parts->filter(function($p) use ($date) {
                return $p->created_at->gte($date->copy()->addHours(24)->addMinutes(45)) && $p->created_at->lte($date->copy()->addHours(29)->addMinutes(30));
            })->count();

            if ($count1 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(20)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count2 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(6)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(6)->addMinutes(30));
                });
            }
            elseif ($count3 > 1) {
                $parts = $parts->filter(function($p) use ($date) {
                    return $p->created_at->gte($date->copy()->addHours(8)->addMinutes(30)) && $p->created_at->lt($date->copy()->addDay()->addHours(8)->addMinutes(30));
                });
            }
            else {
                $parts = $parts->filter(function($p) use ($date) {
                    return false;
                });
            }

            $partIds = $parts->map(function($p) {
                return $p['id'];
            });

            $parts = Part::whereIn('parts.id', $partIds)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->whereIn('if.inspection_group_id', [9, 16, 10, 11, 12, 14]);
                })
                ->select(['parts.panel_id', 'pp.status', 'pg.page_type_id', 'pg.family_id', 'if.inspection_group_id', 'if.created_by', 'if.inspected_at', 'if.created_at', 'if.updated_at'])
                ->orderBy('if.inspected_at')
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
        if ($itionGId !== 'through') {
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
        }
        else {
            $inspectionGroup = InspectionGroup::find($itionGId);
            $vehicle = '680A';
            $line = null;
            $ition_name = '検査サマリー';
            $process_name = '接着';
            $division = '';

            $report = new Report;
            $report->setInfo($vehicle, $process_name, $ition_name, $line, $division, $date, implode(',', $itorG));
        }


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
            case 'through':
                $tcpdf = $report->forThrough($parts);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_j_through_assy';
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

