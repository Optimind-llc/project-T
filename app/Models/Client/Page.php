<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 * @package App\Models
 */
class Page extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function pageType()
    {
        return $this->belongsTo(
            'App\Models\PageType',
            'page_type_id',
            'id'
        );
    }

    public function family()
    {
        return $this->belongsTo(
            'App\Models\Client\InspectionFamily',
            'family_id',
            'id'
        );
    }

    public function parts()
    {
        return $this->belongsToMany(
            'App\Models\Client\Part',
            'part_page',
            'page_id',
            'part_id'
        );
    }

    public function failurePositions()
    {
        return $this->hasMany(
            'App\Models\Client\FailurePosition',
            'page_id',
            'id'
        );
    }

    public function holes()
    {
        return $this->belongsToMany(
            'App\Models\Hole',
            'hole_page',
            'page_id',
            'hole_id'
        )
        ->withPivot('status');
    }
}


