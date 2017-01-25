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

    public function sortedModificationTypes($p, $i, $d)
    {
        $sorted = $this->join('mt_related as mtr', function ($join) use ($p, $i, $d){
            $join->on('modification_types.id', '=', 'mtr.type_id')
                ->where('mtr.process', '=', $p)
                ->where('mtr.inspection', '=', $i)
                ->where('mtr.division', '=', $d);
        })
        ->select('modification_types.*', 'mtr.sort', 'mtr.type')
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
            $mt_label_array[$key] = $row['label'];
            $mt_sort_array[$key] = $row['sort'];
        }

        if (count($sorted) > 0) {
            array_multisort($mt_sort_array, $mt_label_array, $sorted);
        }

        return collect($sorted);
    }
}
