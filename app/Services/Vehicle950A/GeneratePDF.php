<?php

namespace App\Services\Vehicle950A;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use TCPDF;
// Models
use App\Models\Vehicle950A\Process;
use App\Models\Vehicle950A\Inspection;
use App\Models\Vehicle950A\PartType;

class GeneratePDF
{
    protected $tcpdf;
    protected $now;
    protected $reportDate;
    protected $process;
    protected $inspection;
    protected $partName;
    protected $line;
    protected $choku = '';
    protected $positions = [
        'A4' => [
            'xmax' => 210,
            'x0' => 8,
            'y0' => 8,
            'y1' => 16,
            'y2' => 28,
            'y3' => 36
        ],
        'A3' => [
            'xmax' => 420,
            'x0' => 8,
            'y0' => 8,
            'x1' => 36,
            'y1' => 20,
            'y1_ana' => 26,
            'y2' => 33,
            'th' => 6.2
        ]
    ];
    protected $failureTypes;
    protected $modificationTypes;
    protected $holeModificationTypes;
    protected $holeTypes;
    protected $inlineTypes;

    public function __construct($vehicle, $process, $inspection, $pn, $line, $reportDate, $choku) {
        $this->tcpdf = $this->createTCPDF();
        $this->now = Carbon::now();
        $this->reportDate = str_replace('-', '/', $reportDate);

        $this->vehicle = $vehicle;
        $this->process = Process::find($process)->name;
        $this->inspection = Inspection::find($inspection)->name;
        $this->partName = PartType::find($pn)->name;
        $this->line = $line;

        switch ($choku) {
            case 'W': $this->choku = '白直'; break;
            case 'Y': $this->choku = '黄直'; break;
            case 'B': $this->choku = '黒直'; break;
        }
    }

    public function setFailures($failureTypes, $modificationTypes, $holeModificationTypes, $holeTypes, $inlineTypes)
    {
        $this->failureTypes = $failureTypes;
        $this->modificationTypes = $modificationTypes;
        $this->holeModificationTypes = $holeModificationTypes;
        $this->holeTypes = $holeTypes;
        $this->inlineTypes = $inlineTypes;

    }

    protected function createTCPDF()
    {
        $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $tcpdf->SetMargins(0, 0, 0);
        $tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        $tcpdf->SetMargins(0, 0, 0);
        $tcpdf->SetCellPadding(0);
        $tcpdf->SetAutoPageBreak(false);

        $tcpdf->SetAuthor('Mapping system for TAKAOKA PRESS');
        $tcpdf->SetTitle('Report');
        $tcpdf->SetSubject('Mapping system Report');
        $tcpdf->SetKeywords('PDF');

        return $tcpdf;
    }

    protected function renderTitle()
    {
        $A4 = $this->positions['A4'];

        $title = '車種：'.$this->vehicle.'　　'.$this->process.'工程　'.$this->inspection.'　　'.$this->partName.'　　'.$this->reportDate.'　　'.$this->choku;
        $printDate = '印刷日時：'.$this->now->toDateTimeString();

        $this->tcpdf->SetFont('kozgopromedium', '', 10);
        $this->tcpdf->Text($A4['x0'], $A4['y0'], $title);
        $this->tcpdf->SetFont('kozgopromedium', '', 8);
        $this->tcpdf->Text($A4['x0']+154, $A4['y0']+1, $printDate);
    }

