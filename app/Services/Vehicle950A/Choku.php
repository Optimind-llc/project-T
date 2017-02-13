<?php

namespace App\Services\Vehicle950A;

use Carbon\Carbon;

class Choku
{
    protected $date;
    protected $chokuInfo;

    public function __construct(Carbon $date) {
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
}
