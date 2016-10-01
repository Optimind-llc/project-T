<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InspectorGroup
 * @package App\Models
 */
class InspectorGroup extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $primaryKey = 'code';
    public $incrementing = false;

    public function inspectors()
    {
        return $this->hasMany(
            'App\Models\Inspector',
            'group_code',
            'code'
        );
    }
}