    protected function renderAggregate($count1, $count0, $count2 = null)
    {
        $A4 = $this->positions['A4'];

        if ($count2 !== null) {
            $dhj = 54;
            $dhj1 = 5;
            $dhj2 = $dhj/3 - $dhj1;
            $hhj = 4;

            $this->tcpdf->MultiCell($dhj,  $hhj, $this->partName, 1, 'C', 0, 1, $A4['x0'], $A4['y1']);
            $this->tcpdf->MultiCell($dhj1, $hhj, '○',             1, 'C', 0, 1, $A4['x0'], $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj2, $hhj, $count1,         1, 'C', 0, 1, $A4['x0']+$dhj1, $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj1, $hhj, '×',             1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2, $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj2, $hhj, $count0,         1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj1, $hhj, '△',             1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1+$dhj2, $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj2, $hhj, $count2,         1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
        }
        else {
            $dhj = 36;
            $dhj1 = 5;
            $dhj2 = $dhj/2 - $dhj1;
            $hhj = 4;

            $this->tcpdf->MultiCell($dhj, $hhj, $this->partName, 1, 'C', 0, 1, $A4['x0'], $A4['y1']);
            $this->tcpdf->MultiCell($dhj1, $hhj, '○', 1, 'C', 0, 1, $A4['x0'], $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj2, $hhj, $count1, 1, 'C', 0, 1, $A4['x0']+$dhj1, $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj1, $hhj, '×', 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2, $A4['y1']+$hhj);
            $this->tcpdf->MultiCell($dhj2, $hhj, $count0, 1, 'C', 0, 1, $A4['x0']+$dhj1+$dhj2+$dhj1, $A4['y1']+$hhj);
        }

    }

    protected function renderA4($irs)
    {
        $A4 = $this->positions['A4'];

        $countAll = $irs->count();
        $count0 = $irs->filter(function($ir) {
            return $ir['status'] === 0;
        })->count();
        $count1 = $irs->filter(function($ir) {
            return $ir['status'] === 1;
        })->count();
        $count2 = $irs->filter(function($ir) {
            return $ir['status'] === 2;
        })->count();

        $d = [8, 18, 20, 30];
        $th = 5;

        foreach ($irs->chunk(100) as $page => $irs100) {
            $this->tcpdf->AddPage('P', 'A4');
            $this->renderTitle();

            if ($page === 0) {
                if ($this->inspection === '穴検査') {
                    $this->renderAggregate($count1, $count0, $count2);
                }
                else {
                    $this->renderAggregate($count1, $countAll - $count1);
                }
            }

            foreach ($irs100->values()->chunk(50) as $col => $irs50) {
                $this->tcpdf->SetFont('kozgopromedium', '', 8);
                $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['xmax']/2, $A4['y2'], 'No.');
                $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['xmax']/2, $A4['y2'], 'パネルID');
                $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['xmax']/2, $A4['y2'], '検査者');
                $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['xmax']/2, $A4['y2'], '出荷判定');
                $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['xmax']/2, $A4['y2'], '登録時間');

                foreach ($irs50->values() as $row => $ir) {
                    $panelId = $ir['panel_id'];
                    $inspectedBy = $ir['created_by'];
                    $time = $ir['inspected_at']->format('H:i');

                    $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,0))+$col*$A4['xmax']/2, $A4['y3']+($row)*$th, $page*100+$col*50+$row+1);
                    $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,1))+$col*$A4['xmax']/2, $A4['y3']+($row)*$th, $panelId);
                    $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,2))+$col*$A4['xmax']/2, $A4['y3']+($row)*$th, $inspectedBy);

                    $status = '';
                    switch ($ir['status']) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                        case 2: $status = '△'; break;
                    }

                    $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,3))+$col*$A4['xmax']/2, $A4['y3']+($row)*$th, $status);
                    $this->tcpdf->Text($A4['x0']+array_sum(array_slice($d,0,4))+$col*$A4['xmax']/2, $A4['y3']+($row)*$th, $time);
                }
            }

            $this->tcpdf->Line($A4['xmax']/2, 28, $A4['xmax']/2, 286);
            $this->tcpdf->Text(103, 287, 'page '.($page+1));
        }
    }

    public function generate($irs)
    {
        $this->renderA4($irs);

        /*
         * Render A3
         */
        $A3 = $this->positions['A3'];
        $d = [6, 18, 20, 26];
        $d_comment = 20;
        $d_date = 14;

        $fn = $this->failureTypes->count() + $this->modificationTypes->count();
        $fd = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_comment - $d_date)/$fn;

        foreach ($irs->chunk(40) as $page => $irs40) {
            $this->tcpdf->AddPage('L', 'A3');
            $this->renderTitle();

            $this->tcpdf->SetFont('kozgopromedium', '', 7);
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1'], 'No.');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1'], 'パネルID');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1'], '検査者');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1'], '出荷判定');

            foreach ($this->failureTypes as $fi => $f) {
                $this->tcpdf->Text($A3['x0']+array_sum($d)+($fd*$fi), $A3['y1'], $f['name']);
            }

            foreach ($this->modificationTypes as $mi => $m) {
                $this->tcpdf->Text($A3['x0']+array_sum($d)+$fd*($fi+1+$mi), $A3['y1'], $m['name']);
            }

            $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y1'], 'コメント');
            $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y1'], '登録時間');

            foreach ($irs40->values() as $row => $ir) {
                $panelId = $ir['panel_id'];
                $inspectedBy = $ir['created_by'];
                $time = $ir['inspected_at']->format('H:i');

                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $row+($page*40)+1);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panelId);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $inspectedBy);

                $status = '';
                switch ($ir['status']) {
                    case 0: $status = '×'; break;
                    case 1: $status = '○'; break;
                    case 2: $status = '△'; break;
                }
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                foreach ($this->failureTypes as $fi => $ft) {
                    $sum = $ir['fs']->filter(function($f) use ($ft) {
                        return $f == $ft['id'];
                    })->count();

                    $this->tcpdf->Text($A3['x0']+array_sum($d)+($fi*$fd), $A3['y2']+($row)*$A3['th'], $sum);
                }

                foreach ($this->modificationTypes as $mi => $mt) {
                    $sum = $ir['ms']->filter(function($m) use ($mt) {
                        return $m == $mt['id'];
                    })->count();

                    $this->tcpdf->Text($A3['x0']+array_sum($d)+$fd*($fi+1+$mi), $A3['y2']+($row)*$A3['th'], $sum);
                }

                if ($ir['comment']) {
                    $comment = mb_substr($ir['comment'], 0, 4, 'UTF-8').'..';
                } else {
                    $comment = '';
                }
                $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y2']+($row)*$A3['th'], $comment);                   
                $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $this->tcpdf->Line($A3['x0']+array_sum($d)+$fd*($fi+1)-3, 20, $A3['x0']+array_sum($d)+$fd*($fi+1)-4, 286, array('dash' => '2'));
            $this->tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $th = 10;
        $timeChunks = config('report.950A.2choku.timeChunks');
        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedIrs = [];
        foreach ($irs as $ir) {
            $time = $ir['inspected_at']->subHours($base['H'])->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->reportDate.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedIrs)) {
                    $timeChunkedIrs[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedIrs[$tc['label']][] = [
                        'iS' => $ir['status'],
                        'gS' => $ir['status'],
                        's' => $ir['status'],
                        'fs' => $ir['fs'],
                        'ms' => $ir['ms']
                    ];
                }
            }
        }

        $timeChunksSum = collect($timeChunkedIrs)->map(function($chunk) {
            $collected = collect($chunk);

            return collect([
                's' => [
                    0 => $collected->pluck('s')->filter(function($s) {
                        // return $s === 0;
                        return $s === 0 || $s === 2;
                    })->count(),
                    1 => $collected->pluck('s')->filter(function($s) {
                        return $s === 1;
                    })->count()
                ],
                'fs' => array_count_values($collected->pluck('fs')->flatten()->toArray()),
                'ms' => array_count_values($collected->pluck('ms')->flatten()->toArray())
            ]);
        });

        $fd = ($A3['xmax'] - $A3['x0']*2 - 48)/$fn;

        $this->tcpdf->AddPage('L', 'A3');
        $this->renderTitle();

        $this->tcpdf->SetFont('kozgopromedium', '', 7);
        $this->tcpdf->Text($A3['x0']+24, $A3['y1'], '出荷判定');

        foreach ($this->failureTypes as $fi => $ft) {
            $this->tcpdf->Text($A3['x0']+48+$fd*$fi, $A3['y1'], $ft['name']);
        }

        foreach ($this->modificationTypes as $mi => $mt) {
            $this->tcpdf->Text($A3['x0']+48+$fd*($fi+1+$mi), $A3['y1'], $mt['name']);
        }

        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $this->tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);

            if (count($sum) != 0) {
                $ip = 0;

                $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th-1.5, '×');
                $this->tcpdf->Text($A3['x0']+20-0.4, $A3['y2']+$n*$th+1.5, '○');

                $this->tcpdf->Text($A3['x0']+24, $A3['y2']+$n*$th-1.5, $sum['s'][0]);
                $this->tcpdf->Text($A3['x0']+24, $A3['y2']+$n*$th+1.5, $sum['s'][1]);

                foreach ($this->failureTypes as $fi => $ft) {
                    if (!array_key_exists($ft['id'], $sum['fs'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['fs'][$ft['id']];
                    }

                    $this->tcpdf->Text($A3['x0']+48+$fd*$fi, $A3['y2']+$n*$th, $f_sum);
                }

                foreach ($this->modificationTypes as $mi => $mt) {
                    if (!array_key_exists($mt['id'], $sum['ms'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['ms'][$mt['id']];
                    }

                    $this->tcpdf->Text($A3['x0']+48+$fd*($fi+1+$mi), $A3['y2']+$n*$th, $f_sum);
                }
            }

            $n = $n+1;
        }

        $this->tcpdf->Line($A3['x0'], 110, $A3['xmax'] - $A3['x0'], 110, array('dash' => '3,1'));
        $this->tcpdf->Line($A3['x0'], 190, $A3['xmax'] - $A3['x0'], 190, array('dash' => '3,1'));
        $this->tcpdf->Line($A3['x0']+48+$fd*($fi+1)-3, 20, $A3['x0']+48+$fd*($fi+1)-4, 196, array('dash' => '2'));

        return $this->tcpdf;
    }

    public function generateGaikanWithInline($irs, $inlineStatus)
    {
        $irs = $irs->map(function($ir) use($inlineStatus) {
            $gStatus = $ir['status'];

            $iStatusArr = $inlineStatus->first(function($k, $iS) use($ir) {
                return $iS['part_id'] == $ir['part_id'];
            });


            if (is_null($iStatusArr)) {
                $status = $gStatus;
                $iStatus = -1;
            }
            else {
                $iStatus = $iStatusArr['status'];
                if ($gStatus === 1 &&  $iStatus === 1) {
                    $status = 1;
                }
                elseif ($gStatus === 2 &&  $iStatus === 1) {
                    $status = 2;
                }
                else {
                    $status = 0;
                }
            }

            $statusArray = [
                'status' => $status,
                'gStatus' => $gStatus,
                'iStatus' => $iStatus
            ];

            return array_merge($ir, $statusArray);
        });

        $this->renderA4($irs);

        /*
         * Render A3
         */
        $A3 = $this->positions['A3'];
        $d = [6, 18, 20, 26, 26];
        $d_comment = 20;
        $d_date = 14;

        $fn = $this->failureTypes->count() + $this->modificationTypes->count();
        $fd = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $d_comment - $d_date)/$fn;

        foreach ($irs->chunk(40) as $page => $irs40) {
            $this->tcpdf->AddPage('L', 'A3');
            $this->renderTitle();

            $this->tcpdf->SetFont('kozgopromedium', '', 7);
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1'], 'No.');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1'], 'パネルID');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1'], '検査者');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1'], '精度出荷判定');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4)), $A3['y1'], '外観出荷判定');

            foreach ($this->failureTypes as $fi => $f) {
                $this->tcpdf->Text($A3['x0']+array_sum($d)+($fd*$fi), $A3['y1'], $f['name']);
            }

            foreach ($this->modificationTypes as $mi => $m) {
                $this->tcpdf->Text($A3['x0']+array_sum($d)+$fd*($fi+1+$mi), $A3['y1'], $m['name']);
            }

            $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y1'], 'コメント');
            $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y1'], '登録時間');

            foreach ($irs40->values() as $row => $ir) {
                $panelId = $ir['panel_id'];
                $inspectedBy = $ir['created_by'];
                $time = $ir['inspected_at']->format('H:i');

                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $row+($page*40)+1);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $panelId);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $inspectedBy);

                // $status = '';
                // switch ($ir['status']) {
                //     case 0: $status = '×'; break;
                //     case 1: $status = '○'; break;
                //     case 2: $status = '△'; break;
                // }
                $gStatus = '';
                switch ($ir['gStatus']) {
                    case 0: $gStatus = '×'; break;
                    case 1: $gStatus = '○'; break;
                    case 2: $gStatus = '△'; break;
                }
                $iStatus = '';
                switch ($ir['iStatus']) {
                    case 0: $iStatus = '×'; break;
                    case 1: $iStatus = '○'; break;
                    case -1: $iStatus = '-'; break;
                }
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $iStatus);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4)), $A3['y2']+($row)*$A3['th'], $gStatus);

                foreach ($this->failureTypes as $fi => $ft) {
                    $sum = $ir['fs']->filter(function($f) use ($ft) {
                        return $f == $ft['id'];
                    })->count();

                    $this->tcpdf->Text($A3['x0']+array_sum($d)+($fi*$fd), $A3['y2']+($row)*$A3['th'], $sum);
                }

                foreach ($this->modificationTypes as $mi => $mt) {
                    $sum = $ir['ms']->filter(function($m) use ($mt) {
                        return $m == $mt['id'];
                    })->count();

                    $this->tcpdf->Text($A3['x0']+array_sum($d)+$fd*($fi+1+$mi), $A3['y2']+($row)*$A3['th'], $sum);
                }

                if ($ir['comment']) {
                    $comment = mb_substr($ir['comment'], 0, 4, 'UTF-8').'..';
                } else {
                    $comment = '';
                }
                $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd, $A3['y2']+($row)*$A3['th'], $comment);                   
                $this->tcpdf->Text($A3['x0']+array_sum($d)+$fn*$fd+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $this->tcpdf->Line($A3['x0']+array_sum($d)+$fd*($fi+1)-3, 20, $A3['x0']+array_sum($d)+$fd*($fi+1)-4, 286, array('dash' => '2'));
            $this->tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $th = 10;
        $timeChunks = config('report.950A.2choku.timeChunks');
        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedIrs = [];
        foreach ($irs as $ir) {
            $time = $ir['inspected_at']->subHours($base['H'])->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->reportDate.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedIrs)) {
                    $timeChunkedIrs[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedIrs[$tc['label']][] = [
                        's' => $ir['status'],
                        'iS' => $ir['iStatus'],
                        'gS' => $ir['gStatus'],
                        'fs' => $ir['fs'],
                        'ms' => $ir['ms']
                    ];
                }
            }
        }

        $timeChunksSum = collect($timeChunkedIrs)->map(function($chunk) {
            $collected = collect($chunk);

            return collect([
                's' => [
                    0 => $collected->pluck('s')->filter(function($s) {
                        // return $s === 0;
                        return $s === 0 || $s === 2;
                    })->count(),
                    1 => $collected->pluck('s')->filter(function($s) {
                        return $s === 1;
                    })->count()
                ],
                'iS' => [
                    0 => $collected->pluck('iS')->filter(function($s) {
                        return $s === 0;
                    })->count(),
                    1 => $collected->pluck('iS')->filter(function($s) {
                        return $s === 1;
                    })->count()
                ],
                'gS' => [
                    0 => $collected->pluck('gS')->filter(function($s) {
                        // return $s === 0;
                        return $s === 0 || $s === 2;
                    })->count(),
                    1 => $collected->pluck('gS')->filter(function($s) {
                        return $s === 1;
                    })->count()
                ],
                'fs' => array_count_values($collected->pluck('fs')->flatten()->toArray()),
                'ms' => array_count_values($collected->pluck('ms')->flatten()->toArray())
            ]);
        });

        $fd = ($A3['xmax'] - $A3['x0']*2 - 100)/$fn;

        $this->tcpdf->AddPage('L', 'A3');
        $this->renderTitle();

        $this->tcpdf->SetFont('kozgopromedium', '', 7);
        $this->tcpdf->Text($A3['x0']+24, $A3['y1'], '出荷判定');
        $this->tcpdf->Text($A3['x0']+24+24, $A3['y1'], '精度判定');
        $this->tcpdf->Text($A3['x0']+24+48, $A3['y1'], '外観判定');

        foreach ($this->failureTypes as $fi => $ft) {
            $this->tcpdf->Text($A3['x0']+100+$fd*$fi, $A3['y1'], $ft['name']);
        }

        foreach ($this->modificationTypes as $mi => $mt) {
            $this->tcpdf->Text($A3['x0']+100+$fd*($fi+1+$mi), $A3['y1'], $mt['name']);
        }

        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $this->tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);

            if (count($sum) != 0) {
                $ip = 0;

                $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th-1.5, '×');
                $this->tcpdf->Text($A3['x0']+20-0.4, $A3['y2']+$n*$th+1.5, '○');

                $this->tcpdf->Text($A3['x0']+24, $A3['y2']+$n*$th-1.5, $sum['s'][0]);
                $this->tcpdf->Text($A3['x0']+24, $A3['y2']+$n*$th+1.5, $sum['s'][1]);

                $this->tcpdf->Text($A3['x0']+24+24, $A3['y2']+$n*$th-1.5, $sum['iS'][0]);
                $this->tcpdf->Text($A3['x0']+24+24, $A3['y2']+$n*$th+1.5, $sum['iS'][1]);

                $this->tcpdf->Text($A3['x0']+24+48, $A3['y2']+$n*$th-1.5, $sum['gS'][0]);
                $this->tcpdf->Text($A3['x0']+24+48, $A3['y2']+$n*$th+1.5, $sum['gS'][1]);

                foreach ($this->failureTypes as $fi => $ft) {
                    if (!array_key_exists($ft['id'], $sum['fs'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['fs'][$ft['id']];
                    }

                    $this->tcpdf->Text($A3['x0']+100+$fd*$fi, $A3['y2']+$n*$th, $f_sum);
                }

                foreach ($this->modificationTypes as $mi => $mt) {
                    if (!array_key_exists($mt['id'], $sum['ms'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['ms'][$mt['id']];
                    }

                    $this->tcpdf->Text($A3['x0']+100+$fd*($fi+1+$mi), $A3['y2']+$n*$th, $f_sum);
                }
            }

            $n = $n+1;
        }

        $this->tcpdf->Line($A3['x0'], 110, $A3['xmax'] - $A3['x0'], 110, array('dash' => '3,1'));
        $this->tcpdf->Line($A3['x0'], 190, $A3['xmax'] - $A3['x0'], 190, array('dash' => '3,1'));
        $this->tcpdf->Line($A3['x0']+100+$fd*($fi+1)-3, 20, $A3['x0']+100+$fd*($fi+1)-4, 196, array('dash' => '2'));

        return $this->tcpdf;
    }

    public function generateForHole($irs)
    {
        $this->renderA4($irs);

        /*
         * Render A3
         */
        $A3 = $this->positions['A3'];

        $d = [5, 12, 14, 11];
        $margin = 2;
        $d_comment = 14;
        $d_date = 8;

        $fn = $this->failureTypes->count();
        $fd = 4;

        $hn = $this->holeTypes->count();
        $d_hole_max = 16;
        $d_hole = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $fd*$fn - $margin - $d_comment - $d_date)/$hn;

        foreach ($irs->chunk(40) as $page => $irs40) {
            $this->tcpdf->AddPage('L', 'A3');
            $this->renderTitle();

            $this->tcpdf->SetFont('kozgopromedium', '', 6);
            if ($page === 0) {
                $this->tcpdf->Text($A3['x0'], $A3['y1'], '○ : 公差内　× : 穴径大　△ : 穴径小　* : 手直し');
            }

            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1_ana'], 'No.');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1_ana'], 'パネルID');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1_ana'], '検査者');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1_ana'], '出荷判定');

            foreach ($this->holeTypes as $hi => $hole) {
                $this->tcpdf->StartTransform();
                $this->tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hi)+1, $A3['y1_ana']+1);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hi), $A3['y1_ana'], $hole['l']);
                $this->tcpdf->StopTransform();
            }

            foreach ($this->failureTypes as $fi => $f) {
                $this->tcpdf->StartTransform();
                $this->tcpdf->Rotate(90, $A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fi)+1, $A3['y1_ana']+1);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fi), $A3['y1_ana'], $f['name']);
                $this->tcpdf->StopTransform();
            }

            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y1_ana'], 'コメント');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y1_ana'], '登録時間');

            foreach ($irs40->values() as $row => $ir) {
                $status = '';
                switch ($ir['status']) {
                    case 0: $status = '×'; break;
                    case 1: $status = '○'; break;
                    case 2: $status = '△'; break;
                }

                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $page*40+$row+1);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $ir['panel_id']);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $ir['created_by']);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                foreach ($this->holeTypes as $hi => $ht) {
                    switch ($ir['hs'][$ht['id']]['status']) {
                        case 0: $status = '×'; break;
                        case 1: $status = '○'; break;
                        case 2: $status = '△'; break;
                    }

                    if ($ir['hs'][$ht['id']]['hm'] !== -1) {
                        $this->tcpdf->SetFont('kozgopromedium', '', 4);
                        $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($hi*$d_hole)+0.5, $A3['y2']+($row)*$A3['th']-1.5, '※');
                        $this->tcpdf->SetFont('kozgopromedium', '', 6);
                    }
                    $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($hi*$d_hole), $A3['y2']+($row)*$A3['th'], $status);
                }

                // Render failures
                foreach ($this->failureTypes as $fi => $ft) {
                    $sum = $ir['fs']->filter(function($f) use ($ft) {
                        return $f == $ft['id'];
                    })->count();

                    $this->tcpdf->Text($A3['x0']+array_sum($d)+($d_hole*$hn)+$margin+($fd*$fi), $A3['y2']+$row*$A3['th'], $sum);
                }

                $comment = '';
                if ($ir['comment']) {
                    $comment = mb_substr($ir['comment'], 0, 4, 'UTF-8').'..';
                }
                $time = $ir['inspected_at']->format('H:i');

                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn), $A3['y2']+($row)*$A3['th'], $comment);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin+($fd*$fn)+$d_comment, $A3['y2']+($row)*$A3['th'], $time);
            }

            $this->tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $th = 10;
        $timeChunks = config('report.950A.2choku.timeChunks');
        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedIrs = [];
        foreach ($irs as $ir) {
            $time = $ir['inspected_at']->subHours($base['H'])->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->reportDate.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedIrs)) {
                    $timeChunkedIrs[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedIrs[$tc['label']][] = [
                        's' => $ir['status'],
                        'fs' => $ir['fs'],
                        'hs' => $ir['hs']
                    ];
                }
            }
        }

        $timeChunksSum = collect($timeChunkedIrs)->map(function($chunk) {
            $collected = collect($chunk);

            $result['fs'] = array_count_values($collected->pluck('fs')->flatten()->toArray());
            $result['hs'] = $collected->pluck('hs')
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


        $this->tcpdf->AddPage('L', 'A3');
        $this->renderTitle();

        $this->tcpdf->SetFont('kozgopromedium', '', 6);
        foreach ($this->holeTypes as $hi => $ht) {
            $this->tcpdf->StartTransform();
            $this->tcpdf->Rotate(90, $A3['x0']+24+($hi*$d_hole)+1, $A3['y1_ana']+3+1);
            $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y1_ana']+3, $ht['l']);
            $this->tcpdf->StopTransform();
        }
        foreach ($this->failureTypes as $fi => $ft) {
            $this->tcpdf->StartTransform();
            $this->tcpdf->Rotate(90, $A3['x0']+24+($d_hole*$hn)+$margin+($fd*$fi)+1, $A3['y1_ana']+3+1);
            $this->tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($fd*$fi), $A3['y1_ana']+3, $ft['name']);
            $this->tcpdf->StopTransform();
        }

        // Render table body
        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $this->tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);
            $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th, '×');
            $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+3, '△');
            $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+6, '○');


            if (count($sum['hs']) != 0) {
                foreach ($this->holeTypes as $hi => $ht) {
                    $sum0 = $sum['hs'][$ht['id']]->has(0) ? $sum['hs'][$ht['id']][0] : 0;
                    $sum1 = $sum['hs'][$ht['id']]->has(1) ? $sum['hs'][$ht['id']][1] : 0;
                    $sum2 = $sum['hs'][$ht['id']]->has(2) ? $sum['hs'][$ht['id']][2] : 0;

                    $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y2']+$n*$th, $sum0);
                    $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y2']+$n*$th+3, $sum2);
                    $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y2']+$n*$th+6, $sum1);
                }

                foreach ($this->failureTypes as $fi => $ft) {
                    if (!array_key_exists($ft['id'], $sum['fs'])) {
                        $f_sum = 0;
                    }
                    else {
                        $f_sum = $sum['fs'][$ft['id']];
                    }

                    $this->tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($fi*$fd), $A3['y2']+$n*$th, $f_sum);
                }
            }
            else {
                foreach ($this->holeTypes as $hi => $ht) {
                    $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y2']+$n*$th, 0);
                    $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y2']+$n*$th+3, 0);
                    $this->tcpdf->Text($A3['x0']+24+($hi*$d_hole), $A3['y2']+$n*$th+6, 0);
                }

                foreach ($this->failureTypes as $i => $f) {
                    $this->tcpdf->Text($A3['x0']+24+($d_hole*$hn)+$margin+($i*$fd), $A3['y2']+$n*$th, 0);
                }
            }

            $n = $n+1;
        }

        $this->tcpdf->Line($A3['x0'], 112.6, $A3['xmax'] - $A3['x0'], 112.6, array('dash' => '3,1'));
        $this->tcpdf->Line($A3['x0'], 192.6, $A3['xmax'] - $A3['x0'], 192.6, array('dash' => '3,1'));

        return $this->tcpdf;
    }

    public function generateForInline($irs)
    {
        $this->renderA4($irs);

        /*
         * Render A3
         */
        $A3 = $this->positions['A3'];

        $d = [8, 16, 16, 16];
        $margin = 2;
        $d_date = 12;

        $hn = $this->inlineTypes->count();
        $d_hole_max = 16;
        $d_hole = ($A3['xmax'] - $A3['x0']*2 - array_sum($d) - $margin - $d_date)/$hn;

        foreach ($irs->chunk(40) as $page => $irs40) {
            $this->tcpdf->AddPage('L', 'A3');
            $this->renderTitle();

            $this->tcpdf->SetFont('kozgopromedium', '', 8);
            if ($page === 0) {
                // $this->tcpdf->Text($A3['x0'], $A3['y1'], '○ : 公差内　× : 穴径大　△ : 穴径小　* : 手直し');
            }

            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y1_ana'], 'No.');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y1_ana'], 'パネルID');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y1_ana'], '検査者');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y1_ana'], '出荷判定');

            foreach ($this->inlineTypes as $ii => $it) {
                $this->tcpdf->SetFont('kozgopromedium', '', 8);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$ii), $A3['y1_ana'], 'P-'.$it['l']);
                $tolerance = number_format($it['min'],2).'〜'.number_format($it['max'],2);
                $this->tcpdf->SetFont('kozgopromedium', '', 6);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$ii), $A3['y1_ana']+3, $tolerance);
            }
            $this->tcpdf->SetFont('kozgopromedium', '', 8);

            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin, $A3['y1_ana'], 'コメント');
            $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin, $A3['y1_ana'], '登録時間');

            foreach ($irs40->values() as $row => $ir) {
                $status = $ir['status'] == 1 ? '○' : '×';

                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,0)), $A3['y2']+($row)*$A3['th'], $page*40+$row+1);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,1)), $A3['y2']+($row)*$A3['th'], $ir['panel_id']);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,2)), $A3['y2']+($row)*$A3['th'], $ir['created_by']);
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,3)), $A3['y2']+($row)*$A3['th'], $status);

                foreach ($this->inlineTypes as $ii => $it) {
                    $status = $ir['is'][$it['id']]['status'];
                    $judge = '';
                    if ($status > $it['max'] || $status < $it['min']) {
                        $judge = '×';
                    }

                    $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($ii*$d_hole), $A3['y2']+($row)*$A3['th'], $status);
                    $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($ii*$d_hole)+9, $A3['y2']+($row)*$A3['th'], $judge);
                }

                $time = $ir['inspected_at']->format('H:i');
                $this->tcpdf->Text($A3['x0']+array_sum(array_slice($d,0,4))+($d_hole*$hn)+$margin, $A3['y2']+($row)*$A3['th'], $time);
            }

            $this->tcpdf->Text(210, 286, 'page '.($page+1));
        }

        /*
         * Render A3 Aggregation
         */
        $th = 10;
        $timeChunks = config('report.950A.2choku.timeChunks');
        $base = $timeChunks[0]['start'];

        // Divide families to time Chunk
        $timeChunkedIrs = [];
        foreach ($irs as $ir) {
            $time = $ir['inspected_at']->subHours($base['H'])->subMinutes($base['i']);

            $minutes = $time->hour*60 + $time->minute;
            if ($time->gte(Carbon::createFromFormat('Y/m/d H:i:s', $this->reportDate.' 00:00:00')->addDay())) {
                $minutes = $minutes + 60*24;
            }

            foreach ($timeChunks as $tc) {
                $min = ($tc['start']['H'] - $base['H'])*60 + ($tc['start']['i'] - $base['i']);
                $max = ($tc['end']['H'] - $base['H'])*60 + ($tc['end']['i'] - $base['i']);

                if (!array_key_exists($tc['label'], $timeChunkedIrs)) {
                    $timeChunkedIrs[$tc['label']] = [];
                }

                if ($minutes >= $min && $minutes < $max) {
                    $timeChunkedIrs[$tc['label']][] = [
                        's' => $ir['status'],
                        'is' => $ir['is']
                    ];
                }
            }
        }

        $inlineTypes = $this->inlineTypes;
        $timeChunksSum = collect($timeChunkedIrs)->map(function($chunk) use($inlineTypes) {
            $collected = collect($chunk);

            $result['is'] = $collected->pluck('is')
                ->flatten(1)
                ->groupBy('id')
                ->map(function($in) use($inlineTypes) {
                    $sum0 = $in->filter(function($i) use($inlineTypes) {
                        $iType = $inlineTypes->first(function ($key, $value) use ($i){
                            return $value['id'] == $i['id'];
                        });
                        $min = $iType['min'];
                        $max = $iType['max'];

                        return $i['status'] < $min || $i['status'] > $max;
                    })->count();
                    $sum1 = $in->count() - $sum0;
                    return [
                        'sum0' => $sum0,
                        'sum1' => $sum1
                    ];
                });

            return collect($result);
        });

        $this->tcpdf->AddPage('L', 'A3');
        $this->renderTitle();

        $this->tcpdf->SetFont('kozgopromedium', '', 8);

        $n = 0;
        foreach ($timeChunksSum as $key => $sum) {
            $this->tcpdf->Text($A3['x0'], $A3['y2']+$n*$th, $key);
            $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th, '×');
            $this->tcpdf->Text($A3['x0']+20, $A3['y2']+$n*$th+4, '○');

            if (count($sum['is']) != 0) {
                foreach ($this->inlineTypes as $ii => $it) {
                    $sum0 = $sum['is'][$it['id']]['sum0'];
                    $sum1 = $sum['is'][$it['id']]['sum1'];

                    $this->tcpdf->Text($A3['x0']+24+($ii*$d_hole), $A3['y2']+$n*$th, $sum0);
                    $this->tcpdf->Text($A3['x0']+24+($ii*$d_hole), $A3['y2']+$n*$th+4, $sum1);
                }
            }
            else {
                foreach ($this->inlineTypes as $ii => $it) {
                    $this->tcpdf->Text($A3['x0']+24+($ii*$d_hole), $A3['y2']+$n*$th, 0);
                    $this->tcpdf->Text($A3['x0']+24+($ii*$d_hole), $A3['y2']+$n*$th+4, 0);
                }
            }

            $n = $n+1;
        }

        $this->tcpdf->Line($A3['x0'], 112.6, $A3['xmax'] - $A3['x0'], 112.6, array('dash' => '3,1'));
        $this->tcpdf->Line($A3['x0'], 192.6, $A3['xmax'] - $A3['x0'], 192.6, array('dash' => '3,1'));

        return $this->tcpdf;
    }
}
