<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inline
 * @package App\Models
 */
class Inline extends Model
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
            'App\Models\PartType',
            'part_type_id',
            'id'
        );
    }

    public function pages()
    {
        return $this->belongsToMany(
            'App\Models\Client\Page',
            'inline_page',
            'inline_id',
            'page_id'
        )->withPivot('status');
    }
}
