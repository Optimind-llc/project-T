<?php
namespace Database\Seeding;
use Illuminate\Http\Request;
/**
 * Class DummyRequest
 */
class DummyRequest extends Request
{
    public $partType = 0;
    public $panelId = '';
    public $id = [];
    public $family = '';

    public function set($partType, $panelId, $id)
    {
        $this->partType = $partType;
        $this->panelId = $panelId;
        $this->id = $id;
    }

    public function setFamily($f)
    {
        $this->family = $f;
    }

    public function all()
    {
        return [
            'partType' => $this->partType,
            'panelId' => $this->panelId,
            'id' => $this->id
        ];
    }
}