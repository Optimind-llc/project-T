<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HoleModificationType
 * @package App\Models
 */
class HoleModificationType extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function scopeNarrow($query, $p, $i, $d)
    {
        return $query->join('hmt_related as hmtr', function ($join) use ($p, $i, $d){
            $join->on('hole_modification_types.id', '=', 'hmtr.type_id')
                ->where('hmtr.process', '=', $p)
                ->where('hmtr.inspection', '=', $i)
                ->where('hmtr.division', '=', $d);
        })
        ->where('hole_modification_types.status', '=', 1)
        ->select('hole_modification_types.*', 'hmtr.sort', 'hmtr.type');
    }
}
