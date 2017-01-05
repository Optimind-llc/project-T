<?php

namespace App;

use Carbon\Carbon;
use TCPDF;
// Models
use App\Models\PartType;
use App\Models\Hole;
use App\Models\Inline;
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
    protected $triple;

    protected $failureTypes;
    protected $modificationTypes;

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

        $switchingDate = Carbon::createFromFormat('Y-m-d H:i:s', '2016-12-3 00:00:00');
        if ($d->gte($switchingDate)) {
            $this->triple = true;
        }
        else {
            $this->triple = false;
        }
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
                    $exu = explode(',', $part->updated_by);
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

                if ($part->comment) {
                    $comment = mb_substr($part->comment, 0, 4, 'UTF-8').'..';
                }
                else {
                    if ($part->pp_comment) {
                        $comment = mb_substr($part->pp_comment, 0, 4, 'UTF-8').'..';
                    }
                    else {
                        $comment = '';
                    }
                }

                return [
                    'panelId' => $part->panel_id,
                    'iStatus' => $iStatus,
                    'gStatus' => $gStatus,
                    'status' => $status,
                    'inspectedBy' => $inspectedBy,
                    'time' => $part->created_at,
                    'comment' => $comment,
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

                    $sum0 = $part_types[$part_obj->id]->filter(function($p) {
                        if ($p->pages->count() == 0) {
                            return $p->status == 0;
                        }
                        return $p->status == 0 || $p->pages->first()->pivot->status == 0;
                    })->count();

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
                $tcpdf->Text($A4['x0']+$col*$A4['x1'], $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '出荷判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '登録時間');

                foreach ($parts_obj as $n => $part_obj) {
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1']+($n*$dj), $A4['y2']+4, $part_obj->short_name);
                }

                foreach ($families50->values() as $row => $parts) {
                    $panelId = $parts->first()['panelId'];
                    $inspectedBy = $parts->first()['inspectedBy'];
                    $time = $parts->first()['time']->format('H:i');

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $panelId);
                    $tcpdf->Text($A4['x0']+$col*$A4['x1'], $A4['y3']+($row)*$th, $inspectedBy);

                    foreach ($parts_obj as $n => $part_obj) {
                        $part = $parts[$part_obj->id];
                        switch ($part['status']) {
                            case 0: $status = '×'; break;
                            case 1: $status = '○'; break;
                            default: $status = ''; break;
                        }

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

            $tcpdf->SetFont('kozgopromedium', '', 7);
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
                    switch ($part['gStatus']) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                        default: $status = ''; break;
                    }

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

                foreach ($parts_obj as $n => $part_obj) {
                    $part = $parts[$part_obj->id];
                    if ($parts_obj->count() > 1) {
                        $comment = ($part['comment'] == '') ? '' : '有';
                        $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$n*($dj_gaikan-1), $A3['y2']+($row)*$A3['th'], $comment);
                    }
                    else {
                        $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$n*($dj_gaikan-1), $A3['y2']+($row)*$A3['th'], $part['comment']);                   
                    }
                }

                $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $th = 10;

        if ($this->triple) {
            $timeChunks = config('report.timeChunks2');
        } else {
            $timeChunks = config('report.timeChunks');
        }

        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedFamilies = [];
        foreach ($formated_families as $formated_family) {
            $time = $formated_family->first()['time']
                ->subHours($base['H'])
                ->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedFamilies)) {
                    $timeChunkedFamilies[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedFamilies[$tc['label']][] = $formated_family->map(function($part) {
                        return [
                            'iStatus' => $part['iStatus'],
                            'gStatus' => $part['gStatus'],
                            'status' => $part['status'],
                            'f' => $part['failures']
                        ];
                    });
                }
            }
        }

        $timeChunksSum = collect($timeChunkedFamilies)->map(function($chunk) use ($parts_obj) {
            $collected = collect($chunk);

            $plucked = [];
            foreach ($parts_obj as $part_obj) {
                $plucked[$part_obj->id] = [
                    'f' => array_count_values($collected->pluck($part_obj->id)->pluck('f')->flatten()->toArray()),
                    'iStatus' => [
                        0 => $collected->pluck($part_obj->id)->pluck('iStatus')->filter(function($s) {
                            return $s === 0;
                        })->count(),
                        1 => $collected->pluck($part_obj->id)->pluck('iStatus')->filter(function($s) {
                            return $s === 1;
                        })->count()
                    ],
                    'gStatus' => [
                        0 => $collected->pluck($part_obj->id)->pluck('gStatus')->filter(function($s) {
                            return $s === 0;
                        })->count(),
                        1 => $collected->pluck($part_obj->id)->pluck('gStatus')->filter(function($s) {
                            return $s === 1;
                        })->count()
                    ],
                    'status' => [
                        0 => $collected->pluck($part_obj->id)->pluck('status')->filter(function($s) {
                            return $s === 0;
                        })->count(),
                        1 => $collected->pluck($part_obj->id)->pluck('status')->filter(function($s) {
                            return $s === 1;
                        })->count()
                    ]
                ];

            }

            return collect($plucked);
        });

        $fd = ($A3['xmax'] - $A3['x0']*2 - 100)/$fn;
        $fdj = $fd*0.85/$parts_obj->count();

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        // Render table header
        $tcpdf->SetFont('kozgopromedium', '', 7);

        $tcpdf->Text($A3['x0']+24, $A3['y1'], '出荷判定');
        $tcpdf->Text($A3['x0']+24+24, $A3['y1'], '精度判定');
        $tcpdf->Text($A3['x0']+24+48, $A3['y1'], '外観判定');

        foreach ($failureTypes as $i => $f) {
            $tcpdf->Text($A3['x0']+100+($i*$fd), $A3['y1'], $f['name']);
        }

        foreach ($parts_obj as $n => $part_obj) {
            $tcpdf->StartTransform();
            $tcpdf->Rotate(90, $A3['x0']+24+$n*4-1, $A3['y1']+9);
            $tcpdf->Text($A3['x0']+24+$n*4-3, $A3['y1']+9, $part_obj->short_name);
            $tcpdf->StopTransform();
        }

        if ($this->triple) {
            $tcpdf->Line($A3['x0'], 100, $A3['xmax'] - $A3['x0'], 100, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 180, $A3['xmax'] - $A3['x0'], 180, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 260, $A3['xmax'] - $A3['x0'], 260, array('dash' => '3,1'));
        }

        // Render table body
        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);

            if (count($sum) != 0) {
                $ip = 0;

                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th-1.5, '×');
                $tcpdf->Text($A3['x0']+20-0.4, $A3['y2']+$n*$th+1.5, '○');

                foreach ($sum as $part_sum) {
                    $tcpdf->Text($A3['x0']+24+4*$ip, $A3['y2']+$n*$th-1.5, $part_sum['status'][0]);
                    $tcpdf->Text($A3['x0']+24+4*$ip, $A3['y2']+$n*$th+1.5, $part_sum['status'][1]);

                    $tcpdf->Text($A3['x0']+24+4*$ip+24, $A3['y2']+$n*$th-1.5, $part_sum['iStatus'][0]);
                    $tcpdf->Text($A3['x0']+24+4*$ip+24, $A3['y2']+$n*$th+1.5, $part_sum['iStatus'][1]);

                    $tcpdf->Text($A3['x0']+24+4*$ip+48, $A3['y2']+$n*$th-1.5, $part_sum['gStatus'][0]);
                    $tcpdf->Text($A3['x0']+24+4*$ip+48, $A3['y2']+$n*$th+1.5, $part_sum['gStatus'][1]);

                    foreach ($failureTypes as $i => $f) {
                        if (!array_key_exists($f['id'], $part_sum['f'])) {
                            $f_sum = 0;
                        }
                        else {
                            $f_sum = $part_sum['f'][$f['id']];
                        }

                        $tcpdf->Text($A3['x0']+100+($i*$fd)+$fdj*$ip, $A3['y2']+$n*$th, $f_sum);
                    }
                    $ip += 1;
                }
            }

            $n = $n+1;
        }

        return $tcpdf;
    }

    public function forAnaGaikan($parts)
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
                    $exu = explode(',', $part->updated_by);
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

                if ($part->comment) {
                    $comment = mb_substr($part->comment, 0, 4, 'UTF-8').'..';
                }
                else {
                    $comment = '';
                }

                return [
                    'panelId' => $part->panel_id,
                    'iStatus' => $iStatus,
                    'gStatus' => $gStatus,
                    'status' => $status,
                    'inspectedBy' => $inspectedBy,
                    'time' => $part->created_at,
                    'comment' => $comment,
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
                        return $p->status == 1;
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
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '登録時間');

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
        $d = [6, 18, 20, 26];
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
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1'], '外観出荷判定');

            $parts_obj_except_u = $parts_obj->filter(function($p) {return $p->id != 2;})->values();
            $dj_gaikan = 26*0.85/$parts_obj->count();

            foreach ($parts_obj as $n => $part_obj) {
                $tcpdf->StartTransform();
                $tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_gaikan)-1, $A3['y1']+10);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_gaikan)-3, $A3['y1']+10, $part_obj->short_name);
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

                foreach ($parts_obj as $n => $part_obj) {
                    $part = $parts[$part_obj->id];
                    $status = $part['gStatus'] == 1 ? '○' : '×';

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3))+($n*$dj_gaikan), $A3['y2']+($row)*$A3['th'], $status);
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

                $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y2']+($row)*$A3['th'], $parts->first()['comment']);
                $tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $th = 10;

        if ($this->triple) {
            $timeChunks = config('report.timeChunks2');
        } else {
            $timeChunks = config('report.timeChunks');
        }

        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedFamilies = [];
        foreach ($formated_families as $formated_family) {
            $time = $formated_family->first()['time']
                ->subHours($base['H'])
                ->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

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

        if ($this->triple) {
            $tcpdf->Line($A3['x0'], 100, $A3['xmax'] - $A3['x0'], 100, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 180, $A3['xmax'] - $A3['x0'], 180, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 260, $A3['xmax'] - $A3['x0'], 260, array('dash' => '3,1'));
        }

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
                    }
                    $ip += 1;
                }
            }

            $n = $n+1;
        }

        return $tcpdf;
    }

    public function forAnaSingle($parts)
    {
        $tcpdf = $this->createTCPDF();

        $failureTypes = $this->failureTypes;
        $part_obj = PartType::find($parts->first()->part_type_id);
        $parts = $parts->groupBy('panel_id')->values();

        $formated_parts = $parts->map(function($part) {
            $exc = explode(',', $part->first()->created_by);
            $inspectedBy = count($exc) > 1 ? $exc[1] : $exc[0];

            if ($part->first()->updated_by) {
                $exu = explode(',', $part->first()->updated_by);
                $inspectedBy = count($exu) > 1 ? $exu[1] : $exu[0];
            }

            return [
                'panelId' => $part->first()->panel_id,
                'status' => $part->first()->status,
                'inspectedBy' => $inspectedBy,
                // 'time' => $part->first()->updated_at,
                'time' => $part->first()->created_at,
                'comment' => $part->first()->comment,
                'failures' => $part->first()->failurePositions->map(function($fp) {
                    return $fp->failure_id;
                }),
                'holes' => $part->first()->pages
                    ->reduce(function($carry, $p) {
                        return $carry->merge($p->holePages);
                    }, collect([]))
                    ->keyBy('hole_id')
                    ->map(function($h) {
                        return collect([
                            'id' => $h->hole_id,
                            'status' => $h->status,
                            'holeModification' => $h->holeModification
                        ]);
                    })
            ];
        });

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

        foreach ($formated_parts->chunk(100) as $p => $parts100) {
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
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '登録時間');

                foreach ($parts50->values() as $row => $part) {
                    $status = $part['status'] == 1 ? '○' : '×';

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+$row*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+$row*$th, $part['panelId']);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+$row*$th, $part['inspectedBy']);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y3']+$row*$th, $status);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+$row*$th, $part['time']->format('H:i'));
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

        foreach ($formated_parts->chunk(40) as $p => $parts40) {
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
                $status = $part['status'] == 1 ? '○' : '×';

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $p*40+$row+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $part['panelId']);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $part['inspectedBy']);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                foreach ($all_holes as $col => $hole) {
                    switch ($part['holes'][$hole->id]['status']) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                        case 2: $status = '△'; break;
                    }

                    if ($part['holes'][$hole->id]['holeModification']->count() != 0) {
                        $tcpdf->SetFont('kozgopromedium', '', 4);
                        $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole)+2, $A3['y2']+($row)*$A3['th']-1.5, '※');
                        $tcpdf->SetFont('kozgopromedium', '', 6);
                    }
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y2']+($row)*$A3['th'], $status);
                }

                // Render failures
                foreach ($failureTypes as $i => $ft) {
                    $sum = $part['failures']->filter(function($f) use ($ft) {
                        return $f == $ft['id'];
                    })->count();

                    $tcpdf->Text($A3['x0']+array_sum($d)+($d_hole*$hn)+$margin+($fd*$i), $A3['y2']+$row*$A3['th'], $sum);
                }

                $comment = '';
                if ($part['comment']) {
                    $comment = mb_substr($part['comment'], 0, 4, 'UTF-8').'..';
                }

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y2']+($row)*$A3['th'], $comment);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y2']+($row)*$A3['th'], $part['time']->format('H:i'));
            }

            $tcpdf->Text(210, 286, 'page '.($p+1));
        }

        /*
         * Render A3 Aggregation
         */
        if ($this->triple) {
            $timeChunks = config('report.timeChunks2');
        } else {
            $timeChunks = config('report.timeChunks');
        }

        $base = $timeChunks[0]['start'];

        $timeChunkedParts = [];
        foreach ($formated_parts as $formated_part) {
            $time = $formated_part['time']
                ->subHours($base['H'])
                ->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedParts)) {
                    $timeChunkedParts[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedParts[$tc['label']][] = collect([
                        'failures' => $formated_part['failures'],
                        'holes' => $formated_part['holes']
                    ]);
                }
            }
        }

        $timeChunksSum = collect($timeChunkedParts)->map(function($chunk) {
            $collected = collect($chunk);

            $result['f'] = array_count_values($collected->pluck('failures')->flatten()->toArray());
            $result['h'] = $collected->pluck('holes')
                ->flatten(1)
                ->groupBy('id')
                ->map(function($h) {
                    return $h->groupBy('status')->map(function($s) {
                        return $s->count();
                    });
                });
            return collect($result);
        });

        $th = 10;
        $fd = 4;
        $d_hole_max = 16;
        $d_hole = ($A3['xmax'] - $A3['x0']*2 - 24 - $fd*$fn - $margin)/$hn;

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        $tcpdf->Line($A3['x0'], 102.3, $A3['xmax'] - $A3['x0'], 102.3, array('dash' => '2'));
        $tcpdf->Line($A3['x0'], 182.3, $A3['xmax'] - $A3['x0'], 182.3, array('dash' => '2'));
        $tcpdf->Line($A3['x0'], 262.3, $A3['xmax'] - $A3['x0'], 262.3, array('dash' => '2'));

        // Render table header
        $tcpdf->SetFont('kozgopromedium', '', 6);
        foreach ($all_holes as $col => $hole) {
            $tcpdf->StartTransform();
            $tcpdf->Rotate(90, $A3['x0']+24+($col*$d_hole)+1, $A3['y1_ana']+3+1);
            $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y1_ana']+3, $hole->label);
            $tcpdf->StopTransform();
        }
        foreach ($failureTypes as $i => $f) {
            $tcpdf->StartTransform();
            $tcpdf->Rotate(90, $A3['x0']+24+($d_hole*$hn)+$margin+($fd*$i)+1, $A3['y1_ana']+3+1);
            $tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($fd*$i), $A3['y1_ana']+3, $f['name']);
            $tcpdf->StopTransform();
        }

        if ($this->triple) {
            $tcpdf->Line($A3['x0'], 102.3, $A3['xmax'] - $A3['x0'], 102.3, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 182.3, $A3['xmax'] - $A3['x0'], 182.3, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 262.3, $A3['xmax'] - $A3['x0'], 262.3, array('dash' => '3,1'));
        }

        // Render table body
        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th, '×');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+3, '△');
            $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+6, '○');


            if (count($sum['h']) != 0) {
                foreach ($all_holes as $col => $hole) {
                    $sum0 = $sum['h'][$hole->id]->has(0) ? $sum['h'][$hole->id][0] : 0;
                    $sum1 = $sum['h'][$hole->id]->has(1) ? $sum['h'][$hole->id][1] : 0;
                    $sum2 = $sum['h'][$hole->id]->has(2) ? $sum['h'][$hole->id][2] : 0;

                    $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th, $sum0);
                    $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+3, $sum2);
                    $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+6, $sum1);
                }

                foreach ($failureTypes as $i => $f) {
                    if (!array_key_exists($f['id'], $sum['f'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['f'][$f['id']];
                    }

                    $tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($i*$fd), $A3['y2']+$n*$th, $f_sum);
                }
            }
            else {
                foreach ($all_holes as $col => $hole) {
                    $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th, 0);
                    $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+3, 0);
                    $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+6, 0);
                }

                foreach ($failureTypes as $i => $f) {
                    $tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($i*$fd), $A3['y2']+$n*$th, 0);
                }
            }

            $n = $n+1;
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
            ->orderBy('sort')
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
                // foreach ($part_types as $id => $part_type) {
                foreach ($parts_obj as $id => $part_obj) {
                    if ($part_types[$part_obj->id]) {
                        $sum1 = $part_types[$part_obj->id]->filter(function($p) {
                            return $p->status == 1;
                        })->count();
                        $sum0 = $part_types[$part_obj->id]->count() - $sum1;

                        $tcpdf->MultiCell($dL, $hhj, $part_obj->short_name.'：'.$part_obj->name, 1, 'C', 0, 1, $A4['x0']+($n*$dL), $A4['y1'], true, 0, false, true, 0, 'M');
                        $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0']+($n*$dL), $A4['y1']+$hhj);
                        $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1, $A4['y1']+$hhj);
                        $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1+$dhj2, $A4['y1']+$hhj);
                        $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+($n*$dL)+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
                        $n +=1;
                    }
                }
            }

            $col = 0;
            // foreach ($chunked_part_types as $id => $chunked_part_type) {
            foreach ($parts_obj as $id => $part_obj) {
    
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$dL, $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$dL, $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$dL, $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$dL, $A4['y2'], '判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$dL, $A4['y2'], '登録');

                // foreach ($chunked_part_type[$p]->values() as $row => $part) {
                foreach ($chunked_part_types[$part_obj->id][$p]->values() as $row => $part) {
                    $createdBy = explode(',', $part->created_by);

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$dL, $A4['y3']+($row)*$A4['th'], $p*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$dL, $A4['y3']+($row)*$A4['th'], $part->panel_id);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$dL, $A4['y3']+($row)*$A4['th'], array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$dL, $A4['y3']+($row)*$A4['th'], $part->status == 1 ? '○' : '×');
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$dL, $A4['y3']+($row)*$A4['th'], $part->created_at->format('H:i'));
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
        $d = [6, 18, 20, 26];
        $margin = 4;
        $d_comment = 14;
        $d_date = 10;
        $fd = 12;

        foreach ($parts_obj as $id => $part_obj) {
            $formated_part_type = $part_types[$part_obj->id]->map(function($part) {
                $exc = explode(',', $part->created_by);
                $inspectedBy = count($exc) > 1 ? $exc[1] : $exc[0];

                if ($part->updated_by) {
                    $exu = explode(',', $part->first()->updated_by);
                    $inspectedBy = count($exu) > 1 ? $exu[1] : $exu[0];
                }

                return [
                    'panelId' => $part->panel_id,
                    'status' => $part->status,
                    'inspectedBy' => $inspectedBy,
                    // 'time' => $part->updated_at,
                    'time' => $part->created_at,
                    'comment' => $part->comment,
                    'failures' => $part->failurePositions->map(function($fp) {
                        return $fp->failure_id;
                    }),
                    'holes' => $part->pages
                        ->reduce(function($carry, $p) {
                            return $carry->merge($p->holePages);
                        }, collect([]))
                        ->keyBy('hole_id')
                        ->map(function($h) {
                            return collect([
                                'id' => $h->hole_id,
                                'status' => $h->status,
                                'holeModification' => $h->holeModification
                            ]);
                        })
                ];
            });

            $all_holes = Hole::where('part_type_id', '=', $part_obj->id)
                ->whereNotIn('figure_id', [9])
                ->orderBy('label')
                ->get();

            $hn = $all_holes->count();
            $d_hole_max = 16;
            $d_hole = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_date)/$hn;

            if ($d_hole > $d_hole_max) {
                $d_hole = $d_hole_max;
            }

            foreach ($formated_part_type->chunk(40) as $p => $parts40) {
                $tcpdf->AddPage('L', 'A3');

                // Render page header
                $this->renderA3Header($tcpdf);
                $tcpdf->Text($A3['x0']+$A3['header']['part'], $A3['y0'], $part_obj->name);

                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1_ana'], 'No.');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1_ana'], 'パネルID');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1_ana'], '検査者');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1_ana'], '出荷判定');

                foreach ($all_holes as $col => $hole) {
                    $tcpdf->StartTransform();
                    $tcpdf->Rotate(0, $A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole)+1, $A3['y1_ana']+1);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y1_ana'], $hole->label);
                    $tcpdf->StopTransform();
                }

                foreach ($failureTypes as $i => $f) {
                    $tcpdf->StartTransform();
                    $tcpdf->Rotate(0, $A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$i)+1, $A3['y1_ana']+1);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$i), $A3['y1_ana'], $f['name']);
                    $tcpdf->StopTransform();
                }

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y1_ana'], 'コメント');
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y1_ana'], '登録時間');

                if ($p == 0) {
                    $tcpdf->Text($A3['x0'], $A3['y1'], '○ : 公差内　× : 穴径大　△ : 穴径小　* : 手直し');
                }

                foreach ($parts40->values() as $row => $part) {
                    $status = $part['status'] == 1 ? '○' : '×';

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $p*40+$row+1);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $part['panelId']);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $part['inspectedBy']);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                    foreach ($all_holes as $col => $hole) {
                        switch ($part['holes'][$hole->id]['status']) {
                            case 0: $status = '×'; break;
                            case 1: $status = '○'; break;
                            case 2: $status = '△'; break;
                        }

                        if ($part['holes'][$hole->id]['holeModification']->count() != 0) {
                            $tcpdf->SetFont('kozgopromedium', '', 4);
                            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole)+2, $A3['y2']+($row)*$A3['th']-1.5, '※');
                            $tcpdf->SetFont('kozgopromedium', '', 6);
                        }
                        $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($col*$d_hole), $A3['y2']+($row)*$A3['th'], $status);
                    }

                    // Render failures
                    foreach ($failureTypes as $i => $ft) {
                        $sum = $part['failures']->filter(function($f) use ($ft) {
                            return $f == $ft['id'];
                        })->count();

                        $tcpdf->Text($A3['x0']+array_sum($d)+($d_hole*$hn)+$margin+($fd*$i), $A3['y2']+$row*$A3['th'], $sum);
                    }

                    $comment = '';
                    if ($part['comment']) {
                        $comment = mb_substr($part['comment'], 0, 4, 'UTF-8').'..';
                    }

                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y2']+($row)*$A3['th'], $comment);
                    $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y2']+($row)*$A3['th'], $part['time']->format('H:i'));
                }

                $tcpdf->Text(210, 286, 'page '.($p+1));
            }
        }

        foreach ($parts_obj as $id => $part_obj) {
            $formated_part_type = $part_types[$part_obj->id]->map(function($part) {
                $exc = explode(',', $part->created_by);
                $inspectedBy = count($exc) > 1 ? $exc[1] : $exc[0];

                if ($part->updated_by) {
                    $exu = explode(',', $part->first()->updated_by);
                    $inspectedBy = count($exu) > 1 ? $exu[1] : $exu[0];
                }

                return [
                    'panelId' => $part->panel_id,
                    'status' => $part->status,
                    'inspectedBy' => $inspectedBy,
                    // 'time' => $part->updated_at,
                    'time' => $part->created_at,
                    'comment' => $part->comment,
                    'failures' => $part->failurePositions->map(function($fp) {
                        return $fp->failure_id;
                    }),
                    'holes' => $part->pages
                        ->reduce(function($carry, $p) {
                            return $carry->merge($p->holePages);
                        }, collect([]))
                        ->keyBy('hole_id')
                        ->map(function($h) {
                            return collect([
                                'id' => $h->hole_id,
                                'status' => $h->status,
                                'holeModification' => $h->holeModification
                            ]);
                        })
                ];
            });

            $all_holes = Hole::where('part_type_id', '=', $part_obj->id)
                ->whereNotIn('figure_id', [9])
                ->orderBy('label')
                ->get();
            $hn = $all_holes->count();
            $th = 10;
            $fd = 18;
            $d_hole_max = 16;
            $d_hole = ($A3['xmax'] - $A3['x0']*2 - 24 - $fd*$fn - $margin)/$hn;

            if ($d_hole > $d_hole_max) {
                $d_hole = $d_hole_max;
            }

            /*
             * Render A3 Aggregation
             */
            if ($this->triple) {
                $timeChunks = config('report.timeChunks2');
            } else {
                $timeChunks = config('report.timeChunks');
            }

            $base = $timeChunks[0]['start'];

            $timeChunkedParts = [];
            foreach ($formated_part_type as $formated_part) {
                $time = $formated_part['time']
                    ->subHours($base['H'])
                    ->subMinutes($base['i']);

                $minutes = $time->hour*60 + $time->minute;
                if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                    $minutes = $minutes + 60*24;
                }

                foreach ($timeChunks as $tc) {
                    $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                    $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                    if (!array_key_exists($tc['label'], $timeChunkedParts)) {
                        $timeChunkedParts[$tc['label']] = [];
                    }

                    if ($minutes >= $min && $minutes < $max) {
                        $timeChunkedParts[$tc['label']][] = collect([
                            'failures' => $formated_part['failures'],
                            'holes' => $formated_part['holes']
                        ]);
                    }
                }
            }

            $timeChunksSum = collect($timeChunkedParts)->map(function($chunk) {
                $collected = collect($chunk);

                $result['f'] = array_count_values($collected->pluck('failures')->flatten()->toArray());
                $result['h'] = $collected->pluck('holes')
                    ->flatten(1)
                    ->groupBy('id')
                    ->map(function($h) {
                        return $h->groupBy('status')->map(function($s) {
                            return $s->count();
                        });
                    });
                return collect($result);
            });

            $tcpdf->AddPage('L', 'A3');
            // Render page header
            $this->renderA3Header($tcpdf);
            $tcpdf->Text($A3['x0']+$A3['header']['part'], $A3['y0'], $part_obj->name);

            if ($this->triple) {
                $tcpdf->Line($A3['x0'], 102.3, $A3['xmax'] - $A3['x0'], 102.3, array('dash' => '3,1'));
                $tcpdf->Line($A3['x0'], 182.3, $A3['xmax'] - $A3['x0'], 182.3, array('dash' => '3,1'));
                $tcpdf->Line($A3['x0'], 262.3, $A3['xmax'] - $A3['x0'], 262.3, array('dash' => '3,1'));
            }

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 6);
            foreach ($all_holes as $col => $hole) {
                $tcpdf->StartTransform();
                $tcpdf->Rotate(0, $A3['x0']+24+($col*$d_hole)+1, $A3['y1_ana']+1);
                $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y1_ana'], $hole->label);
                $tcpdf->StopTransform();
            }
            foreach ($failureTypes as $i => $f) {
                $tcpdf->StartTransform();
                $tcpdf->Rotate(0, $A3['x0']+24+($d_hole*$hn)+$margin+($fd*$i)+1, $A3['y1_ana']+1);
                $tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($fd*$i), $A3['y1_ana'], $f['name']);
                $tcpdf->StopTransform();
            }

            // Render table body
            $n = 0;
            foreach ($timeChunksSum as $key => $sum) {
                $tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);
                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th, '×');
                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+3, '△');
                $tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+6, '○');

                if (count($sum['h']) != 0) {
                    foreach ($all_holes as $col => $hole) {
                        $sum0 = $sum['h'][$hole->id]->has(0) ? $sum['h'][$hole->id][0] : 0;
                        $sum2 = $sum['h'][$hole->id]->has(2) ? $sum['h'][$hole->id][2] : 0;
                        $sum1 = $sum['h'][$hole->id]->has(1) ? $sum['h'][$hole->id][1] : 0;

                        $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th, $sum0);
                        $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+3, $sum2);
                        $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+6, $sum1);
                    }

                    foreach ($failureTypes as $i => $f) {
                        if (!array_key_exists($f['id'], $sum['f'])) {
                            $f_sum = 0;
                        }
                        else {
                            $f_sum = $sum['f'][$f['id']];
                        }

                        $tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($i*$fd), $A3['y2']+$n*$th, $f_sum);
                    }
                }
                else {
                    foreach ($all_holes as $col => $hole) {
                        $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th, 0);
                        $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+3, 0);
                        $tcpdf->Text($A3['x0']+24+($col*$d_hole), $A3['y2']+$n*$th+6, 0);
                    }

                    foreach ($failureTypes as $i => $f) {
                        $tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($i*$fd), $A3['y2']+$n*$th, 0);
                    }
                }

                if (count($sum['h']) != 0) {

                }

                $n = $n+1;
            }
        }

        return $tcpdf;
    }

    public function forJointing($parts)
    {
        $tcpdf = $this->createTCPDF();

        $failureTypes = $this->failureTypes;
        $modificationTypes = $this->modificationTypes;

        $formated_parts = $parts->map(function($part) {
            $exc = explode(',', $part->created_by);
            $inspectedBy = count($exc) > 1 ? $exc[1] : $exc[0];

            if ($part->updated_by) {
                $exu = explode(',', $part->updated_by);
                $inspectedBy = count($exu) > 1 ? $exu[1] : $exu[0];
            }

            $comment = '';
            if ($part->comment) {
                $comment = mb_substr($part->comment, 0, 4, 'UTF-8').'..';
            }

            return [
                'name' => $part->partType->name,
                'pn' => $part->partType->name,
                'panelId' => $part->panel_id,
                'status' => $part->status,
                'inspectedBy' => $inspectedBy,
                // 'time' => $part->updated_at,
                'time' => $part->created_at,
                'comment' => $comment,
                'failures' => $part->failurePositions->map(function($fp) {
                    return $fp->failure_id;
                }),
                'modifications' => $part->pages->first()->comments->map(function($m) {
                    return $m->m_id;
                })
            ];
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

        foreach ($formated_parts->chunk(100) as $p => $parts100) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);

                $partName = $formated_parts->first()['name'];
                $partPn = $formated_parts->first()['pn'];

                $sum1 = $formated_parts->filter(function($p) {
                    return $p['status'] == 1;
                })->count();
                $sum0 = $formated_parts->count() - $sum1;

                $tcpdf->MultiCell($dhj, $hhj, $partName, 1, 'C', 0, 1, $A4['x0'], $A4['y1']);
                $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0'], $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+$dhj1, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
            }

            foreach ($parts100->values()->chunk(50) as $col => $parts50) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y2'], '検査者');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '出荷判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '登録時間');

                foreach ($parts50->values() as $row => $part) {
                    $panelId = $part['panelId'];
                    $inspectedBy = $part['inspectedBy'];
                    $status = $part['status'] == 1 ? '○' : '×';
                    $time = $part['time']->format('H:i');

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+$row*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+$row*$th, $panelId);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+$row*$th, $inspectedBy);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y3']+$row*$th, $status);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+$row*$th, $time);
                }
            }

            $tcpdf->Line(106, 28, 106, 286);
            $tcpdf->Text(103, 287, 'page '.($p+1));
        }

        /*
         * Render A3
         */
        $A3 = config('report.A3');
        $d = [6, 18, 20, 16];
        $d_comment = 20;
        $d_date = 8;
        $margin = 10;

        $fn = count($failureTypes);
        $mn = count($modificationTypes);
        $fmd = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_comment - $d_date - $margin)/($fn+$mn);

        foreach ($formated_parts->chunk(40) as $page => $parts40) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $this->renderA3Header($tcpdf);

            $tcpdf->SetFont('kozgopromedium', '', 7);
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1_ana'], 'No.');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1_ana'], 'パネルID');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1_ana'], '検査者');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1_ana'], '出荷判定');

            foreach ($failureTypes as $fi => $ft) {
                $f_name = mb_substr($ft['name'], 0, 4, 'UTF-8');
                $tcpdf->StartTransform();
                $tcpdf->Rotate(0, $A3['x0']+array_sum($d)+($fi*$fmd)+1, $A3['y1_ana']+1);
                $tcpdf->Text($A3['x0']+array_sum($d)+($fi*$fmd), $A3['y1_ana'], $f_name);
                $tcpdf->StopTransform();
            }

            foreach ($modificationTypes as $mi => $mt) {
                $m_name = mb_substr($mt['name'], 0, 4, 'UTF-8');
                $tcpdf->StartTransform();
                $tcpdf->Rotate(0, $A3['x0']+array_sum($d)+$margin+$fmd*($fn+$mi)+1, $A3['y1_ana']+1);
                $tcpdf->Text($A3['x0']+array_sum($d)+$margin+$fmd*($fn+$mi), $A3['y1_ana'], $m_name);
                $tcpdf->StopTransform();
            }

            $tcpdf->Text($A3['x0']+array_sum($d)+$fmd*($fn+$mn)+$margin, $A3['y1_ana'], 'コメント');
            $tcpdf->Text($A3['x0']+array_sum($d)+$fmd*($fn+$mn)+$margin+$d_comment, $A3['y1_ana'], '登録時間');

            foreach ($parts40->values() as $row => $part) {
                $panelId = $part['panelId'];
                $inspectedBy = $part['inspectedBy'];
                $status = $part['status'] == 1 ? '○' : '×';
                $time = $part['time']->format('H:i');

                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $row+($page*40)+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panelId);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $inspectedBy);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                foreach ($failureTypes as $fi => $ft) {
                    $sum = $part['failures']->filter(function($f) use ($ft) {
                        return $f == $ft['id'];
                    })->count();

                    $tcpdf->Text($A3['x0']+array_sum($d)+$fmd*$fi, $A3['y2']+($row)*$A3['th'], $sum);
                }

                foreach ($modificationTypes as $mi => $mt) {
                    $sum = $part['modifications']->filter(function($m) use ($mt) {
                        return $m == $mt['id'];
                    })->count();

                    $tcpdf->Text($A3['x0']+array_sum($d)+$margin+$fmd*($fn+$mi), $A3['y2']+($row)*$A3['th'], $sum);
                }

                $tcpdf->Text($A3['x0']+array_sum($d)+$margin+$fmd*($fn+$mi)+$margin, $A3['y2']+($row)*$A3['th'], $part['comment']);
                $tcpdf->Text($A3['x0']+array_sum($d)+$margin+$fmd*($fn+$mi)+$margin+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $tcpdf->Line($A3['x0']+array_sum($d)+$fmd*$fn+$margin/2, 26, $A3['x0']+array_sum($d)+$fmd*$fn+$margin/2, 286, array('dash' => '2'));
            $tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        if ($this->triple) {
            $timeChunks = config('report.timeChunks2');
        } else {
            $timeChunks = config('report.timeChunks');
        }

        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedParts = [];
        foreach ($formated_parts as $formated_part) {
            $time = $formated_part['time']
                ->subHours($base['H'])
                ->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedParts)) {
                    $timeChunkedParts[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedParts[$tc['label']][] = collect([
                        'f' => $formated_part['failures'],
                        'm' => $formated_part['modifications']
                    ]);
                }
            }
        }

        $timeChunksSum = collect($timeChunkedParts)->map(function($chunk) {
            $collected = collect($chunk);

            $result = [];
            if (collect($chunk)->count() > 0) {
                $result['f'] = array_count_values($collected->pluck('f')->flatten()->toArray());
                $result['m'] = array_count_values($collected->pluck('m')->flatten()->toArray());
            }

            return collect($result);
        });

        $th = 10;
        $fmd = ($A3['xmax'] - $A3['x0']*2  - 24 - $margin)/($fn+$mn);

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        // Render table header
        $tcpdf->SetFont('kozgopromedium', '', 7);

        foreach ($failureTypes as $fi => $ft) {
            $tcpdf->StartTransform();
            $tcpdf->Rotate(0, $A3['x0']+24+($fmd*$fi)+1, $A3['y1_ana']+1);
            $tcpdf->Text($A3['x0']+24+($fmd*$fi), $A3['y1_ana'], $ft['name']);
            $tcpdf->StopTransform();
        }

        foreach ($modificationTypes as $mi => $mt) {
            $tcpdf->StartTransform();
            $tcpdf->Rotate(0, $A3['x0']+24+$margin+$fmd*($fn+$mi)+1, $A3['y1_ana']+1);
            $tcpdf->Text($A3['x0']+24+$margin+$fmd*($fn+$mi), $A3['y1_ana'], $mt['name']);
            $tcpdf->StopTransform();
        }

        $tcpdf->Line($A3['x0']+24+$fmd*$fn+$margin/2, 26, $A3['x0']+24+$fmd*$fn+$margin/2, 286);

        if ($this->triple) {
            $tcpdf->Line($A3['x0'], 100, $A3['xmax'] - $A3['x0'], 100, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 180, $A3['xmax'] - $A3['x0'], 180, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 260, $A3['xmax'] - $A3['x0'], 260, array('dash' => '3,1'));
        }

        // Render table body
        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);

            if ($sum->has('f')) {
                foreach ($failureTypes as $fi => $ft) {
                    if (!array_key_exists($ft['id'], $sum['f'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['f'][$ft['id']];
                    }
                    $tcpdf->Text($A3['x0']+24+($fi*$fmd), $A3['y2']+$n*$th, $f_sum);
                }
            }
            else {
                foreach ($failureTypes as $fi => $ft) {
                    $tcpdf->Text($A3['x0']+24+($fi*$fmd), $A3['y2']+$n*$th, 0);
                }
            }

            if ($sum->has('f')) {
                foreach ($modificationTypes as $mi => $mt) {
                    if (!array_key_exists($mt['id'], $sum['m'])) {
                        $m_sum = 0;
                    }
                    else {
                        $m_sum = $sum['m'][$mt['id']];
                    }

                    $tcpdf->Text($A3['x0']+24+$margin+$fmd*($fn+$mi), $A3['y2']+$n*$th, $m_sum);
                }
            }
            else {
                foreach ($modificationTypes as $mi => $mt) {
                    $tcpdf->Text($A3['x0']+24+$margin+$fmd*($fn+$mi), $A3['y2']+$n*$th, 0);
                }
            }

            $n = $n+1;
        }

        return $tcpdf;
    }

    public function forInline($parts)
    {
        $tcpdf = $this->createTCPDF();
        $part_type = $parts->groupBy('part_type_id')->keys()->first();
        $inlines = Inline::where('part_type_id', '=', $part_type)->get();

        $formated_parts = $parts->map(function($part) {
            return collect([
                'panelId' => $part->panel_id,
                'status' => $part->status,
                'time' => Carbon::createFromFormat('Y-m-d H:i:s', $part->inspected_at),
                'inlines' => $part->inlines->groupBy('id')->map(function($inline) {
                    $i = $inline->first();
                    return collect([
                        'id' => $i->id,
                        'sort' => $i->sort,
                        'min' => $i->min_tolerance,
                        'max' => $i->max_tolerance,
                        'status' => $i->pivot->status
                    ]);
                })->sortBy('sort')->values()
            ]);
        });

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

        foreach ($formated_parts->chunk(100) as $p => $parts100) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $sum1 = $parts->filter(function($p) {
                    return $p->status == 1;
                })->count();
                $sum0 = $parts->count() - $sum1;

                $tcpdf->MultiCell($dhj, $hhj, 'バックドアインナーASSY', 1, 'C', 0, 1, $A4['x0'], $A4['y1']);
                $tcpdf->MultiCell($dhj1, $hhj, 'OK', 1, 'C', 0, 1, $A4['x0'], $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+$dhj1, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj1, $hhj, 'NG', 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
            }

            foreach ($parts100->values()->chunk(50) as $col => $parts50) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '出荷判定');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '登録時間');

                foreach ($parts50->values() as $row => $part) {
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $part['panelId']);

                    $status = $part['status'] == 1 ? 'OK' : 'NG';
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y3']+($row)*$th, $status);

                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, $part['time']->format('H:i'));
                }
            }

            $tcpdf->Line(106, 28, 106, 286);
            $tcpdf->Text(103, 287, 'page '.($p+1));
        }

        /*
         * Render A3
         */
        $A3 = config('report.A3');
        $d = [6, 18, 20];
        $d_comment = 20;
        $d_date = 14;
        $inlines = $formated_parts->first()['inlines'];
        $in = $inlines->count();
        $di = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_date)/$in;

        foreach ($formated_parts->chunk(40) as $page => $parts40) {
            $tcpdf->AddPage('L', 'A3');

            // Render page header
            $this->renderA3Header($tcpdf);  

            // Render table header
            $tcpdf->SetFont('kozgopromedium', '', 8);
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1'], 'No.');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1'], 'パネルID');
            $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1'], '精度判定');

            foreach ($inlines as $i => $inline) {
                $tcpdf->Text($A3['x0']+array_sum($d)+$i*$di, $A3['y1'], 'P-'.$inline['sort']);

                $tolerance = number_format($inline['min'],2).'〜'.number_format($inline['max'],2);
                $tcpdf->SetFont('kozgopromedium', '', 6);
                $tcpdf->Text($A3['x0']+array_sum($d)+$i*$di, $A3['y1']+5, $tolerance);
                $tcpdf->SetFont('kozgopromedium', '', 8);
            }

            $tcpdf->Text($A3['x0']+array_sum($d)+$in*$di, $A3['y1'], '登録時間');

            // Render table body
            foreach ($parts40->values() as $row => $part) {
                $status = $part['status'] == 1 ? 'OK' : 'NG';
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+$th*$row, $page*40+$row+1);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+$th*$row, $part['panelId']);
                $tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+$th*$row, $status);

                foreach ($part['inlines'] as $col => $inline) {
                    $judge = '';
                    if ($inline['status'] > $inline['max'] || $inline['status'] < $inline['min']) {
                        $judge = '×';
                    }
                    $tcpdf->Text($A3['x0']+array_sum($d)+$col*$di, $A3['y2']+$th*$row, $inline['status']);
                    $tcpdf->Text($A3['x0']+array_sum($d)+$col*$di+9, $A3['y2']+$th*$row, $judge);
                }

                $tcpdf->Text($A3['x0']+array_sum($d)+$in*$di, $A3['y2']+$th*$row, $part['time']->format('H:i'));
            }

            $tcpdf->Text(210, 280, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        if ($this->triple) {
            $timeChunks = config('report.timeChunks2');
        } else {
            $timeChunks = config('report.timeChunks');
        }

        $base = $timeChunks[0]['start'];

        $th = 10;
        $di = ($A3['xmax'] - ($A3['x0']+$A3['x1']))/$in;

        // Divide families to time Chunk
        $timeChunkedParts = [];
        foreach ($formated_parts as $part) {
            $time = $part['time']->subHours($base['H'])->subMinutes($base['i']);
            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedParts)) {
                    $timeChunkedParts[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedParts[$tc['label']][] = $part['inlines'];
                }
            }
        }

        $timeChunksSum = collect($timeChunkedParts)->map(function($chunk) {
            $result = [];
            foreach ($chunk as $part) {
                foreach ($part as $inline) {
                    $result[$inline['id']][] = $inline;
                }
            }

            return collect($result)->map(function($i) {
                $sum0 = collect($i)->filter(function($i) {
                    return ($i['max'] < $i['status'] || $i['min'] > $i['status']);
                })->count();
                $sum1 = collect($i)->count() - $sum0;

                return [
                    'sum0' => $sum0,
                    'sum1' => $sum1
                ];
            });
        });

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        // Render table header
        $tcpdf->SetFont('kozgopromedium', '', 8);
        foreach ($inlines as $col => $i) {
            $tcpdf->Text($A3['x1']+($di*$col), $A3['y1'], 'P-'.$i['sort']);
        }

        foreach ($inlines as $i => $inline) {
            $tcpdf->Text($A3['x1']+$i*$di, $A3['y1'], 'P-'.$inline['sort']);
        }

        if ($this->triple) {
            $tcpdf->Line($A3['x0'], 102.3, $A3['xmax'] - $A3['x0'], 102.3, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 182.3, $A3['xmax'] - $A3['x0'], 182.3, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 262.3, $A3['xmax'] - $A3['x0'], 262.3, array('dash' => '3,1'));
        }

        // Render table body
        $row = 0;
        foreach ($timeChunksSum as $key => $chunk) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$th*$row, $key);
            $tcpdf->Text($A3['x1']-6, $A3['y2']+$th*$row, '×');
            $tcpdf->Text($A3['x1']-6, $A3['y2']+$th*$row+($th/2), '○');

            if (count($chunk) != 0) {
                $col = 0;
                foreach ($chunk as $inlin) {
                    $tcpdf->Text($A3['x1']+$di*$col, $A3['y2']+$th*$row, $inlin['sum0']);
                    $tcpdf->Text($A3['x1']+$di*$col, $A3['y2']+$th*$row+($th/2), $inlin['sum1']);
                    $col += 1;
                }
            }

            $row += 1;
        }

        return $tcpdf;
    }

    public function forThrough($parts)
    {
        $tcpdf = $this->createTCPDF();

        /*
         * Format for A4
         */
        $formated_parts = $parts->groupBy('panel_id')->map(function($p) {
            return $p->keyBy('inspection_group_id');
        })->map(function($p) {
            return collect([
                'panelId' => $p->first()['panel_id'],
                'P'=> $p,
                's16' => $p->has(16) ? $p[16]['status'] : null,
                's9'  => $p->has(9)  ? $p[9]['status']  : null,
                's10' => $p->has(10) ? $p[10]['status'] : null,
                's11' => $p->has(11) ? $p[11]['status'] : null,
                's12' => $p->has(12) ? $p[12]['status'] : null,
                's14' => $p->has(14) ? $p[14]['status'] : null
            ]);
        });

        /*
         * Render A4
         */
        $A4 = config('report.A4');
        $d = [8, 20, 10, 10, 10, 10, 10];
        $th = 5;
        $dj = 7;
        $dhj = 40;
        $dhj1 = 7;
        $dhj2 = $dhj/2 - $dhj1;
        $hhj = 4;

        foreach ($formated_parts->chunk(100) as $p => $parts100) {
            $tcpdf->AddPage('P', 'A4');

            // Render page header
            $this->renderA4Header($tcpdf);

            if ($p == 0) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $sum1 = $formated_parts->filter(function($p) {
                    return $p['s14'] == 1 || ($p['s14'] == null && $p['s12'] == 1);
                })->count();
                $sum0 = $formated_parts->count() - $sum1;

                $tcpdf->MultiCell($dhj, $hhj, 'バックドアインナーASSY', 1, 'C', 0, 1, $A4['x0'], $A4['y1']);
                $tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0'], $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum1, 1, 'C', 0, 1, $A4['x0']+$dhj1, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2, $A4['y1']+$hhj);
                $tcpdf->MultiCell($dhj2, $hhj, $sum0, 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
            }

            foreach ($parts100->values()->chunk(50) as $col => $parts50) {
                $tcpdf->SetFont('kozgopromedium', '', 8);
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y2'], 'No.');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y2'], 'パネルID');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y2'], '精度');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y2'], '簡易CF');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y2'], '止水');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,5))+$col*$A4['x1'], $A4['y2'], '仕上');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,6))+$col*$A4['x1'], $A4['y2'], '検査');
                $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,7))+$col*$A4['x1'], $A4['y2'], '手直');

                foreach ($parts50->values() as $row => $part) {
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['x1'], $A4['y3']+($row)*$th, $p*100+$col*50+$row+1);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['x1'], $A4['y3']+($row)*$th, $part['panelId']);
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['x1'], $A4['y3']+($row)*$th, is_null($part['s16']) ? '' : ($part['s16'] === 1 ? '○' : '×'));
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['x1'], $A4['y3']+($row)*$th, is_null($part['s9'])  ? '' : ($part['s9']  === 1 ? '○' : '×'));
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['x1'], $A4['y3']+($row)*$th, is_null($part['s10']) ? '' : ($part['s10'] === 1 ? '○' : '×'));
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,5))+$col*$A4['x1'], $A4['y3']+($row)*$th, is_null($part['s11']) ? '' : ($part['s11'] === 1 ? '○' : '×'));
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,6))+$col*$A4['x1'], $A4['y3']+($row)*$th, is_null($part['s12']) ? '' : ($part['s12'] === 1 ? '○' : '×'));
                    $tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,7))+$col*$A4['x1'], $A4['y3']+($row)*$th, is_null($part['s14']) ? '' : ($part['s14'] === 1 ? '○' : '×'));
                }
            }

            $tcpdf->Line(106, 28, 106, 286);
            $tcpdf->Text(103, 287, 'page '.($p+1));
        }



        /*
         * Format for A3 Aggregation
         */
        $formated_parts = $parts->groupBy('panel_id')->map(function($p) {
            return $p->keyBy('inspection_group_id');
        })->map(function($p) {
            return collect([
                'P'=> $p,
                'panelId' => $p->first()['panel_id'],
                'time'=> $p->has(16) ? $p[16]['created_at'] : null,
                's16' => $p->has(16) ? $p[16]['status'] : null,
                's9'  => $p->has(9)  ? $p[9]['status']  : null,
                's10' => $p->has(10) ? $p[10]['status'] : null,
                's11' => $p->has(11) ? $p[11]['status'] : null,
                's12' => $p->has(12) ? $p[12]['status'] : null,
                's14' => $p->has(14) ? $p[14]['status'] : null
            ]);
        });


        /*
         * Render A3 Aggregation
         */
        $A3 = config('report.A3');
        $th = 10;
        // $di = ($A3['xmax'] - ($A3['x0']+$A3['x1']))/$in;

        if ($this->triple) {
            $timeChunks = config('report.timeChunks2');
        } else {
            $timeChunks = config('report.timeChunks');
        }

        $base = $timeChunks[0]['start'];


        // Divide parts to time Chunk
        $timeChunkedParts = [];
        foreach ($formated_parts as $part) {
            $time = $part['time']->subHours($base['H'])->subMinutes($base['i']);
            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->serch_date.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedParts)) {
                    $timeChunkedParts[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedParts[$tc['label']][] = $part;
                }
            }
        }

        $timeChunksSum = collect($timeChunkedParts)->map(function($chunk) {
            $result = [];
            foreach ($chunk as $part) {
                $result['s9'][] = $part['s9'];
                $result['s16'][] = $part['s16'];
                $result['s10'][] = $part['s10'];
                $result['s11'][] = $part['s11'];
                $result['s12'][] = $part['s12'];
                $result['s14'][] = $part['s14'];
            }

            return collect($result)->map(function($i) {
                $sum0 = collect($i)->filter(function($s) {
                    return $s == 0;
                })->count();
                $sum1 = collect($i)->count() - $sum0;

                return [
                    'sum0' => $sum0,
                    'sum1' => $sum1
                ];
            });
        });

