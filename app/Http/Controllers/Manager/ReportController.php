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
        $start = $date->addHours(2);
        $end = $date->copy()->addDay(1);
        if ($itorG == 'W') {
            $itorG = ['白直', '不明'];
        }
        else if ($itorG == 'Y') {
            $itorG = ['黄直', '不明'];
        }

        function array_every($arr) {
          foreach ($arr as $element) {
            if ($element->pages->count() !== 0) {
              return true;
            }
          }
          return false;
        }

        $failureTypes = InspectionGroup::find($itionGId)->inspection->failures->map(function($f) {
            return [
                'id' => $f->id,
                'label' => $f->label,
                'name' => $f->name,
                'type' => $f->pivot->type,
                'sort' => $f->pivot->sort
            ];
        })->toArray();

        foreach( $failureTypes as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }

        if (count($failureTypes) != 0) {
            array_multisort($f_type_array, $f_sort_array, $f_label_array, $failureTypes);
        }

        if (intval($itionGId) == 3 || intval($itionGId) == 9) {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($start, $end, $itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->where('inspection_group_id', '=', $itionGId)
                        ->whereIn('inspector_group', $itorG);
                })
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.inspected_at'])
                ->with(['inlines'])
                ->get()
                ->sortByDesc('inspected_at')
                ->groupBy('panel_id')
                ->map(function($part) {
                    return $part->first();
                })
                ->sortBy('inspected_at')
                ->values();

            // return $parts;
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
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'pg.family_id', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at'])
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
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'pg.family_id', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at'])
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

        if ($parts->count() == 0) {
            $tcpdf = new TCPDF;
            $tcpdf->AddPage('L', 'A4');
            $tcpdf->SetFont('kozgopromedium', '', 16);
            $tcpdf->Text(130, 80, '検索結果なし');
            $pdf_path = 'nothing.pdf';
            $tcpdf->output($pdf_path, 'I');
        }

        $report = new Report;
        $now = Carbon::now();

        switch ($itionGId) {
            case 1:
                $report->setInfo('680A', '成型工程ライン１', 'インナー外観検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forGaikan($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_inner';
                break;
            case 2: 
                $report->setInfo('680A', '成型工程ライン２', 'インナー外観検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forGaikan($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m002_inner';
                break;
            case 3:
                $report->setInfo('680A', '成型工程ライン１', 'インナー精度検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forInline($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_inline_inner';
                break;
            case 5:
                $report->setInfo('680A', '成型工程ライン１', 'アウター外観検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forGaikan($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_outer';
                break;
            case 6:
                $report->setInfo('680A', '成型工程ライン１', 'アウター外観検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forGaikan($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m002_outer';
                break;
            case 4:
                $report->setInfo('680A', '穴あけ工程', 'インナー穴検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forAnaSingle($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_inner';
                break;
            case 8:
                $report->setInfo('680A', '穴あけ工程', 'アウター穴検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forAnaMulti($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_outer';
                break;
            case 15:
                $report->setInfo('680A', '穴あけ工程', 'インナー外観検査結果', $date, implode(',', $itorG));
                $tcpdf = $report->forGaikan($parts, $failureTypes);
                $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_gaikan_inner';
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

