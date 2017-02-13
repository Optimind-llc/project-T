<?php

namespace App\Repositories;

use App\Models\Vehicle950A\Failure;

/**
 * Class FailureRepository.
 */
class FailureRepository
{
    public function create($ir_id, $part_id, $param)
    {
        $new = new Failure;
        $new->ir_id = $ir_id;
        $new->part_id = $part_id;
        $new->figure_id = $param['figureId'];
        if (array_key_exists('point', $param)) {
            $exc = explode(',', $param['point']);

            $new->x = $exc[0];
            $new->y = $exc[1];
        }
        else {
            $new->x = $param['x'];
            $new->y = $param['y'];
        }
        $new->type_id = $param['failureTypeId'];
        $new->save();

        return $new;
    }

    public function deleteByIds($ids)
    {
        $deleted = Failure::whereIn('id', $ids)->delete();
        return $deleted;
    }
}
