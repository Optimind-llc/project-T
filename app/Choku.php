<?php

namespace App;

use Carbon\Carbon;

class Choku
{
    protected $date;

    public function __construct(Carbon $date) {
        $this->setDate($date);
    }

    public function setDate(Carbon $date) {
        $this->date = $date;
    }

    public function getChoku() {
        $Hi = $this->date->format('Hi');

        $choku = 1;
        if ($Hi > 200 && $Hi <= 1615) {
            $choku = 1;
        } else {
            $choku = 2;
        }

        return $choku;
    }
}
