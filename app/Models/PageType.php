<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PageType
 * @package App\Models
 */
class PageType extends Model
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

    public function group()
    {
        return $this->belongsTo(
            'App\Models\InspectionGroup',
            'group_id',
            'id'
        );
    }

    public function partTypes()
    {
        return $this->belongsToMany(
            'App\Models\PartType',
            'part_type_page_type',
            'page_type_id',
            'part_type_id'
        );
    }

    public function pages()
    {
        return $this->hasMany(
            'App\Models\Page',
            'page_type_id',
            'id'
        );
    }
}
