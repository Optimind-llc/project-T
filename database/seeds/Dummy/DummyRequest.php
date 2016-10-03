<?php

namespace Database\Seeding;

use Illuminate\Http\Request;

/**
 * Class DummyRequest
 */
class DummyRequest extends Request
{
    public $process = '';
    public $inspection = '';
    public $division = '';
    public $line = null;
    public $panelId = '';
    public $family = '';

    public function set($p, $i, $d, $l = null, $pId = '')
    {
        $this->process = $p;
        $this->inspection = $i;
        $this->division = $d;
        $this->line = $l;
        $this->panelId = $pId;
    }

    public function setFamily($f)
    {
        $this->family = $f;
    }

    public function all()
    {
        return [
            'process' => $this->process,
            'inspection' => $this->inspection,
            'division' => $this->division,
            'line' => $this->line,
            'panelId' => $this->panelId
        ];
    }
}
