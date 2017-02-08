<?php

namespace App\Repositories;

use App\Models\Vehicle950A\HoleModification;

/**
 * Class HoleModificationRepository.
 */
class HoleModificationRepository
{
    public function create($ir_id, $part_id, $param)
    {
        $new = new HoleModification;
        $new->ir_id = $ir_id;
        $new->part_id = $part_id;
        $new->hole_id = $param['holeId'];
        $new->type_id = $param['holeModificationTypeId'];
        $new->save();

        return $new;
    }

    public function deleteByHoleId($id)
    {
        $deleted = HoleModification::where('hole_id', '=', $id)->delete();
        return $deleted;
    }
}