// return $timeChunksSum;

        $tcpdf->AddPage('L', 'A3');
        // Render page header
        $this->renderA3Header($tcpdf);

        // Render table header
        $tcpdf->Text($A3['x1']+20, $A3['y1'], '精度');
        $tcpdf->Text($A3['x1']+40, $A3['y1'], '簡易CF');
        $tcpdf->Text($A3['x1']+60, $A3['y1'], '止水');
        $tcpdf->Text($A3['x1']+80, $A3['y1'], '仕上');
        $tcpdf->Text($A3['x1']+100, $A3['y1'], '検査');
        $tcpdf->Text($A3['x1']+120, $A3['y1'], '手直');

        if ($this->triple) {
            $tcpdf->Line($A3['x0'], 102.3, $A3['xmax'] - $A3['x0'], 102.3, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 182.3, $A3['xmax'] - $A3['x0'], 182.3, array('dash' => '3,1'));
            $tcpdf->Line($A3['x0'], 262.3, $A3['xmax'] - $A3['x0'], 262.3, array('dash' => '3,1'));
        }

        // Render table body
        $row = 0;
        foreach ($timeChunksSum as $key => $chunk) {
            $tcpdf->Text($A3['x0'], $A3['y2']+$th*$row, $key);
            $tcpdf->Text($A3['x0']+36, $A3['y2']+$th*$row, '×');
            $tcpdf->Text($A3['x0']+36-0.4, $A3['y2']+$th*$row+($th/2), '○');


            if (count($chunk) != 0) {
                $col = 0;

                $tcpdf->Text($A3['x1']+20, $A3['y2']+$th*$row, $chunk['s9']['sum0']);
                $tcpdf->Text($A3['x1']+20, $A3['y2']+$th*$row+($th/2), $chunk['s9']['sum1']);
                $tcpdf->Text($A3['x1']+40, $A3['y2']+$th*$row, $chunk['s16']['sum0']);
                $tcpdf->Text($A3['x1']+40, $A3['y2']+$th*$row+($th/2), $chunk['s16']['sum1']);
                $tcpdf->Text($A3['x1']+60, $A3['y2']+$th*$row, $chunk['s10']['sum0']);
                $tcpdf->Text($A3['x1']+60, $A3['y2']+$th*$row+($th/2), $chunk['s10']['sum1']);
                $tcpdf->Text($A3['x1']+80, $A3['y2']+$th*$row, $chunk['s11']['sum0']);
                $tcpdf->Text($A3['x1']+80, $A3['y2']+$th*$row+($th/2), $chunk['s11']['sum1']);
                $tcpdf->Text($A3['x1']+100, $A3['y2']+$th*$row, $chunk['s12']['sum0']);
                $tcpdf->Text($A3['x1']+100, $A3['y2']+$th*$row+($th/2), $chunk['s12']['sum1']);
                $tcpdf->Text($A3['x1']+120, $A3['y2']+$th*$row, $chunk['s14']['sum0']);
                $tcpdf->Text($A3['x1']+120, $A3['y2']+$th*$row+($th/2), $chunk['s14']['sum1']);
            }

            $row += 1;
        }



        return $tcpdf;

    }

}
