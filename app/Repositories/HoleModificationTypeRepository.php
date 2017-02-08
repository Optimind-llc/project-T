<?php

namespace App\Repositories;

use App\Models\Vehicle950A\HoleModificationType;

/**
 * Class HoleModificationTypeRepository.
 */
class HoleModificationTypeRepository
{
    public function narrow($p, $i, $d)
    {
        return HoleModificationType::narrow($p, $i, $d)->get();
    }

    public function narrowedIds($p, $i, $d)
    {
        return $this->narrow($p, $i, $d)->map(function($hmt) {
            return $hmt->id;
        });
    }

    public function sorted($p, $i, $d)
    {
        $hModifications = $this->narrow($p, $i, $d)
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

        foreach($hModifications as $key => $row) {
            $hmt_label_array[$key] = $row['label'];
            $hmt_sort_array[$key] = $row['sort'];
        }

        if (count($hModifications) > 0) {
            array_multisort($hmt_sort_array, $hmt_label_array, $hModifications);
        }

        return $hModifications;
    }
}
