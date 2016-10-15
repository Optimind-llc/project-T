<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TCPDF;
use FPDI;
// Models
use App\Models\Process;
use App\Models\Inspector;
use App\Models\InspectorGroup;
use App\Models\Inspection;
use App\Models\InspectionGroup;
use App\Models\Division;
use App\Models\Failure;
use App\Models\Comment;
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
 * Class PdfController
 * @package App\Http\Controllers
 */
class PdfController extends Controller
{
    protected function createTCPDF()
    {
        $fpdi = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $fpdi->SetMargins(0, 0, 0);
        $fpdi->setPrintHeader(false);
        $fpdi->setPrintFooter(false);
        $fpdi->SetMargins(0, 0, 0);
        $fpdi->SetCellPadding(0);
        $fpdi->SetAutoPageBreak(false);

        $fpdi->SetAuthor('Mapping system');
        $fpdi->SetTitle('Report');
        $fpdi->SetSubject('Mapping system Report');
        $fpdi->SetKeywords('TCPDF, PDF');
        return $fpdi;
    }

    protected function forMoldingInner($tcpdf, $date, $families, $itorG_name, $line)
    {
        $now = Carbon::now();

        $failures = Process::where('id', 'molding')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->sortBy('type')
            ->values()
            ->all();

        $n = count($failures);

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c => $f) {
                    $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                    $sum = 0;
                    if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                    array_push($body[$r], $sum);
                    $row_sum[$c+6] = array_key_exists($c+6, $row_sum) ? $row_sum[$c+6]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        /*
         * Render A4 PDF
         */
        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [8, 9, 20, 13, 16, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：成形工程ライン'.$line);
            $tcpdf->Text(60, $y0, '検査：インナー');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 21) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        /*
         * Render A4 PDF
         */
        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [12, 14, 30, 20, 22, 20];
        $fd = 17;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：成形工程ライン'.$line);
            $tcpdf->Text(80, $y0, '検査：インナー');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+count($failures)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        if ($line == '１') {
            $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_m001_inner_w.pdf';
        } else {
            $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_m002_inner_w.pdf';
        }

        $tcpdf->output($pdf_path, 'I');
    }

    protected function forMoldingInlineInner($tcpdf, $date, $families)
    {
        $now = Carbon::now();

        $body = [];
        $row_sum = [5 => '合計'];

        $inlines = $families[0]->pages[0]->inlines->map(function($inline) {
            return [
                'sort' => $inline->sort,
                'status' => $inline->pivot->status,
                'inspected_at' => $inline->pivot->inspected_at
            ];
        });

        $n = $inlines->count();

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    $part['status'] == 1 ? 'OK' : 'NG'
                ];

                foreach ($inlines as $c => $i) {
                    array_push($body[$r], $i['status']);
                }

                $at = preg_replace("/(-| |:)/", "", $inlines[0]['inspected_at']);
                array_push($body[$r], $at);

