<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Process
 * @package App\Models
 */
class Process extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function inspections()
    {
        return $this->hasMany(
            'App\Models\Inspection'
        );
    }

    public function inspectors()
    {
        return $this->belongsToMany(
            'App\Models\Inspector',
            'inspector_process',
            'process_id',
            'inspector_id'
        );
    }

    public function failures()
    {
        return $this->belongsToMany(
            'App\Models\Failure',
            'failure_process',
            'failure_id',
            'process_id'
        )
        ->withPivot('type');
    }
}
