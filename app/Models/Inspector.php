<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspector
 * @package App\Models
 */
class Inspector extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function groups()
    {
        return $this->belongsTo(
            'App\Models\InspectorGroup',
            'group_code',
            'code'
        );
    }

    public function inspectionGroup()
    {
        return $this->belongsToMany(
            'App\Models\InspectionGroup',
            'inspector_inspection_group',
            'inspector_id',
            'inspection_g_id'
        )->withPivot('sort');
    }
}
