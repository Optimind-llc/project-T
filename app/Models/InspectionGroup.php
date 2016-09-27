<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InspectionGroup
 * @package App\Models
 */
class InspectionGroup extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function inspection()
    {
        return $this->belongsTo(
            'App\Models\Inspection',
            'inspection_id',
            'id'
        );
    }

    public function division()
    {
        return $this->belongsTo(
            'App\Models\Division',
            'division_id',
            'id'
        );
    }

    public function pageTypes()
    {
        return $this->hasMany(
            'App\Models\PageType',
            'group_id',
            'id'
        );
    }

    public function families()
    {
        return $this->hasMany(
            'App\Models\Client\InspectionFamily',
            'inspection_group_id',
            'id'
        );
    }
}
