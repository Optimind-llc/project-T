<?php

namespace App\Services\Vehicle950A;

use Carbon\Carbon;

class Choku
{
    protected $now;
    protected $date;
    protected $chokuInfo;

    public function __construct(Carbon $date) {
        $this->now = Carbon::now();
        $this->date = $date;
        $this->chokuInfo = config('report.950A.2choku');
    }

    public function getDayStart() {
        $d = $this->chokuInfo['start']['d'];
        $H = $this->chokuInfo['start']['H'];
        $i = $this->chokuInfo['start']['i'];

        return $this->date->copy()->addDay($d)->addHours($H)->addMinutes($i);
    }

    public function getDayEnd() {
        $d = $this->chokuInfo['end']['d']+$this->chokuInfo['over']['d'];
        $H = $this->chokuInfo['end']['H']+$this->chokuInfo['over']['H'];
        $i = $this->chokuInfo['end']['i']+$this->chokuInfo['over']['i'];

        return $this->date->copy()->addDay($d)->addHours($H)->addMinutes($i);
    }

    public function getLastChokuStart() {
        $today = Carbon::today();

        $t2_end_at   = $today->copy()->addHours(1)->addMinutes(0);
        $t1_start_at = $today->copy()->addHours(6)->addMinutes(30);
        $t2_start_at = $today->copy()->addHours(16)->addMinutes(15);

        // between 00:00 ~ 01:00
        if ($this->now->lt($t1_start_at)) {
            $start_at = $t1_start_at->copy()->subDay();
        }
        // between 01:00 ~ 06:30
        elseif ($this->now->lt($t1_start_at)) {
            if ($this->now->dayOfWeek === 1) {
                $start_at = $t2_start_at->copy()->subDays(3);
            }
            else {
                $start_at = $t2_start_at->copy()->subDay();
            }
        }
        // between 06:30 ~ 16:15
        elseif ($this->now->lt($t2_start_at)) {
            if ($this->now->dayOfWeek === 1) {
                $start_at = $t2_start_at->copy()->subDays(3);
            }
            else {
                $start_at = $t2_start_at->copy()->subDay();
            }
        }
        // between 16:15 ~ 24:00
        else {
            $start_at = $t1_start_at;
        }

        return $start_at;
    }

    public function getChokuNum() {
        $Hi = $this->date->format('Hi');

        if ($Hi > 650 && $Hi < 1615) {
            $choku = 1;
        } else {
            $choku = 2;
        }

        return $choku;
    }
}
