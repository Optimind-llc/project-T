<?php

namespace App\Repositories;

use App\Models\Vehicle950A\HoleType;

/**
 * Class HoleTypeRepository.
 */
class HoleTypeRepository
{
    public function getOnlyActiveByPn($pn)
    {
        return HoleType::where('pt_pn', '=', $pn)
            ->where('status', '=', 1)
            ->select(['id', 'x', 'y', 'label', 'direction', 'shape', 'border', 'color', 'pt_pn', 'figure_id', 'status'])
            ->get();
    }

    public function getAllByPn($pn)
    {
        return HoleType::where('pt_pn', '=', $pn)
            ->orderBy('label')
            ->get()
            ->map(function($ht) {
                return [
                    'id' => $ht->id,
                    'x' => $ht->x,
                    'y' => $ht->y,
                    'label' => $ht->label,
                    'direction' => $ht->direction,
                    'shape' => $ht->shape,
                    'border' => $ht->border,
                    'color' => $ht->color,
                    'fig' => $ht->figure_id
                ];
            });
    }
}
