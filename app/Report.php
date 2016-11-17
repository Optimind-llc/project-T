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
    protected $line;
    protected $division;
    protected $serch_date;
    protected $tyoku;
    protected $now;

    protected $failureTypes;
    protected $ModificationTypes;

    public function setInfo($v, $p, $i, $l, $di, $d, $t)
    {
        if (is_null($l)) {
            $process_name = $p.'工程';
        }
        else {
            $process_name = $p.'工程ライン'.$l;
        }

        $this->vehicle = $v;
        $this->process_name = $process_name;
        $this->ition_name = $di.$i;
        $this->line = $l;
        $this->serch_date = $d->format('Y/m/d');
        $this->tyoku = $t;
        $this->now = Carbon::now();
    }

    public function setFailureTypes($failureTypes)
    {
        $this->failureTypes = $failureTypes;
    }

    public function setModificationTypes($modificationTypes)
    {
        $this->modificationTypes = $modificationTypes;
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
        $tcpdf->Text($A4['x0']+$A4['header']['date'], $A4['y0'], $this->serch_date);
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
        $tcpdf->Text($A3['x0']+$A3['header']['date'], $A3['y0'], $this->serch_date);
        $tcpdf->Text($A3['x0']+$A3['header']['tyoku'], $A3['y0'], $this->tyoku);
        $tcpdf->Text($A3['x0']+$A3['header']['now'], $A3['y0'], '印刷日時　'.$this->now->format('Y/m/d H:i:s'));
    }

    public function forGaikan($parts)
    {
        $tcpdf = $this->createTCPDF();

        $failureTypes = $this->failureTypes;
        $families = $parts->groupBy('family_id')->values();
        $part_types = $parts->sortBy('part_type_id')->values()->groupBy('part_type_id');
        $parts_obj = PartType::whereIn('id', $part_types->keys())
            ->select(['id', 'pn', 'name', 'short_name', 'sort'])
            ->orderBy('sort')
            ->get();

        $formated_families = $families->map(function($f) {
            return $f->keyBy('part_type_id')->map(function($part) {
                $exc = explode(',', $part->created_by);
                $inspectedBy = count($exc) > 1 ? $exc[1] : $exc[0];

                if ($part->updated_by) {
                    $exu = explode(',', $part->created_by);
                    $inspectedBy = count($exu) > 1 ? $exu[1] : $exu[0];
                }

                $iStatus = $part->pages->count() == 0 ? null : $part->pages->first()->pivot->status;
                $gStatus = $part->status;

                if (is_null($iStatus)) {
                    $status = $gStatus;
                }
                else {
                    $status = ($gStatus == 1 && $iStatus == 1) ? 1 : 0;
                }

                return [
                    'panelId' => $part->panel_id,
                    'iStatus' => $iStatus,
                    'gStatus' => $gStatus,
                    'status' => $status,
                    'inspectedBy' => $inspectedBy,
                    'time' => $part->updated_at,
                    'comment' => '任意のコメ..',
                    'failures' => $part->failurePositions->map(function($fp) {
                        return $fp->failure_id;
                    }),
                ];
            });
        });


        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [8, 18, 20, 38];
        $th = 5;
        $dj = 7;
        $dhj = 36;
        $dhj1 = 5;
        $dhj2 = $dhj/2 - $dhj1;
        $hhj = 4;

        foreach ($formated_families->chunk(100) as $p => $families100) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);

                foreach ($parts_obj as $n => $part_obj) {
                    $sum1 = $part_types[$part_obj->id]->filter(function($p) {
                        if ($p->pages->count() == 0) {
                            return $p->status == 1;
                        }
                        return $p->status == 1 && $p->pages->first()->pivot->status == 1;
                    })->count();

                    $sum0 = $part_types[$part_obj->id]->count() - $sum1;

                    $tcpdf->MultiCell($dhj, $hhj, $part_obj->short_name.'：'.$part_obj->name, 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1']);
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
                    $panelId = $parts->first()['panelId'];
                    $inspectedBy = $parts->first()['inspectedBy'];
                    $time = $parts->first()['time']->format('H:i');

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $panelId);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+($row)*$th, $inspectedBy);

                    foreach ($parts_obj as $n => $part_obj) {
                        $part = $parts[$part_obj->id];
                        $status = $part['status'] == 1 ? '○' : '×';

                        $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y3']+($row)*$th, $status);
                    }
                    
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, $time);
                }
            }

            $tcpdf->Line(106, 28, 106, 286);
            $tcpdf->Text(103, 287, 'page '.($p+1));
        }

        /*
         * Render A3
         */
        $A3 = config('report.A3');
        $d = [6, 18, 20, 26, 26];
        $d_comment = 20;
        $d_date = 14;

        $fn = count($failureTypes);
        $fd = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_comment - $d_date)/$fn;
        $fdj = $fd*0.8/$parts_obj->count();

        foreach ($formated_families->chunk(40) as $page => $families40) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $this->renderA3Header($tcpdf);

            $tcpdf->SetFont('kozgopromedium', '', 8);
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

            foreach ($failureTypes as $fi => $f) {
                $tcpdf->Text($A3['x0']+array_sum($d)+($fd*$fi), $A3['y1'], $f['name']);
            }

            $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y1'], 'コメント');
            $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y1'], '登録時間');

            foreach ($families40->values() as $row => $parts) {
                $panelId = $parts->first()['panelId'];
                $inspectedBy = $parts->first()['inspectedBy'];
                $time = $parts->first()['time']->format('H:i');

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $row+($page*40)+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panelId);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $inspectedBy);

                foreach ($parts_obj_except_u as $n => $part_obj) {
                    $part = $parts[$part_obj->id];
                    $status = is_null($part['iStatus']) ? ' -' : ($part['iStatus'] == 1 ? '○' : '×');

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_inline), $A3['y2']+($row)*$A3['th'], $status);
                }

                foreach ($parts_obj as $n => $part_obj) {
                    $part = $parts[$part_obj->id];
                    $status = $part['gStatus'] == 1 ? '○' : '×';

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($n*$dj_gaikan), $A3['y2']+($row)*$A3['th'], $status);
                }

                foreach ($failureTypes as $i => $ft) {
                    foreach ($parts_obj as $n => $part_obj) {
                        $part = $parts[$part_obj->id];
                        $sum = $part['failures']->filter(function($f) use ($ft) {
                            return $f == $ft['id'];
                        })->count();

                        $tcpdf->Text($A3['x0']+array_sum($d)+($i*$fd)+($fdj*$n), $A3['y2']+($row)*$A3['th'], $sum);
                    }
                }

                $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y2']+($row)*$A3['th'], '任意のコメ..');
                $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $timeChunks = config('report.timeChunks');
        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedFamilies = [];
        foreach ($formated_families as $formated_family) {
            $time = $formated_family->first()['time']
                ->subHours($base['H'])
                ->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']) - 1;
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']) - 1;

                if (!array_key_exists($tc['label'], $timeChunkedFamilies)) {
                    $timeChunkedFamilies[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedFamilies[$tc['label']][] = $formated_family->map(function($part) {
                        return $part['failures'];
                    });
                }
            }
        }

        $timeChunksSum = collect($timeChunkedFamilies)->map(function($chunk) use ($parts_obj) {
            $collected = collect($chunk);

            $plucked = [];
            foreach ($parts_obj as $part_obj) {
                $plucked[$part_obj->id] = array_count_values($collected->pluck($part_obj->id)->flatten()->toArray());
            }

            return collect($plucked);
        });

        $fd = ($A3['xmax'] - $A3['x0']*2 - 24)/$fn;
        $fdj = $fd*0.85/$parts_obj->count();

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        // Render table header
        $tcpdf->SetFont('kozgopromedium', '', 8);
        foreach ($failureTypes as $i => $f) {
            $tcpdf->Text($A3['x0']+24+($i*$fd), $A3['y1'], $f['name']);
        }

        foreach ($parts_obj as $n => $part_obj) {
            $tcpdf->StartTransform();
            $tcpdf->Rotate(90, $A3['x0']+24+$n*$fdj-1, $A3['y1']+9);
            $tcpdf->Text($A3['x0']+24+$n*$fdj-3, $A3['y1']+9, $part_obj->short_name);
            $tcpdf->StopTransform();
        }

        $all_sum = [];
        // Render table body
        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);

            if (count($sum) != 0) {
                $ip = 0;
                foreach ($sum as $part_sum) {
                    foreach ($failureTypes as $i => $f) {
                        if (!array_key_exists($f['id'], $part_sum)) {
                            $f_sum = 0;
                        }
                        else {
                            $f_sum = $part_sum[$f['id']];
                        }
                        
                        $tcpdf->Text($A3['x0']+24+($i*$fd)+$fdj*$ip, $A3['y2']+$n*$th, $f_sum);

                        if (!array_key_exists($ip, $all_sum)) {
                            $all_sum[$ip] = [];
                        }

                        if (!array_key_exists($i, $all_sum[$ip])) {
                            $all_sum[$ip][$i] = $f_sum;
                        }
                        else {
                            $all_sum[$ip][$i] = $all_sum[$ip][$i] + $f_sum;
                        }
                    }
                    $ip += 1;
                }
            }

            $n = $n+1;
        }

        $tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, '直合計');
        $ip = 0;
        foreach ($all_sum as $part_sum) {
            foreach ($part_sum as $i => $val) {
                $tcpdf->Text($A3['x0']+24+($i*$fd)+$fdj*$ip, $A3['y2']+$n*$th, $val);
            }
            $ip += 1;
        }

        return $tcpdf;
    }

    public function forAnaSingle($parts)
    {
        $tcpdf = $this->createTCPDF();

        $failureTypes = $this->failureTypes;
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
        $margin = 4;
        $d_comment = 14;
        $d_date = 8;

        $fd = 4;

        $part_type_id = $parts->first()->first()->part_type_id;
        $all_holes = Hole::where('part_type_id', '=', $part_type_id)->orderBy('label')->get();
        $hn = $all_holes->count();
        $d_hole_max = 16;
        $d_hole = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $fd*$fn - $margin - $d_comment - $d_date)/$hn;

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
                $tcpdf->StartTransform();
                $tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole)+1, $A3['y1_ana']+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y1_ana'], $hole->label);
                $tcpdf->StopTransform();
            }

            foreach ($failureTypes as $i => $f) {
                $tcpdf->StartTransform();
                $tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$i)+1, $A3['y1_ana']+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$i), $A3['y1_ana'], $f['name']);
                $tcpdf->StopTransform();
            }

            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y1_ana'], 'コメント');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y1_ana'], '登録時間');

            if ($p == 0) {
                $tcpdf->Text($A3['x0'], $A3['y1'], '○ : 公差内　× : 穴径大　△ : 穴径小　* : 手直し');
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

                // Render failures
                foreach ($failureTypes as $i => $f) {
                    // $sum = $part->failurePositions->filter(function($fp) use ($f) {
                    //     return $fp->failure_id == $f['id'];
                    // })->count();
                    $tcpdf->Text($A3['x0']+array_sum($d)+($d_hole*$hn)+$margin+($fd*$i), $A3['y2']+$row*$A3['th'], 0);
                }


                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y2']+($row)*$A3['th'], '任意のコメ..');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y2']+($row)*$A3['th'], $updatedAt);

                // $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y1_ana'], 'コメント');
                // $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y1_ana'], '登録時間');

            }

            $tcpdf->Text(210, 286, 'page '.($p+1));
        }



        $formated_parts = $parts->map(function($part) {
            return [
                'holes' => $part->first()->pages->reduce(function($carry, $p) {
                    return $carry->merge($p->holePages->map(function($h) {
                        return $h->status;
                    }));
                }, collect([])),
                'time' => $part->first()->pages->first()->created_at->format('Hi')
            ];
        });

        $timeChunks = [
            '6:30〜7:30' => [],
            '7:30〜8:30' => [],
            '8:40〜9:40' => [],
            '9:40〜10:40' => [],
            '11:25〜12:25' => [],
            '12:25〜13:25' => [],
            '13:35〜14:35' => [],
            '14:45〜15:15' => [],
            '16:15〜17:15' => [],
            '17:15〜18:15' => [],
            '18:25〜19:25' => [],
            '19:25〜20:25' => [],
            '21:20〜22:10' => [],
            '22:10〜23:20' => [],
            '23:20〜0:20' => [],
            '0:30〜1:00' => []
        ];

        foreach ($formated_parts as $formated_part) {
            $time = $formated_part['time'];

            if ($time >= 630 && $time < 730) {
                $timeChunks['6:30〜7:30'][] = $formated_part['holes'];
            }
            elseif ($time >= 730 && $time < 840) {
                $timeChunks['7:30〜8:30'][] = $formated_part['holes'];;
            }
            elseif ($time >= 840 && $time < 940) {
                $timeChunks['8:40〜9:40'][] = $formated_part['holes'];;
            }
            elseif ($time >= 940 && $time < 1125) {
                $timeChunks['9:40〜10:40'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1125 && $time < 1225) {
                $timeChunks['11:25〜12:25'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1225 && $time < 1335) {
                $timeChunks['12:25〜13:25'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1335 && $time < 1445) {
                $timeChunks['13:35〜14:35'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1445 && $time < 1615) {
                $timeChunks['14:45〜15:15'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1615 && $time < 1715) {
                $timeChunks['16:15〜17:15'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1715 && $time < 1825) {
                $timeChunks['17:15〜18:15'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1825 && $time < 1925) {
                $timeChunks['18:25〜19:25'][] = $formated_part['holes'];;
            }
            elseif ($time >= 1925 && $time < 2120) {
                $timeChunks['19:25〜20:25'][] = $formated_part['holes'];;
            }
            elseif ($time >= 2120 && $time < 2210) {
                $timeChunks['21:20〜22:10'][] = $formated_part['holes'];;
            }
            elseif ($time >= 2210 && $time < 2320) {
                $timeChunks['22:10〜23:20'][] = $formated_part['holes'];;
            }
            elseif ($time >= 2320 && $time < 2400) {
                $timeChunks['23:20〜0:20'][] = $formated_part['holes'];;
            }
            elseif ($time >= 0 && $time < 30) {
                $timeChunks['23:20〜0:20'][] = $formated_part['holes'];;
            }
            elseif ($time >= 30 && $time < 300) {
                $timeChunks['0:30〜1:00'][] = $formated_part['holes'];;
            }
        }

        $timeChunkSum = array_map(function($chunk) {
            return array_reduce($chunk, function($carry, $item) {
                if (count($carry) == 0) {
                    foreach ($item as $key => $value) {
                        $carry[$key] = [0 => 0, 1 => 0, 2 => 0];
                        switch ($value) {
                            case 0: $carry[$key][0] = 1; break;
                            case 1: $carry[$key][1] = 1; break;
                            case 2: $carry[$key][2] = 1; break;
                        }
                    }

                    return $carry;
                }
                foreach ($item as $key => $value) {
                    switch ($value) {
                        case 0: $carry[$key][0] += 1; break;
                        case 1: $carry[$key][1] += 1; break;
                        case 2: $carry[$key][2] += 1; break;
                    }
                }

                return $carry;
            }, []);
        }, $timeChunks);


        $hd = ($A3['xmax'] - $A3['x0']*2 - 24)/$hn;

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        // Render table header
        $tcpdf->SetFont('kozgopromedium', '', 6);
        foreach ($all_holes as $col => $h) {
            $tcpdf->Text($A3['x0']+24+($col*$hd), $A3['y1'], $h['label']);
        }

        // Render table body
        $all_sum = [];
        $n = 0;
        $tcpdf->SetFont('kozgopromedium', '', 7);
        foreach ($timeChunkSum as $key => $sum) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$n*3*$th, $key);
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th, '×');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th, '○');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th+$th, '△');
            if (count($sum) != 0) {
                foreach ($sum as $ih => $h_sum) {
                    foreach ($h_sum as $ij => $j_sum) {
                        $tcpdf->Text($A3['x0']+24+($ih*$hd), $A3['y2']+$n*3*$th+$ij*$th, $j_sum);

                        if (!array_key_exists($ih, $all_sum)) {
                            $all_sum[$ih] = $h_sum;
                        }
                        else {
                            $all_sum[$ih][$ij] += $j_sum;
                        }
                    }
                }
            }

            $n = $n+1;
        }

        $tcpdf->Text($A3['x0'], $A3['y2']+$n*3*$th, '直合計');
        $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th, '×');
        $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th, '○');
        $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th+$th, '△');
        foreach ($all_sum as $ih => $h_sum) {
            foreach ($h_sum as $ij => $j_sum) {
                $tcpdf->Text($A3['x0']+24+($ih*$hd), $A3['y2']+$n*3*$th+$ij*$th, $j_sum);
            }
        }

        return $tcpdf;
    }

    public function forAnaMulti($parts)
    {
        $tcpdf = $this->createTCPDF();

        $failureTypes = $this->failureTypes;
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


        foreach ($part_types as $id => $parts) {
            $all_holes = Hole::where('part_type_id', '=', $id)
                ->whereNotIn('figure_id', [9])
                ->orderBy('label')
                ->get();
            $hn = $all_holes->count();
            $hd_max = 16;
            $hd = ($A3['xmax'] - $A3['x0']*2 -24)/$hn;
            if ($hd > $hd_max) {
                $hd = $hd_max;
            }

            $formated_parts = $parts->map(function($part) use ($id) {
                return [
                    'holes' => $part->pages->first()->holePages->filter(function($hp) use ($id) {
                        return $hp->hole->part_type_id == $id;
                    })->map(function($hp) {
                        return $hp->status;
                    })->values(),
                    'time' => $part->first()->pages->first()->created_at->format('Hi')
                ];
            });

            $timeChunks = [
                '6:30〜7:30' => [],
                '7:30〜8:30' => [],
                '8:40〜9:40' => [],
                '9:40〜10:40' => [],
                '11:25〜12:25' => [],
                '12:25〜13:25' => [],
                '13:35〜14:35' => [],
                '14:45〜15:15' => [],
                '16:15〜17:15' => [],
                '17:15〜18:15' => [],
                '18:25〜19:25' => [],
                '19:25〜20:25' => [],
                '21:20〜22:10' => [],
                '22:10〜23:20' => [],
                '23:20〜0:20' => [],
                '0:30〜1:00' => []
            ];

            foreach ($formated_parts as $formated_part) {
                $time = $formated_part['time'];

                if ($time >= 630 && $time < 730) {
                    $timeChunks['6:30〜7:30'][] = $formated_part['holes'];
                }
                elseif ($time >= 730 && $time < 840) {
                    $timeChunks['7:30〜8:30'][] = $formated_part['holes'];;
                }
                elseif ($time >= 840 && $time < 940) {
                    $timeChunks['8:40〜9:40'][] = $formated_part['holes'];;
                }
                elseif ($time >= 940 && $time < 1125) {
                    $timeChunks['9:40〜10:40'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1125 && $time < 1225) {
                    $timeChunks['11:25〜12:25'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1225 && $time < 1335) {
                    $timeChunks['12:25〜13:25'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1335 && $time < 1445) {
                    $timeChunks['13:35〜14:35'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1445 && $time < 1615) {
                    $timeChunks['14:45〜15:15'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1615 && $time < 1715) {
                    $timeChunks['16:15〜17:15'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1715 && $time < 1825) {
                    $timeChunks['17:15〜18:15'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1825 && $time < 1925) {
                    $timeChunks['18:25〜19:25'][] = $formated_part['holes'];;
                }
                elseif ($time >= 1925 && $time < 2120) {
                    $timeChunks['19:25〜20:25'][] = $formated_part['holes'];;
                }
                elseif ($time >= 2120 && $time < 2210) {
                    $timeChunks['21:20〜22:10'][] = $formated_part['holes'];;
                }
                elseif ($time >= 2210 && $time < 2320) {
                    $timeChunks['22:10〜23:20'][] = $formated_part['holes'];;
                }
                elseif ($time >= 2320 && $time < 2400) {
                    $timeChunks['23:20〜0:20'][] = $formated_part['holes'];;
                }
                elseif ($time >= 0 && $time < 30) {
                    $timeChunks['23:20〜0:20'][] = $formated_part['holes'];;
                }
                elseif ($time >= 30 && $time < 300) {
                    $timeChunks['0:30〜1:00'][] = $formated_part['holes'];;
                }
            }

            $timeChunkSum = array_map(function($chunk) {
                return array_reduce($chunk, function($carry, $item) {
                    if (count($carry) == 0) {
                        foreach ($item as $key => $value) {
                            $carry[$key] = [0 => 0, 1 => 0, 2 => 0];
                            switch ($value) {
                                case 0: $carry[$key][0] = 1; break;
                                case 1: $carry[$key][1] = 1; break;
                                case 2: $carry[$key][2] = 1; break;
                            }
                        }

                        return $carry;
                    }
                    foreach ($item as $key => $value) {
                        switch ($value) {
                            case 0: $carry[$key][0] += 1; break;
                            case 1: $carry[$key][1] += 1; break;
                            case 2: $carry[$key][2] += 1; break;
                        }
                    }

                    return $carry;
                }, []);
            }, $timeChunks);


            $tcpdf->AddPage('L', 'A3');
            // Render page header
            $this->renderA3Header($tcpdf);

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 6);
            foreach ($all_holes as $col => $h) {
                $tcpdf->Text($A3['x0']+24+($col*$hd), $A3['y1'], $h['label']);
            }

            // Render table body
            $all_sum = [];
            $n = 0;
            $tcpdf->SetFont('kozgopromedium', '', 7);
            foreach ($timeChunkSum as $key => $sum) {
                $tcpdf->Text($A3['x0'], $A3['y2']+$n*3*$th, $key);
                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th, '×');
                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th, '○');
                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th+$th, '△');
                if (count($sum) != 0) {
                    foreach ($sum as $ih => $h_sum) {
                        foreach ($h_sum as $ij => $j_sum) {
                            $tcpdf->Text($A3['x0']+24+($ih*$hd), $A3['y2']+$n*3*$th+$ij*$th, $j_sum);

                            if (!array_key_exists($ih, $all_sum)) {
                                $all_sum[$ih] = $h_sum;
                            }
                            else {
                                $all_sum[$ih][$ij] += $j_sum;
                            }
                        }
                    }
                }

                $n = $n+1;
            }

            $tcpdf->Text($A3['x0'], $A3['y2']+$n*3*$th, '直合計');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th, '×');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th, '○');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*3*$th+$th+$th, '△');
            foreach ($all_sum as $ih => $h_sum) {
                foreach ($h_sum as $ij => $j_sum) {
                    $tcpdf->Text($A3['x0']+24+($ih*$hd), $A3['y2']+$n*3*$th+$ij*$th, $j_sum);
                }
            }

            // return $timeChunkSum;
        }



        // $timeChunks = [
        //     '6:30〜7:30' => [],
        //     '7:30〜8:30' => [],
        //     '8:40〜9:40' => [],
        //     '9:40〜10:40' => [],
        //     '11:25〜12:25' => [],
        //     '12:25〜13:25' => [],
        //     '13:35〜14:35' => [],
        //     '14:45〜15:15' => [],
        //     '16:15〜17:15' => [],
        //     '17:15〜18:15' => [],
        //     '18:25〜19:25' => [],
        //     '19:25〜20:25' => [],
        //     '21:20〜22:10' => [],
        //     '22:10〜23:20' => [],
        //     '23:20〜0:20' => [],
        //     '0:30〜1:00' => []
        // ];

        // foreach ($formated_parts as $formated_part) {
        //     $time = $formated_part['time'];

        //     if ($time >= 630 && $time < 730) {
        //         $timeChunks['6:30〜7:30'][] = $formated_part['holes'];
        //     }
        //     elseif ($time >= 730 && $time < 840) {
        //         $timeChunks['7:30〜8:30'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 840 && $time < 940) {
        //         $timeChunks['8:40〜9:40'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 940 && $time < 1125) {
        //         $timeChunks['9:40〜10:40'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1125 && $time < 1225) {
        //         $timeChunks['11:25〜12:25'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1225 && $time < 1335) {
        //         $timeChunks['12:25〜13:25'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1335 && $time < 1445) {
        //         $timeChunks['13:35〜14:35'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1445 && $time < 1615) {
        //         $timeChunks['14:45〜15:15'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1615 && $time < 1715) {
        //         $timeChunks['16:15〜17:15'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1715 && $time < 1825) {
        //         $timeChunks['17:15〜18:15'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1825 && $time < 1925) {
        //         $timeChunks['18:25〜19:25'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 1925 && $time < 2120) {
        //         $timeChunks['19:25〜20:25'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 2120 && $time < 2210) {
        //         $timeChunks['21:20〜22:10'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 2210 && $time < 2320) {
        //         $timeChunks['22:10〜23:20'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 2320 && $time < 2400) {
        //         $timeChunks['23:20〜0:20'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 0 && $time < 30) {
        //         $timeChunks['23:20〜0:20'][] = $formated_part['holes'];;
        //     }
        //     elseif ($time >= 30 && $time < 300) {
        //         $timeChunks['0:30〜1:00'][] = $formated_part['holes'];;
        //     }
        // }

        // $timeChunkSum = array_map(function($chunk) {
        //     return array_reduce($chunk, function($carry, $item) {
        //         if (count($carry) == 0) {
        //             foreach ($item as $key => $value) {
        //                 $carry[$key] = [0 => 0, 1 => 0, 2 => 0];
        //                 switch ($value) {
        //                     case 0: $carry[$key][0] = 1; break;
        //                     case 1: $carry[$key][1] = 1; break;
        //                     case 2: $carry[$key][2] = 1; break;
        //                 }
        //             }

        //             return $carry;
        //         }
        //         foreach ($item as $key => $value) {
        //             switch ($value) {
        //                 case 0: $carry[$key][0] += 1; break;
        //                 case 1: $carry[$key][1] += 1; break;
        //                 case 2: $carry[$key][2] += 1; break;
        //             }
        //         }

        //         return $carry;
        //     }, []);
        // }, $timeChunks);


        // $hd = ($A3['xmax'] - $A3['x0']*2 - 24)/$hn;

        // $tcpdf->AddPage('L', 'A3');
        // // Render page header
        // $this->renderA3Header($tcpdf);

        // // Render table header
        // $tcpdf->SetFont('kozgopromedium', '', 6);
        // foreach ($all_holes as $col => $h) {
        //     $tcpdf->Text($A3['x0']+24+($col*$hd), $A3['y1'], $h['label']);
        // }



        return $tcpdf;
    }

    public function forInline($parts)
    {
        $tcpdf = $this->createTCPDF();
        $part_types = $parts->sortBy('part_type_id')->values()->groupBy('part_type_id');

        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [8, 18, 20, 38];
        $th = 5;
        $dj = 7;
        $dhj = 40;
        $dhj1 = 7;
        $dhj2 = $dhj/2 - $dhj1;
        $hhj = 4;

        foreach ($parts->chunk(100) as $p => $parts100) {
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

                    $tcpdf->MultiCell($dhj, $hhj, $part_obj->name.'：'.$part_obj->pn, 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1'], true, 0, false, true, 0, 'M');
                    $tcpdf->MultiCell($dhj1, $hhj, 'OK', 1, 'C', 0, 1, $A4['x0']+($n*$dhj), $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj1, $hhj, 'NG', 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1+$dhj2, $A4['y1']+$hhj);
                    $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+($n*$dhj)+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
                    $n +=1;
                }
            }

            foreach ($parts100->values()->chunk(50) as $col => $parts50) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '出荷判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '時間');

                foreach ($parts_obj as $n => $part_obj) {
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y2']+4, $part_obj->short_name);
                }

                foreach ($parts50->values() as $row => $part) {
                    $panel_id = $part->panel_id;
                    $createdBy = explode(',', $part->created_by);
                    $updatedAt = $part->updated_at->format('H:i');

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $panel_id);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+($row)*$th, array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);

                    $status = $part->status == 1 ? 'OK' : 'NG';
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y3']+($row)*$th, $status);

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, $updatedAt);
                }
            }

            $tcpdf->Line(106, 28, 106, 286);
            $tcpdf->Text(103, 287, 'page '.($p+1));
        }

        return $tcpdf;
    }
}
