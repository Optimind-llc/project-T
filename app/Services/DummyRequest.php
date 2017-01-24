<?php

namespace App\Services;

use Illuminate\Http\Request;

/**
 * Class DummyRequest
 */
class DummyRequest extends Request
{
    public $process = '';
    public $inspection = '';
    public $partNames = [];
    public $choku = '';
    public $worker = '';
    public $line = 1;
    public $parts = [];

    public function setForGet($process, $inspection, $partNames)
    {
        $this->process = $process;
        $this->inspection = $inspection;
        $this->partNames = $partNames;
    }

    public function setForSave($choku, $worker, $line, $parts)
    {
        $this->choku = $choku;
        $this->worker = $worker;
        $this->line = $line;
        $this->parts = $parts;
    }

    public function all()
    {
        return [
            'process' => $this->process,
            'inspection' => $this->inspection,
            'partNames' => $this->partNames,
            'choku' => $this->choku,
            'worker' => $this->worker,
            'line' => $this->line,
            'parts' => $this->parts
        ];
    }
}