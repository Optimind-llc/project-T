<?php

namespace App;

use Carbon\Carbon;
use TCPDF;
// Models
use App\Models\PartType;
use App\Models\Hole;
use App\Models\Client\Part;

class Report
{
    protected $vehicle;
    protected $process_name;
    protected $ition_name;
    protected $print_date;
    protected $tyoku;
    protected $file_name;
    protected $now;

    public function setInfo($v, $p, $i, $d, $t)
    {
        $this->vehicle = $v;
        $this->process_name = $p;
        $this->ition_name = $i;
        $this->print_date = $d->format('Y/m/d');
        $this->tyoku = $t;
        $this->now = Carbon::now();
    }

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

    public function forGaikan($parts, $failureTypes)
    {
        $tcpdf = $this->createTCPDF();

        $families = $parts->groupBy('family_id')->values();
        $part_types = $parts->groupBy('part_type_id');
        $parts_obj = PartType::whereIn('id', $part_types->keys())
            ->select(['id', 'pn', 'name', 'short_name'])
            ->get()
            ->values();

        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [8, 18, 20, 38];
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

                $n = 0;
                foreach ($part_types as $id => $part_type) {
                    $part_obj = PartType::find($id);

                    $sum1 = $part_type->filter(function($p) {
                        if ($p->pages->count() == 0) {
                            return $p->status == 1;
                        }
                        return $p->status == 1 && $p->pages->first()->pivot->status == 1;
                    })->count();
                    $sum0 = $part_type->count() - $sum1;

                    $tcpdf->MultiCell($dhj, $hhj, $part_obj->short_name.'：'.$part_obj->name, 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1'], true, 0, false, true, 0, 'M');
                    $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1+$dhj2, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
                    $n +=1;
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
                    $updatedAt = $parts[0]->updated_at->format('H:i');

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $panel_id);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+($row)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);

                    foreach ($parts as $n => $part) {
                        if ($part->pages->count() == 0) {
                            $status = $part->status == 1 ? '○' : '×';
                        }
                        else {
                            $status = ($part->status == 1 && $part->pages->first()->pivot->status == 1) ? '○' : '×';
                        }

                        $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y3']+($row)*$th, $status);
                    }
                    
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, $updatedAt);
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
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1'], '精度出荷判定');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4)), $A3['y1'], '外観出荷判定');

            $parts_obj_except_u = $parts_obj->filter(function($p) {return $p->id != 2;})->values();
            $dj_inline = 26*0.85/$parts_obj_except_u->count();
            $dj_gaikan = 26*0.85/$parts_obj->count();

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

                $family_except_u = $family->filter(function($p) {
                    return $p->part_type_id != 2;
                })
                ->sortBy('part_type_id')
                ->values();

