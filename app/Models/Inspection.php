<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspection
 * @package App\Models
 */
class Inspection extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function groups()
    {
        return $this->hasMany(
            'App\Models\InspectionGroup',
            'inspection_id',
            'id'
        );
    }

    public function process()
    {
        return $this->belongsTo(
            'App\Models\Process',
            'process_id',
            'id'
        );
    }
}
