<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Figure
 * @package App\Models
 */
class Figure extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function pageType()
    {
        return $this->hasOne(
            'App\Models\PageType',
            'figure_id',
            'id'
        );
    }

    public function holes()
    {
        return $this->hasMany(
            'App\Models\Hole',
            'figure_id',
            'id'
        );
    }
}
