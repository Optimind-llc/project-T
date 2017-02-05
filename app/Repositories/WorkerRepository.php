<?php

namespace App\Repositories;

use App\Models\Vehicle950A\Worker;

/**
 * Class WorkerRepository.
 */
class WorkerRepository
{
    public function narrow($p, $i, $d)
    {
        return Worker::narrow($p, $i, $d)->get();
    }

    public function formated($p, $i, $d)
    {
        $workers = $this->narrow($p, $i, $d);

        return $workers->map(function ($w) {
            return [
                'id' => $w->id,
                'name' => $w->name,
                'choku' => $w->choku_code,
                'sort' => $w->sort
            ];
        })
        ->sortBy('sort')
        ->groupBy('choku')
        ->toArray();
    }
}
