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
        foreach ($families as $row => $family) {
            $body[$row] = [
                '680A',
                $family->pages[0]->parts[0]->partType->pn,
                $family->pages[0]->parts[0]->partType->name,
                $family->pages[0]->parts[0]->panel_id,
                explode(',', $family->created_by)[1],
                $family->status == 1 ? '○' : '×'
            ];

            foreach ($failures as $c => $f) {
                $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

                $sum = 0;
                if ($fp->has($f['id'])) $sum = $fp[$f['id']]->count();

                array_push($body[$row], $sum);
                $row_sum[$c+6] = array_key_exists($c+6, $row_sum) ? $row_sum[$c+6]+$sum : $sum;
            }

            array_push($body[$row], $family->created_at->format('Ymdhms'));
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

        array_pop($body);

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
    }

    protected function forMoldingInlineInner($tcpdf, $date, $families, $itorG_name)
    {
        $now = Carbon::now();
        $failures = Process::where('id', 'molding')->first()->failures()->get(['id', 'sort']);
        $n = $failures->count();

        $body = [];
        $row_sum = [5 => '合計'];
    }


    protected function forMoldingSmall($tcpdf)
    {
        $tcpdf->AddPage('L', 'A3');
        $tcpdf->SetFont('kozgopromedium', '', 12);
        $tcpdf->Text(10, 10, '工程：成形工程ライン１');
        $tcpdf->Text(80, 10, '検査：アウター');
        $tcpdf->Text(160, 10, '2016/10/12');
        $tcpdf->Text(200, 10, '黄直');
        $tcpdf->Text(350, 10, '印刷日時　2016/10/13 18 :00');

        $tcpdf->SetFont('kozgopromedium', '', 8);
        $x = [10, 12, 14, 30, 20, 22, 20, 16, 16, 16, 16, 16, 16, 16, 16, 16, 18, 18, 18, 18, 18, 18, 16];

        $tcpdf->Text(array_sum(array_slice($x,0,1)), 22, '車種');
        $tcpdf->Text(array_sum(array_slice($x,0,2)), 22, '品番');
        $tcpdf->Text(array_sum(array_slice($x,0,3)), 22, '名称');
        $tcpdf->Text(array_sum(array_slice($x,0,4)), 22, 'パネルID');
        $tcpdf->Text(array_sum(array_slice($x,0,5)), 22, '検査者');
        $tcpdf->Text(array_sum(array_slice($x,0,6)), 22, '出荷判定');
        $tcpdf->Text(array_sum(array_slice($x,0,7)), 22, '不良区分１');
        $tcpdf->Text(array_sum(array_slice($x,0,8)), 22, '不良区分２');
        $tcpdf->Text(array_sum(array_slice($x,0,9)), 22, '不良区分３');
        $tcpdf->Text(array_sum(array_slice($x,0,10)), 22, '不良区分４');
        $tcpdf->Text(array_sum(array_slice($x,0,11)), 22, '不良区分５');
        $tcpdf->Text(array_sum(array_slice($x,0,12)), 22, '不良区分６');
        $tcpdf->Text(array_sum(array_slice($x,0,13)), 22, '不良区分７');
        $tcpdf->Text(array_sum(array_slice($x,0,14)), 22, '不良区分８');
        $tcpdf->Text(array_sum(array_slice($x,0,15)), 22, '不良区分９');
        $tcpdf->Text(array_sum(array_slice($x,0,16)), 22, '不良区分10');
        $tcpdf->Text(array_sum(array_slice($x,0,17)), 22, '不良区分11');
        $tcpdf->Text(array_sum(array_slice($x,0,18)), 22, '不良区分12');
        $tcpdf->Text(array_sum(array_slice($x,0,19)), 22, '不良区分13');
        $tcpdf->Text(array_sum(array_slice($x,0,20)), 22, '不良区分14');
        $tcpdf->Text(array_sum(array_slice($x,0,21)), 22, '不良区分15');
        $tcpdf->Text(array_sum(array_slice($x,0,22)), 22, '手直合計');
        $tcpdf->Text(array_sum(array_slice($x,0,23)), 22, '登録時間');

        $lists = [
            ['680A', '67149', 'アッパー', 'A1234567', '佐々木　賢介', '○', 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 15, 201610121621],
            ['680A', '67149', 'サイドロアRH', 'A1234567', '佐々木　賢介', '○', 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 15, 201610121621],
            ['680A', '67149', 'サイドロアLH', 'A1234567', '佐々木　賢介', '○', 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 15, 201610121621],
            ['680A', '67149', 'サイドアッパーLH', 'A1234567', '佐々木　賢介', '○', 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 15, 201610121621],
            ['680A', '67119', 'サイドアッパーRH', 'A1234567', '佐々木　賢介', '○', 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 15, 201610121621]
        ];

        $i = 1;

        for ($i=1; $i <= 2  ; $i++) {
            foreach ($lists as $ii => $list) {
                foreach ($list as $key => $value) {
                    $tcpdf->Text(array_sum(array_slice($x,0,$key+1)), 22+6+(($i-1)*5+$ii)*6, $value);
                }
            }
        }
    }

    protected function forMoldingASSY($tcpdf)
    {
        $tcpdf->AddPage('L', 'A3');
        $tcpdf->SetFont('kozgopromedium', '', 12);
        $tcpdf->Text(10, 10, '工程：成形工程ライン１');
        $tcpdf->Text(80, 10, '検査：アウター');
        $tcpdf->Text(160, 10, '2016/10/12');
        $tcpdf->Text(200, 10, '黄直');
        $tcpdf->Text(350, 10, '印刷日時　2016/10/13 18 :00');

        $tcpdf->SetFont('kozgopromedium', '', 8);
        $x = [10, 12, 14, 36, 18, 22, 20, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12,12, 12, 12, 12, 16];

        $tcpdf->Text(array_sum(array_slice($x,0,1)), 22, '車種');
        $tcpdf->Text(array_sum(array_slice($x,0,2)), 22, '品番');
        $tcpdf->Text(array_sum(array_slice($x,0,3)), 22, '名称');
        $tcpdf->Text(array_sum(array_slice($x,0,4)), 22, 'パネルID');
        $tcpdf->Text(array_sum(array_slice($x,0,5)), 22, '検査者');
        $tcpdf->Text(array_sum(array_slice($x,0,6)), 22, '判定');
        $tcpdf->Text(array_sum(array_slice($x,0,7)), 22, 'P -1');
        $tcpdf->Text(array_sum(array_slice($x,0,8)), 22, 'P -2');
        $tcpdf->Text(array_sum(array_slice($x,0,9)), 22, 'P -3');
        $tcpdf->Text(array_sum(array_slice($x,0,10)), 22, 'P -4');
        $tcpdf->Text(array_sum(array_slice($x,0,11)), 22, 'P -5');
        $tcpdf->Text(array_sum(array_slice($x,0,12)), 22, 'P -6');
        $tcpdf->Text(array_sum(array_slice($x,0,13)), 22, 'P -7');
        $tcpdf->Text(array_sum(array_slice($x,0,14)), 22, 'P -8');
        $tcpdf->Text(array_sum(array_slice($x,0,15)), 22, 'P -9');
        $tcpdf->Text(array_sum(array_slice($x,0,16)), 22, 'P -10');
        $tcpdf->Text(array_sum(array_slice($x,0,17)), 22, 'P -11');
        $tcpdf->Text(array_sum(array_slice($x,0,18)), 22, 'P -12');
        $tcpdf->Text(array_sum(array_slice($x,0,19)), 22, 'P -13');
        $tcpdf->Text(array_sum(array_slice($x,0,20)), 22, 'P -14');
        $tcpdf->Text(array_sum(array_slice($x,0,21)), 22, 'P -15');
        $tcpdf->Text(array_sum(array_slice($x,0,22)), 22, 'P -16');
        $tcpdf->Text(array_sum(array_slice($x,0,23)), 22, 'P -17');
        $tcpdf->Text(array_sum(array_slice($x,0,24)), 22, 'P -18');
        $tcpdf->Text(array_sum(array_slice($x,0,25)), 22, 'P -19');
        $tcpdf->Text(array_sum(array_slice($x,0,26)), 22, 'P -20');
        $tcpdf->Text(array_sum(array_slice($x,0,27)), 22, 'P -21');
        $tcpdf->Text(array_sum(array_slice($x,0,28)), 22, 'P -22');
        $tcpdf->Text(array_sum(array_slice($x,0,29)), 22, '登録時間');

        $lists = [
            ['680A', '67007', 'バックドアインナーASSY', 'A1234567', '佐々木　賢介', 'OK', '0.994', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', 201610121621],
            ['680A', '67007', 'バックドアインナーASSY', 'A1234567', '佐々木　賢介', 'OK', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', 201610121621],
            ['680A', '67007', 'バックドアインナーASSY', 'A1234567', '佐々木　賢介', 'OK', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', 201610121621],
            ['680A', '67007', 'バックドアインナーASSY', 'A1234567', '佐々木　賢介', 'NG', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '0.994', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', '-2.570', 201610121621],
        ];

        $i = 1;

        for ($i=1; $i <= 2  ; $i++) {
            foreach ($lists as $ii => $list) {
                foreach ($list as $key => $value) {
                    $tcpdf->Text(array_sum(array_slice($x,0,$key+1)), 22+6+(($i-1)*4+$ii)*6, $value);
                }
            }
        }
    }

    protected function forHolingInner($tcpdf)
    {
        $tcpdf->AddPage('L', 'A3');
        $tcpdf->SetFont('kozgopromedium', '', 12);
        $tcpdf->Text(10, 10, '工程：成形工程ライン１');
        $tcpdf->Text(80, 10, '検査：アウター');
        $tcpdf->Text(160, 10, '2016/10/12');
        $tcpdf->Text(200, 10, '黄直');
        $tcpdf->Text(350, 10, '印刷日時　2016/10/13 18 :00');

        $tcpdf->SetFont('kozgopromedium', '', 5);
        $x = [10, 6, 7, 22, 11, 12, 6];

        $tcpdf->Text(array_sum(array_slice($x,0,1)), 22, '車種');
        $tcpdf->Text(array_sum(array_slice($x,0,2)), 22, '品番');
        $tcpdf->Text(array_sum(array_slice($x,0,3)), 22, '名称');
        $tcpdf->Text(array_sum(array_slice($x,0,4)), 22, 'パネルID');
        $tcpdf->Text(array_sum(array_slice($x,0,5)), 22, '検査者');
        $tcpdf->Text(array_sum(array_slice($x,0,6)), 22, '判定');
        
        $hole_label = [1,3,4,7,8,10,11,14,15,16,17,18,19,20,21,22,25,26,27,28,29,30,31,32,33,34,35,36,38,40,41,42,43,44,45,46,47,49,51,52,53,54,55,56,57,58,59,60,61,63,64,65,66,67,68,69,70,71,72,76,77,78,79,81,82,83,84,86,87,88,89,90,91,93,94,95,99,100,102,103,104,105,106,107,108,109,113,115,117,119];
        for ($i=0; $i <90 ; $i++) {
            $tcpdf->Text(array_sum(array_slice($x,0,7))+$i*3.7, 22, $hole_label[$i]);
        }

        $tcpdf->Text(array_sum(array_slice($x,0,7))+3.7*90, 22, '登録時間');

        $list = ['680A', '67007', 'バックドアインナーASSY', 'A1234567', '佐々木　賢介', '○'];

        $i = 1;

        for ($i=1; $i <= 40; $i++) { 
            $tcpdf->SetFont('kozgopromedium', '', 5);
            foreach ($list as $key => $value) {
                $tcpdf->Text(array_sum(array_slice($x,0,$key+1)), 22+$i*3, $value);
            }
            $tcpdf->SetFont('kozgopromedium', '', 7);
            for ($ii=0; $ii < 90; $ii++) {
                if ($hole_label[$ii] > 9 && $hole_label[$ii] < 100) {
                    $xi = $ii*3.7+0.5;
                } elseif ($hole_label[$ii] > 99) {
                    $xi = $ii*3.7+1.2;
                } else {
                    $xi = $ii*3.7;
                }

                $vs = ['○', '×', '△'];
                $v =  $vs[array_rand($vs, 1)];
                $tcpdf->Text(array_sum(array_slice($x,0,7))+$xi, 22+$i*3, $v);
            }
        }
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
                'pages.inlines',
                'pages.parts',
                'pages.parts.partType',
                'pages.failurePositions'
            ])
            ->get();

// return $families;

        // $now = Carbon::now();
        // $n = 22;

        // $body = [];
        // foreach ($families as $row => $family) {
        //     $body[$row] = [
        //         '680A',
        //         $family->pages[0]->parts[0]->partType->pn,
        //         $family->pages[0]->parts[0]->partType->name,
        //         $family->pages[0]->parts[0]->panel_id,
        //         explode(',', $family->created_by)[1],
        //         $family->status == 1 ? 'OK' : 'NG'
        //     ];

        //     foreach ($family->pages[0] as $key => $value) {
        //         # code...
        //     }

        //     foreach ($failures as $c => $f) {
        //         $fp = $family->pages[0]->failurePositions->groupBy('failure_id');

        //         $sum = 0;
        //         if ($fp->has($f->id)) $sum = $fp[$f->id]->count();

        //         array_push($body[$row], $sum);
        //     }

        //     array_push($body[$row], $family->created_at->format('Ymdhms'));
        // }





        $tcpdf = $this->createTCPDF();

        switch (intval($itionG_id)) {
            case 1:  $this->forMoldingInner($tcpdf, $date, $families, $itorG_name, '１'); break;
            case 2:  $this->forMoldingInner($tcpdf, $date, $families, $itorG_name, '２'); break;
            case 3:  $this->forMoldingInlineInner($tcpdf, $date, $families, $itorG_name); break;
            case 4:  $this->forHolingInner($tcpdf, 1); break;

            case 5:  $this->forMoldingSmall($tcpdf, 1); break;
            case 6:  $this->forMoldingSmall($tcpdf, 1); break;

            case 8:  $this->forMoldingInner($tcpdf, 1); break;
            case 9:  $this->forMoldingInner($tcpdf, 1); break;
            case 10: $this->forMoldingInner($tcpdf, 1); break;
            case 11: $this->forMoldingInner($tcpdf, 1); break;
            case 12: $this->forMoldingInner($tcpdf, 1); break;
            case 13: $this->forMoldingInner($tcpdf, 1); break;
            case 14: $this->forMoldingInner($tcpdf, 1); break;
        }

        // $this->forMoldingInner($tcpdf);
        // $this->forMoldingSmall($tcpdf);
        // $this->forMoldingASSY($tcpdf);
        // $this->forHolingInner($tcpdf);

        $pdf_path = storage_path() . '/M001C_Y_20161012.pdf';
        $tcpdf->output($pdf_path, 'I');
    }
}
