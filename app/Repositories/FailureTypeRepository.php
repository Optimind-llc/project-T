<?php

namespace App\Repositories;

use App\Models\Vehicle950A\FailureType;

/**
 * Class FailureTypeRepository.
 */
class FailureTypeRepository
{
    public function narrow($p, $i, $d)
    {
        return FailureType::narrow($p, $i, $d)->get();
    }

    public function sorted($p, $i, $d)
    {
        $failures = $this->narrow($p, $i, $d)
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

        foreach($failures as $key => $row) {
            $ft_type_array[$key] = $row['type'];
            $ft_label_array[$key] = $row['label'];
            $ft_sort_array[$key] = $row['sort'];
        }

        if (count($failures) > 0) {
            array_multisort($ft_type_array, $ft_sort_array, $ft_label_array, $failures);
        }

        return $failures;
    }
}
