<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class ModificationType extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function inspections()
    {
        return $this->belongsToMany(
            'App\Models\Vehicle950A\Inspection',
            'mt_related',
            'type_id',
            'inspection'
        )->withPivot('process', 'inspection', 'division', 'type', 'sort');
    }

    public function scopeNarrow($query, $p, $i, $d)
    {
        return $query->join('mt_related as mtr', function ($join) use ($p, $i, $d){
            $join->on('modification_types.id', '=', 'mtr.type_id')
                ->where('mtr.process', '=', $p)
                ->where('mtr.inspection', '=', $i)
                ->where('mtr.division', '=', $d);
        })
        ->where('modification_types.status', '=', 1)
        ->orderBy('mtr.sort')
        ->select('modification_types.*', 'mtr.sort', 'mtr.type');
    }
}
