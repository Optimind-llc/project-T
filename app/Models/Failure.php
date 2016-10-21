<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class Failure extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function inspections()
    {
        return $this->belongsToMany(
            'App\Models\Inspection',
            'failure_inspection',
            'failure_id',
            'inspection_id'
        );
    }
}
