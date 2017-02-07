<?php

namespace App\Repositories;

use App\Models\Vehicle950A\Hole;

/**
 * Class HoleRepository.
 */
class HoleRepository
{
    public function create($ir_id, $part_id, $param)
    {
        $new = new Hole;
        $new->ir_id = $ir_id;
        $new->part_id = $part_id;
        $new->type_id = $param['holeTypeId'];
        $new->status = $param['status'];
        $new->save();

        return $new;
    }
}
