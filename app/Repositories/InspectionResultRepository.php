<?php

namespace App\Repositories;

// Models
use App\Models\Vehicle950A\InspectionResult;

/**
 * Class InspectionResultRepository.
 */
class InspectionResultRepository
{
    public function exist($p, $i, $partId)
    {
        $ir = InspectionResult::identify($p, $i, $partId)->first();
        return $ir instanceof InspectionResult;
    }

    public function all($p, $i, $partId)
    {
        $ir = InspectionResult::identify($p, $i, $partId)
            ->with([
                'failures' => function($q) {
                    $q->select('id', 'x', 'y', 'type_id', 'ir_id', 'figure_id');
                },
                'failures.figure' => function($q) {
                    $q->select('id', 'name', 'path');
                }
            ])
            ->select(['id', 'process', 'inspection', 'line', 'status', 'comment', 'created_choku', 'created_by', 'created_at'])
            ->first();

        return $ir;
    }

    public function update($p, $i, $partId)
    {
        return true;
    }
}
