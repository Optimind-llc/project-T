<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hole
 * @package App\Models
 */
class Hole extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Figure',
            'figure_id',
            'id'
        );
    }

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\partType',
            'part_type_id',
            'id'
        );
    }
}
