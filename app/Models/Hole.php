<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hole
 * @package App\Models
 */
class Hole extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

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
            'App\Models\PartType',
            'part_type_id',
            'id'
        );
    }

    public function holePages()
    {
        return $this->hasMany(
            'App\Models\Client\HolePage',
            'hole_id',
            'id'
        );
    }

    // public function pages()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Client\Page',
    //         'hole_page',
    //         'hole_id',
    //         'page_id'
    //     )->withPivot('status');
    // }
}
