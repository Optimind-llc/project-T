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

    public function sortedFailureTypes($p, $i, $d)
    {
        $sorted = $this->join('ft_related as ftr', function ($join) use ($p, $i, $d){
            $join->on('failure_types.id', '=', 'ftr.type_id')
                ->where('ftr.process', '=', $p)
                ->where('ftr.inspection', '=', $i)
                ->where('ftr.division', '=', $d);
        })
        ->select('failure_types.*', 'ftr.sort', 'ftr.type')
        ->get()
        ->map(function($ft) {
            return [
                'id' => $ft->id,
                'label' => $ft->label,
                'name' => $ft->name,
                'type' => $ft->type,
                'sort' => $ft->sort
            ];
        })
        ->toArray();

        foreach($sorted as $key => $row) {
            $ft_type_array[$key] = $row['type'];
            $ft_label_array[$key] = $row['label'];
            $ft_sort_array[$key] = $row['sort'];
        }

        if (count($sorted) > 0) {
            array_multisort($ft_type_array, $ft_sort_array, $ft_label_array, $sorted);
        }

        return collect($sorted);
    }

    // public function inspections()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Inspection',
    //         'failure_inspection',
    //         'failure_id',
    //         'inspection_id'
    //     )->withPivot('type', 'sort');
    // }
}
