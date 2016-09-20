<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartType
 * @package App\Models
 */
class PartType extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function pageTypes()
    {
        return $this->belongsToMany(
            'App\Models\PageType',
            'part_type_page_type',
            'part_type_id',
            'page_type_id'
        );
    }

    public function vehicle()
    {
        return $this->belongsTo(
            'App\Models\Vehicle',
            'vehicle_id',
            'id'
        );
    }
}
