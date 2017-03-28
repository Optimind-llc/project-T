<?php

namespace App\Repositories;

use App\Models\Vehicle950A\HoleType;

/**
 * Class HoleTypeRepository.
 */
class HoleTypeRepository
{
    public function getByIds($ids)
    {
        return HoleType::whereIn('id', $ids)
            ->orderBy('label')
            ->select(['id', 'label'])
            ->get();
    }

    public function getOnlyActiveByPn($pn)
    {
        return HoleType::where('pt_pn', '=', $pn)
            ->where('status', '=', 1)
            ->select(['id', 'x', 'y', 'label', 'direction', 'shape', 'border', 'color', 'pt_pn', 'figure_id', 'status'])
            ->get();
    }

    public function getAllByPns($pns, $inspection)
    {
        return HoleType::join('figures as fig', function($join) use($inspection) {
                $join->on('fig.id', '=', 'hole_types.figure_id')
                    ->where('fig.inspection', '=', $inspection);
            })
            ->whereIn('hole_types.pt_pn', $pns)
            ->orderBy('hole_types.pt_pn')
            ->orderBy('hole_types.label')
            ->select(['fig.id as fig_id', 'fig.inspection', 'hole_types.id', 'hole_types.pt_pn', 'x', 'y', 'label', 'direction'])
            ->get()
            ->map(function($ht) {
                return [
                    'id' => $ht->id,
                    'pn' => $ht->pt_pn,
                    'x' => $ht->x,
                    'y' => $ht->y,
                    'l' => $ht->label,
                    'd' => $ht->direction,
                    'fig' => $ht->fig_id,
                    'i' => $ht->inspection
                ];
            });
    }
}
