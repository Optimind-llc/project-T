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
    protected $table = 'workers';
    protected $guarded = ['id'];

    public function scopeNarrow($query, $p, $i, $d)
    {
        return $query->join('worker_related as wr', function ($join) use ($p, $i, $d){
            $join->on('workers.id', '=', 'wr.worker_id')
                ->where('wr.process', '=', $p)
                ->where('wr.inspection', '=', $i)
                ->where('wr.division', '=', $d);
        })
        ->select('workers.*', 'wr.sort');
    }

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