                $r = $r+1;
            }

            $r = $r+1;
        }

        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [8, 9, 20, 13, 16];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[4] == 'OK' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[4] == 'NG' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：成形工程');
            $tcpdf->Text(60, $y0, '精度検査');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, 'OK');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, 'NG');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 5) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 27) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 27) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [11, 12, 33, 20, 20];
        $fd = 13;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：成形工程');
            $tcpdf->Text(60, $y0, '精度検査');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '出荷判定');

            foreach ($inlines as $i => $inline) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, 'P-'.$inline['sort']);
            }

            $tcpdf->Text($x0+array_sum($d)+$n*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 5) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-5)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_m001_inner_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    /***** Should be refactaring *****/
    protected function forHolingInner($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $body = [];
        $row_sum1 = [4 => '合計', 5 => '○'];
        $row_sum2 = [5 => '△'];
        $row_sum3 = [5 => '×'];

        foreach ($families as $row => $family) {
            $body[$row] = [
                '680A',
                $family->pages[0]->parts[0]->partType->pn,
                $family->pages[0]->parts[0]->partType->name,
                $family->pages[0]->parts[0]->panel_id,
                explode(',', $family->created_by)[1],
                $family->status == 1 ? '○' : '×'
            ];

            $holes_arr = $family->pages->reduce(function ($carry, $page) {
                return array_merge($carry, $page->holes->toArray());
            },[]);
            
            $holes = array_column($holes_arr, 'pivot', 'label');
            ksort($holes);
            $i = 0;

            foreach ($holes as $c => $h) {
                switch ($h['status']) {
                    case 1: $sign = '○'; $row_sum1[$i+6] = array_key_exists($i+6, $row_sum1) ? $row_sum1[$i+6]+1 : 1; break;
                    case 2: $sign = '△'; $row_sum2[$i+6] = array_key_exists($i+6, $row_sum2) ? $row_sum2[$i+6]+1 : 1; break;
                    case 0: $sign = '×'; $row_sum3[$i+6] = array_key_exists($i+6, $row_sum3) ? $row_sum3[$i+6]+1 : 1; break;
                }
                array_push($body[$row], $sign);
                $i = $i+1;
            }

            array_push($body[$row], $family->created_at->format('Ymdhms'));
        }

        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [8, 9, 20, 13, 16, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：穴あけ工程');
            $tcpdf->Text(60, $y0, '検査：インナー');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 96) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        array_push($body, $row_sum1);
        array_push($body, $row_sum2);
        array_push($body, $row_sum3);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [6, 7, 22, 11, 12, 10];
        $fd = 3.6;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：穴あけ工程');
            $tcpdf->Text(80, $y0, '検査：インナー');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 5);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            /***** HARD CODE *****/
            $n = 90;

            $i = 0;
            foreach ($holes as $key => $hole) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $key);
                $i = $i+1;
            }

            $tcpdf->Text($x0+array_sum($d)+$n*$fd, $y0+$y1, '登録時間');



            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->SetFont('kozgopromedium', '', 5);
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } elseif ($c >= 6 && $c < 6+$n) {
                        $tcpdf->SetFont('kozgopromedium', '', 5);
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->SetFont('kozgopromedium', '', 5);
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_h_inner_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forMoldingSmall($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();
        $failures = Process::where('id', 'molding')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->sortBy('type')
            ->values()
            ->all();

        $n = count($failures);

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            $fp = $family->pages->reduce(function ($carry, $page) {
                $fp = $page->failurePositions->map(function ($fp){
                    return [
                        'part_id' => $fp->part_id,
                        'failure_id' => $fp->failure_id
                    ];
                });

                return array_merge($carry, $fp->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c => $f) {
                    $filterd = array_filter($fp, function($value) use ($part, $f){
                        return $value['part_id'] == $part['id'] && $value['failure_id'] == $f['id'];
                    });
                    $sum = count($filterd);

                    array_push($body[$r], $sum);
                    $row_sum[$c+6] = array_key_exists($c+6, $row_sum) ? $row_sum[$c+6]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [8, 9, 20, 13, 16, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：成形工程');
            $tcpdf->Text(60, $y0, '検査：アウター');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 21) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [12, 14, 30, 20, 22, 20];
        $fd = 17;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：成形工程');
            $tcpdf->Text(80, $y0, '検査：アウター');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+count($failures)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        // report_680A_20161014_m001_outer_w.pdf
        // report_680A_20161014_m001_inline_w.pdf
        // report_680A_20161014_h_inner_w.pdf
        
        // report_680A_20161014_j_tome_w.pdf
        // report_680A_20161014_j_siage_w.pdf
        // report_680A_20161014_j_kensa_w.pdf
        // report_680A_20161014_j_tokken_w.pdf
        // report_680A_20161014_j_tenaosi_w.pdf

        if ($line == '１') {
            $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_outer_w.pdf';
        } else {
            $pdf_path = '/report_'.'680A'.'_'.$now->format('Ymd').'_m002_outer_w.pdf';
        }

        $tcpdf->output($pdf_path, 'I');
    }

    protected function forHolingSmall($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $holes = $families[0]->pages->reduce(function ($carry, $page) {
            $holes = $page->holes->map(function ($h){
                return [
                    'id' => $h->id,
                    'label' => $h->label,
                    'pn' => $h->partType->pn,
                    'status' => $h->pivot->status
                ];
            });

            return array_merge($carry, $holes->toArray());
        }, []);

        $body = [];
        $row_sum1 = [4 => '合計', 5 => '○'];
        $row_sum2 = [5 => '△'];
        $row_sum3 = [5 => '×'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($holes as $key => $hole) {
                    $sign = '';
                    $row_sum1[$key+6] = array_key_exists($key+6, $row_sum1) ? $row_sum1[$key+6] : 0;
                    $row_sum2[$key+6] = array_key_exists($key+6, $row_sum2) ? $row_sum2[$key+6] : 0;
                    $row_sum3[$key+6] = array_key_exists($key+6, $row_sum3) ? $row_sum3[$key+6] : 0;

                    if ($hole['pn'] == $part['pn']) {
                        switch ($hole['status']) {
                            case 1: $sign = '○'; $row_sum1[$key+6] = array_key_exists($key+6, $row_sum1) ? $row_sum1[$key+6]+1 : 1; break;
                            case 2: $sign = '△'; $row_sum2[$key+6] = array_key_exists($key+6, $row_sum2) ? $row_sum2[$key+6]+1 : 1; break;
                            case 0: $sign = '×'; $row_sum3[$key+6] = array_key_exists($key+6, $row_sum3) ? $row_sum3[$key+6]+1 : 1; break;
                        }
                    }

                    array_push($body[$r], $sign);
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }
            $r = $r+1;
        }

        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [8, 9, 20, 13, 16, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：穴あけ工程');
            $tcpdf->Text(60, $y0, '検査：インナー');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 96) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        array_push($body, $row_sum1);
        array_push($body, $row_sum2);
        array_push($body, $row_sum3);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [12, 14, 30, 20, 22, 20];
        $fd = 9;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：穴あけ工程');
            $tcpdf->Text(80, $y0, '検査：アウター');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            /***** HARD CODE *****/
            $n = 27;

            $i = 0;
            foreach ($holes as $key => $hole) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $hole['label']);
                $i = $i+1;
            }

            $tcpdf->Text($x0+array_sum($d)+$n*$fd, $y0+$y1, '登録時間');

            // Render table body
            $tcpdf->SetFont('kozgopromedium', '', 8);
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } elseif ($c >= 6 && $c < 6+$n) {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_h_outer_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forJointingInlineASSY($tcpdf, $date, $families)
    {
        $now = Carbon::now();

        $body = [];
        $row_sum = [5 => '合計'];

        $inlines = $families[0]->pages[0]->inlines->map(function($inline) {
            return [
                'sort' => $inline->sort,
                'status' => $inline->pivot->status,
                'inspected_at' => $inline->pivot->inspected_at
            ];
        });

        $n = $inlines->count();

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    $part['status'] == 1 ? 'OK' : 'NG'
                ];

                foreach ($inlines as $c => $i) {
                    array_push($body[$r], $i['status']);
                }

                $at = preg_replace("/(-| |:)/", "", $inlines[0]['inspected_at']);
                array_push($body[$r], $at);

                $r = $r+1;
            }
            $r = $r+1;
        }

        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [7, 8, 24, 12, 14];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[4] == 'OK' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[4] == 'NG' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(60, $y0, '精度検査');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, 'OK');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, 'NG');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 5) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 27) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 27) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [11, 12, 33, 20, 20];
        $fd = 13;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(60, $y0, '精度検査');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '出荷判定');

            foreach ($inlines as $i => $inline) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, 'P-'.$inline['sort']);
            }

            $tcpdf->Text($x0+array_sum($d)+$n*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 5) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-5)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_j_inline_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forJointingSWASSY($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $failures = Process::where('id', 'jointing')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->filter(function($f) {
                return $f['name'] == '水漏れ';
            })
            ->sortBy('type')
            ->values()
            ->all();

        $n = count($failures);

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c => $f) {
                    $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                    $sum = 0;
                    if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                    array_push($body[$r], $sum);
                    $row_sum[$c+6] = array_key_exists($c+6, $row_sum) ? $row_sum[$c+6]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        /*
         * Render A4 PDF
         */
        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [7, 8, 24, 12, 14, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(50, $y0, '止水：インナーASSY');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 21) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        /*
         * Render A4 PDF
         */
        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [12, 14, 30, 20, 22, 20];
        $fd = 15.5;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(80, $y0, '止水：インナーASSY');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 7);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+count($failures)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_j_tome_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forJointingFNASSY($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $failures = Process::where('id', 'molding')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->sortBy('type')
            ->values()
            ->all();
        $n1 = count($failures);

        $comments = Inspection::find(6)
            ->comments()
            ->orderBy('sort')
            ->get(['id', 'sort', 'message']);
        $n2 = $comments->count();

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            $c_comments = $family->pages->reduce(function ($carry, $page) {
                $comments = $page->comments->map(function ($comment){
                    return [
                        'sort' => $comment->comment->sort,
                        'message' => $comment->comment->message
                    ];
                });
                return array_merge($carry, $comments->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c1 => $f) {
                    $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                    $sum = 0;
                    if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                    array_push($body[$r], $sum);
                    $row_sum[$c1+6] = array_key_exists($c1+6, $row_sum) ? $row_sum[$c1+6]+$sum : $sum;
                }

                foreach ($comments as $c2 => $c) {
                    $sum = count(array_filter($c_comments, function($comments) use ($c){
                        return $comments['sort'] = $c['sort'];
                    }));

                    array_push($body[$r], $sum);
                    $row_sum[6+$c1+1+$c2] = array_key_exists(6+$c1+1+$c2, $row_sum) ? $row_sum[6+$c1+1+$c2]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        /*
         * Render A4 PDF
         */
        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [7, 8, 24, 12, 14, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(50, $y0, '仕上：インナーASSY');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 6+$n1+$n2) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        /*
         * Render A4 PDF
         */
        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [8, 9, 24, 14, 16, 14];
        $fd = 12;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(80, $y0, '仕上：インナーASSY');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 6);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            foreach ($comments as $i => $c) {
                $tcpdf->Text($x0+array_sum($d)+($n1+$i)*$fd, $x0+12, $c['message']);
            }

            $tcpdf->Text($x0+array_sum($d)+($n1+$n2)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_j_siage_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forJointingCHASSY($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $failures = Process::where('id', 'jointing')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->sortBy('type')
            ->values()
            ->all();

        $n = count($failures);

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c => $f) {
                    $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                    $sum = 0;
                    if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                    array_push($body[$r], $sum);
                    $row_sum[$c+6] = array_key_exists($c+6, $row_sum) ? $row_sum[$c+6]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        /*
         * Render A4 PDF
         */
        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [7, 8, 24, 12, 14, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(50, $y0, '点検：インナーASSY');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 21) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        /*
         * Render A4 PDF
         */
        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [12, 14, 30, 20, 22, 20];
        $fd = 15.5;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(80, $y0, '点検：インナーASSY');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 7);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+count($failures)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_j_kensa_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forJointingSCASSY($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $failures = Process::where('id', 'jointing')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->sortBy('type')
            ->values()
            ->all();

        $n = count($failures);

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c => $f) {
                    $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                    $sum = 0;
                    if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                    array_push($body[$r], $sum);
                    $row_sum[$c+6] = array_key_exists($c+6, $row_sum) ? $row_sum[$c+6]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        /*
         * Render A4 PDF
         */
        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [7, 8, 24, 12, 14, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(50, $y0, '特検：インナーASSY');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 21) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        /*
         * Render A4 PDF
         */
        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [12, 14, 30, 20, 22, 20];
        $fd = 15.5;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(80, $y0, '特検：インナーASSY');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 7);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+count($failures)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_j_tokken_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    protected function forJointingADASSY($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();

        $failures = Process::where('id', 'molding')
            ->first()
            ->failures()
            ->orderBy('sort')
            ->get(['id', 'sort', 'name'])
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->name,
                    'type' => $f->pivot->type
                ];
            })
            ->sortBy('type')
            ->values()
            ->all();
        $n1 = count($failures);

        $comments = Inspection::find(6)
            ->comments()
            ->orderBy('sort')
            ->get(['id', 'sort', 'message']);
        $n2 = $comments->count();

        $body = [];
        $row_sum = [5 => '合計'];

        $r = 0;
        foreach ($families as $row => $family) {
            $parts = $family->pages->reduce(function ($carry, $page) {
                $parts = $page->parts->map(function ($part){
                    return [
                        'id' => $part->id,
                        'status' => $part->pivot->status,
                        'panel_id' => $part->panel_id,
                        'pn' => $part->partType->pn,
                        'name' => $part->partType->name,
                        'sort' => $part->partType->sort,
                        'vehicle' => $part->partType->vehicle_num
                    ];
                });
                return array_merge($carry, $parts->toArray());
            }, []);

            $c_comments = $family->pages->reduce(function ($carry, $page) {
                $comments = $page->comments->map(function ($comment){
                    return [
                        'sort' => $comment->comment->sort,
                        'message' => $comment->comment->message
                    ];
                });
                return array_merge($carry, $comments->toArray());
            }, []);

            foreach ($parts as $i => $part) {
                $body[$r] = [
                    $part['vehicle'],
                    $part['pn'],
                    $part['name'],
                    $part['panel_id'],
                    explode(',', $family->created_by)[1],
                    $part['status'] == 1 ? '○' : '×'
                ];

                foreach ($failures as $c1 => $f) {
                    $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                    $sum = 0;
                    if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                    array_push($body[$r], $sum);
                    $row_sum[$c1+6] = array_key_exists($c1+6, $row_sum) ? $row_sum[$c1+6]+$sum : $sum;
                }

                foreach ($comments as $c2 => $c) {
                    $sum = count(array_filter($c_comments, function($comments) use ($c){
                        return $comments['sort'] = $c['sort'];
                    }));

                    array_push($body[$r], $sum);
                    $row_sum[6+$c1+1+$c2] = array_key_exists(6+$c1+1+$c2, $row_sum) ? $row_sum[6+$c1+1+$c2]+$sum : $sum;
                }

                array_push($body[$r], $family->created_at->format('Ymdhms'));
                $r = $r+1;
            }

            $r = $r+1;
        }

        /*
         * Render A4 PDF
         */
        $x0 = 8;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [7, 8, 24, 12, 14, 11];
        $fd = 17;
        $th = 5;

        $ok = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '○' ? 1 : 0;
        });

        $ng = array_reduce($body, function($carry, $item){
            return $carry += $item[5] == '×' ? 1 : 0;
        });

        foreach (array_chunk($body, 100) as $page => $value) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(50, $y0, '仕上げ：インナーASSY');
            $tcpdf->Text(100, $y0, strtr($date,'-','/'));
            $tcpdf->Text(134, $y0, $itorG_name);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+2, '印刷日時　'.$now->format('Y/m/d h :m :s'));

            if ($page ==0 ) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2, $y0+$y1, '○');
                $tcpdf->Text($x0+2+10, $y0+$y1, $ok);

                $tcpdf->Rect($x0+2+10+16, $y0+$y1-0.3, 7, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+2+10+16+7, $y0+$y1-0.3, 12, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+2+10+16+2, $y0+$y1, '×');
                $tcpdf->Text($x0+2+10+16+2+10, $y0+$y1, $ng);
            }

            if (count($value) < 51) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                foreach ($value as $r => $row) {
                    foreach ($row as $c => $val) {
                        if ($c < 6) {
                            $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                        } elseif ($c == 6+$n1+$n2) {
                            $tcpdf->Text($x0+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                        }
                    }
                }
            } else {
                $tcpdf->Line(106, 28, 106, 281);

                foreach (array_chunk($body, 50) as $column => $value) {
                    $tcpdf->SetFont('kozgopromedium', '', 6);
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,0)), $y0+$y2, '車種');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,1)), $y0+$y2, '品番');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,2)), $y0+$y2, '名称');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,3)), $y0+$y2, 'パネルID');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,4)), $y0+$y2, '検査者');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,5)), $y0+$y2, '出荷判定');
                    $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,6)), $y0+$y2, '登録時間');

                    foreach ($value as $r => $row) {
                        foreach ($row as $c => $val) {
                            if ($c < 6) {
                                $tcpdf->Text($x0+$column*$x1+array_sum(array_slice($d,0,$c)), $y0+$y2+($r+1)*$th, $val);
                            } elseif ($c == 21) {
                                $tcpdf->Text($x0+$column*$x1+array_sum($d), $y0+$y2+($r+1)*$th, $val);
                            }
                        }
                    }
                }
            }

            $tcpdf->Text(103, 287, 'page '.($page+1));
        }

        /*
         * Render A4 PDF
         */
        array_push($body, $row_sum);

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [8, 9, 24, 14, 16, 14];
        $fd = 12;
        $th = 6;

        foreach (array_chunk($body, 40) as $page => $value) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 12);
            $tcpdf->Text($x0, $y0, '工程：接着工程');
            $tcpdf->Text(80, $y0, '仕上げ：インナーASSY');
            $tcpdf->Text(160, $y0, strtr($date,'-','/'));
            $tcpdf->Text(200, $y0, $itorG_name);
            $tcpdf->Text(350, $y0, '印刷日時　'.$now->format('Y/m/d h :m :s'));  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 6);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, '車種');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '品番');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '名称');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,4)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,5)), $y0+$y1, '出荷判定');

            foreach ($failures as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $x0+12, $f['name']);
            }

            foreach ($comments as $i => $c) {
                $tcpdf->Text($x0+array_sum($d)+($n1+$i)*$fd, $x0+12, $c['message']);
            }

            $tcpdf->Text($x0+array_sum($d)+($n1+$n2)*$fd, $y0+$y1, '登録時間');
            // Render table body
            foreach ($value as $r => $row) {
                foreach ($row as $c => $val) {
                    if ($c < 6) {
                        $tcpdf->Text($x0+array_sum(array_slice($d,0,$c)), $y0+$y1+($r+1)*$th, $val);
                    } else {
                        $tcpdf->Text($x0+array_sum($d)+($c-6)*$fd, $y0+$y1+($r+1)*$th, $val);
                    }
                }
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        $pdf_path = 'report_'.$parts[0]['vehicle'].'_'.$now->format('Ymd').'_j_tenaosi_w.pdf';
        $tcpdf->output($pdf_path, 'I');
    }

    /**
     * Get user from JWT token
     */
    public function report($itionG_id, $date, $itorG_code)
    {
        $date_obj = Carbon::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
        $itorG_name = InspectorGroup::find($itorG_code)->name;

        $families = InspectionGroup::find($itionG_id)
            ->families()
            ->whereIn('inspector_group', [$itorG_name, '不明'])
            ->where('created_at', '>=', $date_obj->addHours(1))
            ->where('created_at', '<', $date_obj->copy()->addDay(1))
            ->with([
                'pages',
                'pages.inlines' => function ($q) {
                    $q->orderBy('sort');
                },
                'pages.parts',
                'pages.parts.partType',
                'pages.failurePositions',
                'pages.holes',
                'pages.holes.partType',
                'pages.comments',
                'pages.comments.comment'
            ])
            ->get();

        $tcpdf = $this->createTCPDF();

        switch (intval($itionG_id)) {
            case 1:  $this->forMoldingInner($tcpdf, $date, $families, $itorG_name, '１'); break;
            case 2:  $this->forMoldingInner($tcpdf, $date, $families, $itorG_name, '２'); break;
            case 3:  $this->forMoldingInlineInner($tcpdf, $date, $families); break;
            case 4:  $this->forHolingInner($tcpdf, $date, $families, $itorG_name); break;

            case 5:  $this->forMoldingSmall($tcpdf, $date, $families, $itorG_name, '１'); break;
            case 6:  $this->forMoldingSmall($tcpdf, $date, $families, $itorG_name, '２'); break;
            case 8:  $this->forHolingSmall($tcpdf, $date, $families, $itorG_name); break;

            case 9:  $this->forJointingInlineASSY($tcpdf, $date, $families); break;
            case 10: $this->forJointingSWASSY($tcpdf, $date, $families, $itorG_name); break;
            case 11: $this->forJointingFNASSY($tcpdf, $date, $families, $itorG_name); break;
            case 12: $this->forJointingCHASSY($tcpdf, $date, $families, $itorG_name); break;
            case 13: $this->forJointingSCASSY($tcpdf, $date, $families, $itorG_name); break;
            case 14: $this->forJointingADASSY($tcpdf, $date, $families, $itorG_name); break;
        }
    }
}
