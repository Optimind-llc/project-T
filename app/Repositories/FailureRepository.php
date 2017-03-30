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
        // Check key point exist
        if (array_key_exists('point', $param)) {
            $exc = explode(',', $param['point']);

            $x = $exc[0];
            $y = $exc[1];
        }
        else {
            $x = $param['x'];
            $y = $param['y'];
        }

        $face = 1;
        if (array_key_exists('face', $param)) {
            $face = $param['face'];
        }

        $new = new Failure;
        $new->ir_id = $ir_id;
        $new->part_id = $part_id;
        $new->figure_id = $param['figureId'];
        $new->type_id = $param['failureTypeId'];
        $new->x = $x;
        $new->y = $y;
        $new->face = $face;
        $new->save();

        return $new;
    }

    public function deleteByIds($ids)
    {
        $deleted = Failure::whereIn('id', $ids)->delete();
        return $deleted;
    }
}
