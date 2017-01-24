<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspector
 * @package App\Models
 */
class Worker extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    // public function groups()
    // {
    //     return $this->belongsTo(
    //         'App\Models\InspectorGroup',
    //         'group_code',
    //         'code'
    //     );
    // }

    // public function inspectionGroup()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\InspectionGroup',
    //         'inspector_inspection_group',
    //         'inspector_id',
    //         'inspection_g_id'
    //     )->withPivot('sort');
    // }
}
