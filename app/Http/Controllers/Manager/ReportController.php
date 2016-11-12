<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TCPDF;
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
    protected $vehicle;
    protected $process_name;
    protected $ition_name;
    protected $print_date;
    protected $tyoku;
    protected $file_name;
    protected $now;

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

    protected function renderA4Header($tcpdf)
    {
        $A4 = config('report.A4');

        $tcpdf->SetFont('kozgopromedium', '', 10);
        $tcpdf->Text($A4['x0']+$A4['header']['vehicle'], $A4['y0'], '車種：'.$this->vehicle);
        $tcpdf->Text($A4['x0']+$A4['header']['process'], $A4['y0'], $this->process_name);
        $tcpdf->Text($A4['x0']+$A4['header']['ition'], $A4['y0'], $this->ition_name);
        $tcpdf->Text($A4['x0']+$A4['header']['date'], $A4['y0'], $this->print_date);
        $tcpdf->Text($A4['x0']+$A4['header']['tyoku'], $A4['y0'], $this->tyoku);
        $tcpdf->SetFont('kozgopromedium', '', 8);
        $tcpdf->Text($A4['x0']+$A4['header']['now'], $A4['y0']+1, '印刷日時　'.$this->now->format('Y/m/d H:i:s'));
    }

    protected function renderA3Header($tcpdf)
    {
        $A3 = config('report.A3');

        $tcpdf->SetFont('kozgopromedium', '', 10);
        $tcpdf->Text($A3['x0']+$A3['header']['vehicle'], $A3['y0'], '車種：'.$this->vehicle);
        $tcpdf->Text($A3['x0']+$A3['header']['process'], $A3['y0'], $this->process_name);
        $tcpdf->Text($A3['x0']+$A3['header']['ition'], $A3['y0'], $this->ition_name);
        $tcpdf->Text($A3['x0']+$A3['header']['date'], $A3['y0'], $this->print_date);
        $tcpdf->Text($A3['x0']+$A3['header']['tyoku'], $A3['y0'], $this->tyoku);
        $tcpdf->Text($A3['x0']+$A3['header']['now'], $A3['y0'], '印刷日時　'.$this->now->format('Y/m/d H:i:s'));
    }

    protected function forMoldingInner($parts, $failureTypes)
    {
    }

    protected function forMoldingOuter($parts, $failureTypes)
    {
        $tcpdf = $this->createTCPDF();
        $now = Carbon::now();

        $families = $parts->groupBy('family_id')->values();
        $part_types = $parts->groupBy('part_type_id');
        $parts_obj = PartType::whereIn('id', [2, 3, 4, 5, 6])
            ->select(['id', 'pn', 'name', 'short_name'])
            ->get()
            ->values();

        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [8, 18, 20, 38];
        $fd = 17;
        $th = 5;
        $dj = 7;
        $dhj = 34;
        $dhj1 = 5;
        $dhj2 = 12;
        $hhj = 4;

        foreach ($families->chunk(100) as $p => $families100) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                foreach ($parts_obj as $n => $part_obj) {
                    $sum1 = $part_types[$part_obj->id]->filter(function($p) {
                        return $p->status ==  1;
                    })->count();
                    $sum0 = $part_types[$part_obj->id]->filter(function($p) {
                        return $p->status ==  0;
                    })->count();

                    $tcpdf->MultiCell($dhj, $hhj, $part_obj->name.'：'.$part_obj->short_name, 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1'], true, 0, false, true, 0, 'M');
                    $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1+$dhj2, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
                }
            }

            foreach ($families100->values()->chunk(50) as $col => $families50) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '出荷判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '時間');

                foreach ($parts_obj as $n => $part_obj) {
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y2']+4, $part_obj->short_name);
                }

                foreach ($families50->values() as $row => $parts) {
                    $panel_id = $parts[0]->panel_id;
                    $createdBy = explode(',', $parts[0]->created_by);
                    $createdAt = $parts[0]->created_at->format('H:i');

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $panel_id);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+($row)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);

                    foreach ($parts as $n => $part) {
                        $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y3']+($row)*$th, $part->status == 1 ? '○' : '×');
                    }
                    
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, $createdAt);
                }
            }

            $tcpdf->Line(106, 28, 106, 286);
            $tcpdf->Text(103, 287, 'page '.($p+1));
        }

        /*
         * Render A3
         */
        $fn = count($failureTypes);

        $A3 = config('report.A3');
        $d = [6, 18, 20, 26, 26];
        $d_date = 10;

        $fd = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_date)/$fn;
        $fdj = $fd*0.8/$parts_obj->count();

        foreach ($families->chunk(40) as $page => $families40) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $this->renderA3Header($tcpdf);

            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1'], 'No.');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1'], 'パネルID');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1'], '検査者');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1'], '精度判定');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4)), $A3['y1'], '外観判定');

            $parts_obj_except_u = $parts_obj->filter(function($p) {return $p->id != 2;})->values();
            $dj_inline = 26*0.8/$parts_obj_except_u->count();
            $dj_gaikan = 26*0.8/$parts_obj->count();

            foreach ($parts_obj_except_u as $n => $part_obj) {
                $tcpdf->StartTransform();
                $tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_inline)-1, $A3['y1']+10);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_inline)-3, $A3['y1']+10, $part_obj->short_name);
                $tcpdf->StopTransform();
            }

            foreach ($parts_obj as $n => $part_obj) {
                $tcpdf->StartTransform();
                $tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,4))+($n*$dj_gaikan)-1, $A3['y1']+10);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($n*$dj_gaikan)-3, $A3['y1']+10, $part_obj->short_name);
                $tcpdf->StopTransform();
            }

            foreach ($failureTypes as $i => $f) {
                $tcpdf->Text($A3['x0']+array_sum($d)+$i*$fd, $A3['y1'], $f['name']);
            }

            $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y1'], '登録時間');

            foreach ($families40->values() as $row => $family) {
                $panel_id = $family[0]->panel_id;
                $createdBy = explode(',', $family[0]->created_by);
                $createdAt = $family[0]->created_at->format('H:i');

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $row+($page*40)+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panel_id);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);

                // 今後インライン精度検査の結果と入れ替える
                $dummy = ['-','-','-','-'];
                foreach ($dummy as $n => $part) {
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_inline), $A3['y2']+($row)*$A3['th'], $part);
                }

                foreach ($family->sortBy('part_type_id') as $n => $part) {
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($n*$dj_gaikan), $A3['y2']+($row)*$A3['th'], $part->status == 1 ? '○' : '×');
                }

                foreach ($failureTypes as $i => $f) {
                    foreach ($family as $n => $part) {
                        $sum = $part->failurePositions->filter(function($fp) use ($f) {
                            return $fp->failure_id == $f['id'];
                        })->count();

                        $tcpdf->Text($A3['x0']+array_sum($d)+($i*$fd)+($fdj*$n), $A3['y2']+($row)*$A3['th'], $sum);
                    }
                }

                $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y2']+($row)*$A3['th'], $createdAt);
            }

            $tcpdf->Text(210, 286, 'page '.($page+1));
        }

        return $tcpdf;
    }

    protected function forHolingSmall($families, $vehicle, $process_name, $ition_name, $date, $tyoku, $failureTypes)
    {
        $tcpdf = $this->createTCPDF();
        $now = Carbon::now();

        $x0 = 6;
        $x1 = 102;
        $y0 = 8;
        $y1 = 10;
        $y2 = 20;
        $d = [10, 12, 6];
        $fd = 17;
        $th = 5;

        // $ok = array_reduce($body, function($carry, $item){
        //     return $carry += $item[5] == '○' ? 1 : 0;
        // });

        // $ng = array_reduce($body, function($carry, $item){
        //     return $carry += $item[5] == '×' ? 1 : 0;
        // });

        $OK = [40, 40, 40, 40, 40];
        $NG = [10, 10, 10, 10, 10];

        // foreach (array_chunk($families, 100) as $page => $value) {
        for ($page=1; $page < 2; $page++) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 10);
            $tcpdf->Text($x0, $y0, $vehicle);
            $tcpdf->Text(40, $y0, $process_name);
            $tcpdf->Text(76, $y0, $ition_name);
            $tcpdf->Text(120, $y0, $date->format('Y/m/d'));
            $tcpdf->Text(150, $y0, $tyoku);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(162, $y0+1, '印刷日時　'.$now->format('Y/m/d H:i:s'));

            $tcpdf->Line(42, 28, 42, 281);
            $tcpdf->Line(84, 28, 84, 281);
            $tcpdf->Line(126, 28, 126, 281);
            $tcpdf->Line(168, 28, 168, 281);

            $tcpdf->SetFont('kozgopromedium', '', 8);
            if ($page == 1) {
                $tcpdf->Rect($x0, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+1, $y0+$y1, '○');
                $tcpdf->Text($x0+2+5, $y0+$y1, 48);

                $tcpdf->Rect($x0+1+5+10, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+1+5+10+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+1+5+10+1, $y0+$y1, '×');
                $tcpdf->Text($x0+1+5+10+1+5, $y0+$y1, 2);
            }

            $tcpdf->SetFont('kozgopromedium', '', 5);
            $tcpdf->Text($x0, $y0+$y2, '67119 アッパー');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2+4, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2+4, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2+4, '判定');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2+4, '時間');

            foreach ($families['2'] as $row => $part) {

                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y2+4+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y2+4+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y2+4+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y2+4+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->SetFont('kozgopromedium', '', 8);
            if ($page == 1) {
                $tcpdf->Rect($x0+42, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+1, $y0+$y1, '○');
                $tcpdf->Text($x0+42+2+5, $y0+$y1, 48);

                $tcpdf->Rect($x0+42+1+5+10, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+1+5+10+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+1+5+10+1, $y0+$y1, '×');
                $tcpdf->Text($x0+42+1+5+10+1+5, $y0+$y1, 2);
            }

            $tcpdf->SetFont('kozgopromedium', '', 5);
            $tcpdf->Text($x0+42, $y0+$y2, '67175 サイドアッパーRH');
            $tcpdf->Text($x0+42+array_sum(array_slice($d,0,0)), $y0+$y2+4, 'パネルID');
            $tcpdf->Text($x0+42+array_sum(array_slice($d,0,1)), $y0+$y2+4, '検査者');
            $tcpdf->Text($x0+42+array_sum(array_slice($d,0,2)), $y0+$y2+4, '判定');
            $tcpdf->Text($x0+42+array_sum(array_slice($d,0,3)), $y0+$y2+4, '時間');

            foreach ($families['3'] as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+42+array_sum(array_slice($d,0,0)), $y0+$y2+4+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+42+array_sum(array_slice($d,0,1)), $y0+$y2+4+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+42+array_sum(array_slice($d,0,2)), $y0+$y2+4+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+42+array_sum(array_slice($d,0,3)), $y0+$y2+4+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->SetFont('kozgopromedium', '', 8);
            if ($page == 1) {
                $tcpdf->Rect($x0+42+42, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+42+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+42+1, $y0+$y1, '○');
                $tcpdf->Text($x0+42+42+2+5, $y0+$y1, 48);

                $tcpdf->Rect($x0+42+42+1+5+10, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+42+1+5+10+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+42+1+5+10+1, $y0+$y1, '×');
                $tcpdf->Text($x0+42+42+1+5+10+1+5, $y0+$y1, 2);
            }

            $tcpdf->SetFont('kozgopromedium', '', 5);
            $tcpdf->Text($x0+42+42, $y0+$y2, '67176 サイドアッパーLH');
            $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,0)), $y0+$y2+4, 'パネルID');
            $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,1)), $y0+$y2+4, '検査者');
            $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,2)), $y0+$y2+4, '判定');
            $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,3)), $y0+$y2+4, '時間');

            foreach ($families['4'] as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,0)), $y0+$y2+4+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,1)), $y0+$y2+4+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,2)), $y0+$y2+4+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+42+42+array_sum(array_slice($d,0,3)), $y0+$y2+4+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->SetFont('kozgopromedium', '', 8);
            if ($page == 1) {
                $tcpdf->Rect($x0+42+42+42, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+42+42+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+42+42+1, $y0+$y1, '○');
                $tcpdf->Text($x0+42+42+42+2+5, $y0+$y1, 48);

                $tcpdf->Rect($x0+42+42+42+1+5+10, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+42+42+1+5+10+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+42+42+1+5+10+1, $y0+$y1, '×');
                $tcpdf->Text($x0+42+42+42+1+5+10+1+5, $y0+$y1, 2);
            }

            $tcpdf->SetFont('kozgopromedium', '', 5);
            $tcpdf->Text($x0+42+42+42, $y0+$y2, '67177 サイドロアRH');
            $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,0)), $y0+$y2+4, 'パネルID');
            $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,1)), $y0+$y2+4, '検査者');
            $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,2)), $y0+$y2+4, '判定');
            $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,3)), $y0+$y2+4, '時間');

            foreach ($families['5'] as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,0)), $y0+$y2+4+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,1)), $y0+$y2+4+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,2)), $y0+$y2+4+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+42+42+42+array_sum(array_slice($d,0,3)), $y0+$y2+4+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->SetFont('kozgopromedium', '', 8);
            if ($page == 1) {
                $tcpdf->Rect($x0+42+42+42+42, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+42+42+42+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+42+42+42+1, $y0+$y1, '○');
                $tcpdf->Text($x0+42+42+42+42+2+5, $y0+$y1, 48);

                $tcpdf->Rect($x0+42+42+42+42+1+5+10, $y0+$y1-0.3, 5, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Rect($x0+42+42+42+42+1+5+10+5, $y0+$y1-0.3, 8, 4, 'DF', ['LTRB' => ['color' => [0, 0, 0]]], [255,255,255]);
                $tcpdf->Text($x0+42+42+42+42+1+5+10+1, $y0+$y1, '×');
                $tcpdf->Text($x0+42+42+42+42+1+5+10+1+5, $y0+$y1, 2);
            }

            $tcpdf->SetFont('kozgopromedium', '', 5);
            $tcpdf->Text($x0+42+42+42+42, $y0+$y2, '67178 サイドロアLH');
            $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,0)), $y0+$y2+4, 'パネルID');
            $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,1)), $y0+$y2+4, '検査者');
            $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,2)), $y0+$y2+4, '判定');
            $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,3)), $y0+$y2+4, '時間');

            foreach ($families['6'] as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,0)), $y0+$y2+4+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,1)), $y0+$y2+4+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,2)), $y0+$y2+4+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+42+42+42+42+array_sum(array_slice($d,0,3)), $y0+$y2+4+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->Text(103, 287, 'page '.($page));
        }

        $x0 = 10;
        $y0 = 10;
        $y1 = 12;
        $d = [20, 22, 20, 20];
        $fd = 18;
        $th = 6;

        $fn = count($failureTypes);

        foreach ($families['2']->chunk(40) as $page => $parts) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 10);
            $tcpdf->Text($x0, $y0, $vehicle);
            $tcpdf->Text(40, $y0, $process_name);
            $tcpdf->Text(76, $y0, $ition_name);
            $tcpdf->Text(120, $y0, 'アッパー');
            $tcpdf->Text(160, $y0, $date->format('Y/m/d'));
            $tcpdf->Text(190, $y0, $tyoku);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(350, $y0+1, '印刷日時　'.$now->format('Y/m/d H:i:s'));

            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '精度判定');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, '外観判定');

            foreach ($failureTypes as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1, '登録時間');

            foreach ($parts->values() as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');

                foreach ($failureTypes as $i => $f) {
                    $sum = $part->failurePositions->filter(function($fp) use ($f) {
                        return $fp->failure_id == $f['id'];
                    })->count();

                    $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+$y1+($row+1)*$th, $sum);
                }

                $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        foreach ($families['3']->chunk(40) as $page => $parts) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 10);
            $tcpdf->Text($x0, $y0, $vehicle);
            $tcpdf->Text(40, $y0, $process_name);
            $tcpdf->Text(76, $y0, $ition_name);
            $tcpdf->Text(120, $y0, 'サイドアッパーRH');
            $tcpdf->Text(160, $y0, $date->format('Y/m/d'));
            $tcpdf->Text(190, $y0, $tyoku);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(350, $y0+1, '印刷日時　'.$now->format('Y/m/d H:i:s'));

            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '精度判定');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, '外観判定');

            foreach ($failureTypes as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1, '登録時間');

            foreach ($parts->values() as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');

                foreach ($failureTypes as $i => $f) {
                    $sum = $part->failurePositions->filter(function($fp) use ($f) {
                        return $fp->failure_id == $f['id'];
                    })->count();

                    $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+$y1+($row+1)*$th, $sum);
                }

                $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        foreach ($families['4']->chunk(40) as $page => $parts) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 10);
            $tcpdf->Text($x0, $y0, $vehicle);
            $tcpdf->Text(40, $y0, $process_name);
            $tcpdf->Text(76, $y0, $ition_name);
            $tcpdf->Text(120, $y0, 'サイドアッパーLH');
            $tcpdf->Text(160, $y0, $date->format('Y/m/d'));
            $tcpdf->Text(190, $y0, $tyoku);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(350, $y0+1, '印刷日時　'.$now->format('Y/m/d H:i:s'));

            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '精度判定');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, '外観判定');

            foreach ($failureTypes as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1, '登録時間');

            foreach ($parts->values() as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');

                foreach ($failureTypes as $i => $f) {
                    $sum = $part->failurePositions->filter(function($fp) use ($f) {
                        return $fp->failure_id == $f['id'];
                    })->count();

                    $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+$y1+($row+1)*$th, $sum);
                }

                $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        foreach ($families['5']->chunk(40) as $page => $parts) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 10);
            $tcpdf->Text($x0, $y0, $vehicle);
            $tcpdf->Text(40, $y0, $process_name);
            $tcpdf->Text(76, $y0, $ition_name);
            $tcpdf->Text(120, $y0, 'サイドロアRH');
            $tcpdf->Text(160, $y0, $date->format('Y/m/d'));
            $tcpdf->Text(190, $y0, $tyoku);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(350, $y0+1, '印刷日時　'.$now->format('Y/m/d H:i:s'));

            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '精度判定');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, '外観判定');

            foreach ($failureTypes as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1, '登録時間');

            foreach ($parts->values() as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');

                foreach ($failureTypes as $i => $f) {
                    $sum = $part->failurePositions->filter(function($fp) use ($f) {
                        return $fp->failure_id == $f['id'];
                    })->count();

                    $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+$y1+($row+1)*$th, $sum);
                }

                $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        foreach ($families['6']->chunk(40) as $page => $parts) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $tcpdf->SetFont('kozgopromedium', '', 10);
            $tcpdf->Text($x0, $y0, $vehicle);
            $tcpdf->Text(40, $y0, $process_name);
            $tcpdf->Text(76, $y0, $ition_name);
            $tcpdf->Text(120, $y0, 'サイドロアLH');
            $tcpdf->Text(160, $y0, $date->format('Y/m/d'));
            $tcpdf->Text(190, $y0, $tyoku);
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text(350, $y0+1, '印刷日時　'.$now->format('Y/m/d H:i:s'));

            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1, 'パネルID');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1, '検査者');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1, '精度判定');
            $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1, '外観判定');

            foreach ($failureTypes as $i => $f) {
                $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+12, $f['name']);
            }

            $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1, '登録時間');

            foreach ($parts->values() as $row => $part) {
                $createdBy = explode(',', $part->created_by);

                $tcpdf->Text($x0+array_sum(array_slice($d,0,0)), $y0+$y1+($row+1)*$th, $part->panel_id);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,1)), $y0+$y1+($row+1)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($x0+array_sum(array_slice($d,0,2)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');
                $tcpdf->Text($x0+array_sum(array_slice($d,0,3)), $y0+$y1+($row+1)*$th, $part->status == 1 ? '○' : '×');

                foreach ($failureTypes as $i => $f) {
                    $sum = $part->failurePositions->filter(function($fp) use ($f) {
                        return $fp->failure_id == $f['id'];
                    })->count();

                    $tcpdf->Text($x0+array_sum($d)+$i*$fd, $y0+$y1+($row+1)*$th, $sum);
                }

                $tcpdf->Text($x0+array_sum($d)+$fn*$fd, $y0+$y1+($row+1)*$th, $part->created_at->format('H:i'));
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }


        $pdf_path = 'report_'.'680A'.'_'.$now->format('Ymd').'_m001_inner_w';
        $tcpdf->output($pdf_path, 'I');
    }

    public function report($itionGId, Request $request)
    {
        $validator = app('validator')->make(
            $request->all(),
            [
                'date' => ['required'],
                'itorG' => ['required']
            ]
        );

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation error', $validator->errors());
        }

        $date = Carbon::createFromFormat('Y/m/d H:i:s', $request->date.' 00:00:00');
        $start = $date->addHours(2);
        $end = $date->copy()->addDay(1);
        $itorG = $request->itorG;

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

        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failureTypes);

        if (intval($itionGId) == 3 || intval($itionGId) == 9) {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        ->where('inspection_group_id', '=', $itionGId)
                        ->whereIn('inspector_group', $itorG);
                })
                ->select(['parts.*', 'pp.status', 'pg.page_type_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.inspected_at'])
                ->orderBy('if.inspected_at', 'if.created_at')
                ->get();

            return "インライン検査は未実装";
        }
        elseif (intval($itionGId) == 1 || intval($itionGId) == 2 || intval($itionGId) == 5 || intval($itionGId) == 6) {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        // ->whereIn('inspection_group_id', $itionGId)
                        ->whereIn('inspection_group_id', [$itionGId, 7])
                        ->whereIn('inspector_group', $itorG);
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
                ])
                ->get();

            // return $parts;

            // switch (intval($itionGId)) {
            //     case 1: $this->forMoldingInner($parts, '680A', '成型工程ライン１', 'アウター外観検査結果', $date, implode(',', $itorG), $failureTypes); break;
            //     case 2: $this->forMoldingInner($parts, '680A', '成型工程ライン２', 'アウター外観検査結果', $date, implode(',', $itorG), $failureTypes); break;
            //     case 5: $this->forMoldingSmall($parts, '680A', '成型工程ライン１', 'アウター外観検査結果', $date, implode(',', $itorG), $failureTypes); break;
            //     case 6: $this->forMoldingSmall($parts, '680A', '成型工程ライン２', 'アウター外観検査結果', $date, implode(',', $itorG), $failureTypes); break;
            // }

            $this->now = Carbon::now();
            switch (intval($itionGId)) {
                case 1: 
                    $this->vehicle = '680A';
                    $this->process_name = '成型工程ライン１';
                    $this->ition_name = 'インナー外観検査結果';
                    $this->print_date = $date->format('Y/m/d');
                    $this->tyoku = implode(',', $itorG);
                    $this->file_name = 'report_'.'680A'.'_'.$this->now->format('Ymd').'_m001_inner';
                    // $this->forMoldingInner($parts, $failureTypes);
                    break;
                case 2: 
                    $this->vehicle = '680A';
                    $this->process_name = '成型工程ライン２';
                    $this->ition_name = 'インナー外観検査結果';
                    $this->print_date = $date->format('Y/m/d');
                    $this->tyoku = implode(',', $itorG);
                    $this->file_name = 'report_'.'680A'.'_'.$this->now->format('Ymd').'_m002_inner';
                    // $this->forMoldingInner($parts, $failureTypes);
                    break;
                case 5:
                    $this->vehicle = '680A';
                    $this->process_name = '成型工程ライン１';
                    $this->ition_name = 'アウター外観検査結果';
                    $this->print_date = $date->format('Y/m/d');
                    $this->tyoku = implode(',', $itorG);
                    $tcpdf = $this->forMoldingOuter($parts, $failureTypes);
                    $pdf_path = 'report_'.'680A'.'_'.$this->now->format('Ymd').'_m001_inner';
                    break;
                case 6:
                    $this->vehicle = '680A';
                    $this->process_name = '成型工程ライン２';
                    $this->ition_name = 'アウター外観検査結果';
                    $this->print_date = $date->format('Y/m/d');
                    $this->tyoku = implode(',', $itorG);
                    $this->file_name = 'report_'.'680A'.'_'.$this->now->format('Ymd').'_m002_inner';
                    $this->forMoldingInner($parts, $failureTypes);
                    break;
            }

        $tcpdf->output($pdf_path, 'I');

        }
        else {
            $parts = Part::where('parts.created_at', '<', $end)
                ->join('part_page as pp', function($join) {
                    $join->on('pp.part_id', '=', 'parts.id');
                })
                ->join('pages as pg', function($join) {
                    $join->on('pg.id', '=', 'pp.page_id');
                })
                ->join('inspection_families as if', function($join) use ($itionGId, $itorG) {
                    $join->on('if.id', '=', 'pg.family_id')
                        // ->whereIn('inspection_group_id', $itionGId)
                        ->whereIn('inspection_group_id', [$itionGId, 7])
                        ->whereIn('inspector_group', $itorG);
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
                ])
                ->get()
                ->groupBy('part_type_id');
        }
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

