<?php

namespace App\Repositories;

use App\Models\Vehicle950A\InlineType;

/**
 * Class InlineTypeRepository.
 */
class InlineTypeRepository
{
    public function getOnlyActiveByPn($pn)
    {
        return InlineType::where('pt_pn', '=', $pn)
            ->where('status', '=', 1)
            ->select(['id', 'x', 'y', 'label', 'direction', 'shape', 'border', 'color', 'pt_pn', 'figure_id', 'status'])
            ->get();
    }

    public function getAllByPns($pns)
    {
        return InlineType::whereIn('pt_pn', $pns)
            ->orderBy('label')
            ->get()
            ->map(function($it) {
                return [
                    'id' => $it->id,
                    'pn' => $it->pt_pn,
                    'x' => $it->x,
                    'y' => $it->y,
                    'lx' => $it->lx,
                    'ly' => $it->ly,
                    'l' => $it->label,
                    's' => $it->side,
                    'p' => $it->position,
                    'min' => $it->min,
                    'max' => $it->max,
                    'fig' => $it->figure_id
                ];
            });
    }
}
