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
    public $panelId = '';
    public $family = '';

    public function set($p, $i, $d, $pi = '')
    {
        $this->process = $p;
        $this->inspection = $i;
        $this->division = $d;
        $this->panelId = $pi;
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
            'panelId' => $this->panelId
        ];
    }
}
