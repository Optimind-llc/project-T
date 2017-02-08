<?php

namespace App\Repositories;

use App\Models\Vehicle950A\Modification;

/**
 * Class ModificationRepository.
 */
class ModificationRepository
{
    public function create($ir_id, $part_id, $param)
    {
        $new = new Modification;
        $new->ir_id = $ir_id;
        $new->part_id = $part_id;
        $new->figure_id = $param['figureId'];
        $new->failure_id = $param['failureId'];
        $new->type_id = $param['modificationTypeId'];
        $new->save();

        return $new;
    }

    public function deleteByIds($ids)
    {
        $deleted = Modification::whereIn('id', $ids)->delete();
        return $deleted;
    }
}
