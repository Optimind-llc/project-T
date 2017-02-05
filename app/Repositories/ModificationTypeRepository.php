<?php

namespace App\Repositories;

use App\Models\Vehicle950A\ModificationType;

/**
 * Class ModificationTypeRepository.
 */
class ModificationTypeRepository
{
    public function narrow($p, $i, $d)
    {
        return ModificationType::narrow($p, $i, $d)->get();
    }

    public function sorted($p, $i, $d)
    {
        $modifications = $this->narrow($p, $i, $d)
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

        foreach($modifications as $key => $row) {
            $mt_label_array[$key] = $row['label'];
            $mt_sort_array[$key] = $row['sort'];
        }

        if (count($modifications) > 0) {
            array_multisort($mt_sort_array, $mt_label_array, $modifications);
        }

        return $modifications;
    }
}
