<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class FailureType extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function scopeNarrow($query, $p, $i, $d)
    {
        return $query->join('ft_related as ftr', function ($join) use ($p, $i, $d){
            $join->on('failure_types.id', '=', 'ftr.type_id')
                ->where('ftr.process', '=', $p)
                ->where('ftr.inspection', '=', $i)
                ->where('ftr.division', '=', $d);
        })
        ->where('failure_types.status', '=', 1)
        ->select('failure_types.*', 'ftr.sort', 'ftr.type');
    }
}