                foreach ($family_except_u as $n => $part) {
                    if ($part->pages->count() == 0) {
                        $status = ' -';
                    }
                    else {
                        $status = $part->pages->first()->pivot->status == 1 ? '○' : '×';
                    }
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_inline), $A3['y2']+($row)*$A3['th'], $status);
                }

                //ソートがちゃんと機能してる？
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

    public function forAnaSingle($parts, $failureTypes)
    {
        $tcpdf = $this->createTCPDF();
        $part_obj = PartType::find($parts->first()->part_type_id);
        $parts = $parts->groupBy('panel_id')->values();

        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [8, 24, 24, 18];
        $th = 5;
        $dj = 7;
        $dL = 36;
        $dhj1 = 5;
        $dhj2 = $dL/2 - $dhj1;
        $hhj = 4;

        foreach ($parts->chunk(100) as $p => $parts100) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);

                $sum1 = $parts->filter(function($p) {
                    return $p->first()->status == 1;
                })->count();
                $sum0 = $parts->count() - $sum1;

                $n = 0;
                $tcpdf->MultiCell($dL, $hhj, $part_obj->name.'：'.$part_obj->short_name, 1, 'C', 0, 1, $A4['x0']+($n*$dL), $A4['y1'], true, 0, false, true, 0, 'M');
                $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0']+($n*$dL), $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1+$dhj2, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
            }

            foreach ($parts100->chunk(50) as $col => $parts50) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '出荷判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '検査時間');

                foreach ($parts50->values() as $row => $part) {
                    $panel_id = $part->first()->panel_id;
                    $createdBy = explode(',', $part->first()->created_by);
                    $updatedAt = $part->first()->updated_at->format('H:i');
                    switch ($part->first()->status) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                    }
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $panel_id);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+($row)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y3']+($row)*$th, $status);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, $updatedAt);
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
        $d = [5, 12, 14, 11];
        $d_date = 8;

        $part_type_id = $parts->first()->first()->part_type_id;
        $all_holes = Hole::where('part_type_id', '=', $part_type_id)->orderBy('label')->get();
        $hn = $all_holes->count();
        $d_hole_max = 16;
        $d_hole = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_date)/$hn;

        foreach ($parts->chunk(40) as $p => $parts40) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $this->renderA3Header($tcpdf);

            $tcpdf->SetFont('kozgopromedium', '', 6);
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1_ana'], 'No.');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1_ana'], 'パネルID');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1_ana'], '検査者');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1_ana'], '出荷判定');

            foreach ($all_holes as $col => $hole) {
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y1_ana'], $hole->label);
            }

            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($hn*$d_hole), $A3['y1_ana'], '登録時間');

            if ($p == 0) {
                $tcpdf->Text($A3['header']['now'], $A3['y1'], '○ : 公差内　× : 穴径大　△ : 穴径小　* : 手直し');
            }

            foreach ($parts40->values() as $row => $part) {
                $panel_id = $part->first()->panel_id;
                $createdBy = explode(',', $part->first()->created_by);
                $updatedAt = $part->first()->updated_at->format('H:i');
                switch ($part->first()->status) {
                    case 0: $status = '×'; break;
                    case 1: $status = '○'; break;
                }

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $p*40+$row+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panel_id);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                $c_holes = $part->first()->pages->reduce(function($carry, $p) {
                    return $carry->merge($p->holePages);
                }, collect([]))
                ->keyBy('hole_id');

                foreach ($all_holes as $col => $hole) {
                    switch ($c_holes[$hole->id]->status) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                        case 2: $status = '△'; break;
                    }

                    if ($c_holes[$hole->id]->holeModification->count() != 0) {
                        $tcpdf->SetFont('kozgopromedium', '', 4);
                        $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole)+2, $A3['y2']+($row)*$A3['th']-1.5, '※');
                        $tcpdf->SetFont('kozgopromedium', '', 6);
                    }
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y2']+($row)*$A3['th'], $status);
                }

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($hn*$d_hole), $A3['y2']+($row)*$A3['th'], $updatedAt);
            }

            $tcpdf->Text(210, 286, 'page '.($p+1));
        }


        return $tcpdf;
    }

    public function forAnaMulti($parts, $failureTypes)
    {
        $tcpdf = $this->createTCPDF();

        $families = $parts->groupBy('family_id')->values();
        $part_types = $parts->groupBy('part_type_id');
        $parts_obj = PartType::whereIn('id', $part_types->keys())
            ->select(['id', 'pn', 'name', 'short_name'])
            ->get()
            ->values();

        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [4.4, 11, 12, 5];
        $th = 5;
        $dj = 7;
        $dL = ($A4['xmax'] - $A4['x0']*2)/$part_types->count();
        $dhj1 = 5;
        $dhj2 = $dL/2 - $dhj1;
        $hhj = 4;

        $max_pages = 1;
        $chunked_part_types = $part_types->map(function($pt) {
            return $pt->chunk(50);
        });

        foreach ($chunked_part_types as $id => $part_type) {
            if ($part_type->count() > $max_pages) {
                $max_pages = $part_type->count();
            }
        }

        for ($p=0; $p < $max_pages; $p++) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            // Render line
            for ($i=1; $i < $part_types->count(); $i++) { 
                $tcpdf->Line($A4['x0']+($i*$dL)-1, 28, $A4['x0']+($i*$dL)-1, 281);
            }

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);

                $n = 0;
                foreach ($part_types as $id => $part_type) {
                    $part_obj = PartType::find($id);

                    $sum1 = $part_type->filter(function($p) {
                        return $p->status == 1;
                    })->count();
                    $sum0 = $part_type->count() - $sum1;

                    $tcpdf->MultiCell($dL, $hhj, $part_obj->short_name.'：'.$part_obj->name, 1, 'C', 0, 1, $A4['x0']+($n*$dL), $A4['y1'], true, 0, false, true, 0, 'M');
                    $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0']+($n*$dL), $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1+$dhj2, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
                    $n +=1;
                }
            }

            $col = 0;
            foreach ($chunked_part_types as $id => $chunked_part_type) {
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$dL, $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$dL, $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$dL, $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$dL, $A4['y2'], '判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$dL, $A4['y2'], '時間');

                foreach ($chunked_part_type[$p]->values() as $row => $part) {
                    $createdBy = explode(',', $part->created_by);

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$dL, $A4['y3']+($row)*$A4['th'], $p*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$dL, $A4['y3']+($row)*$A4['th'], $part->panel_id);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$dL, $A4['y3']+($row)*$A4['th'], array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$dL, $A4['y3']+($row)*$A4['th'], $part->status == 1 ? '○' : '×');
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$dL, $A4['y3']+($row)*$A4['th'], $part->updated_at->format('H:i'));
                }

                $col += 1;
            }

            $tcpdf->Text(103, 287, 'page '.($p+1));
        }

        /*
         * Render A3
         */
        $fn = count($failureTypes);

        $A3 = config('report.A3');
        $d = [6, 18, 20, 26, 26];
        $d_date = 10;


        foreach ($part_types as $id => $part_type) {
            $all_holes = Hole::where('part_type_id', '=', $id)
                ->whereNotIn('figure_id', [9])
                ->orderBy('label')
                ->get();

            $hn = $all_holes->count();
            $d_hole_max = 16;
            $d_hole = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_date)/$hn;

            if ($d_hole > $d_hole_max) {
                $d_hole = $d_hole_max;
            }

            foreach ($part_type->chunk(40) as $p => $part_types40) {
                $tcpdf->AddPage('L', 'A3');

                // Render page header
                $this->renderA3Header($tcpdf);
                $part_name = PartType::find($id)->name;
                $tcpdf->Text($A3['x0']+$A3['header']['part'], $A3['y0'], $part_name);

                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1_ana'], 'No.');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1_ana'], 'パネルID');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1_ana'], '検査者');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1_ana'], '出荷判定');

                if ($p == 0) {
                    $tcpdf->Text($A3['header']['now'], $A3['y1'], '○ : 公差内　× : 穴径大　△ : 穴径小　* : 手直し');
                }

                foreach ($all_holes as $col => $hole) {
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y1_ana'], $hole->label);
                }

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($hn*$d_hole), $A3['y1_ana'], '登録時間');


                foreach ($part_types40->values() as $row => $part) {
                    $panel_id = $part->panel_id;
                    $createdBy = explode(',', $part->created_by);
                    $updatedAt = $part->updated_at->format('H:i');
                    switch ($part->status) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                    }

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $row+($p*40)+1);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panel_id);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                    $c_holes = $part->pages->first()->holePages->map(function($hp) {
                        return collect([
                            'hole_id' => $hp->hole_id,
                            'status' => $hp->status,
                            'label' => $hp->hole->label,
                            'hm' => $hp->holeModification
                        ]);
                    })
                    ->keyBy('hole_id');

                    foreach ($all_holes as $col => $hole) {
                        switch ($c_holes[$hole->id]['status']) {
                            case 0: $status = '×'; break;
                            case 1: $status = '○'; break;
                            case 2: $status = '△'; break;
                        }

                        if ($c_holes[$hole->id]['hm']->count() != 0) {  
                            $tcpdf->SetFont('kozgopromedium', '', 4);
                            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole)+2, $A3['y2']+($row)*$A3['th']-1.5, '※');
                            $tcpdf->SetFont('kozgopromedium', '', 8);
                        }
                        $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y2']+($row)*$A3['th'], $status);
                    }

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($hn*$d_hole), $A3['y2']+($row)*$A3['th'], $updatedAt);
                }

                $tcpdf->Text(210, 286, 'page '.($p+1));
            }
        }

        return $tcpdf;
    }
}
