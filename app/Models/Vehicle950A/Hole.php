<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Hole
 * @package App\Models
 */
class Hole extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function type()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\HoleType',
            'type_id',
            'id'
        );
    }

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Figure',
            'figure_id',
            'id'
        );
    }
}
