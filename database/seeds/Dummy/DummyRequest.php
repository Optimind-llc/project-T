<?php
namespace Database\Seeding;
use Illuminate\Http\Request;
/**
 * Class DummyRequest
 */
class DummyRequest extends Request
{
    public $panelId = '';
    public $family = '';

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