<?php

namespace App\Repositories;

use Carbon\Carbon;
// Models
use App\Models\Vehicle950A\InspectionResult;
// Repositories
use App\Repositories\FailureRepository;
use App\Repositories\ModificationRepository;
use App\Repositories\HoleRepository;
use App\Repositories\HoleModificationRepository;

/**
 * Class InspectionResultRepository.
 */
class InspectionResultRepository
{
    protected $now;
    protected $failure;
    protected $modification;
    protected $hole;
    protected $holeModification;

    public function __construct (
        FailureRepository $failure,
        ModificationRepository $modification,
        HoleRepository $hole,
        HoleModificationRepository $holeModification
    )
    {
        $this->now = Carbon::now();
        $this->failure = $failure;
        $this->modification = $modification;
        $this->hole = $hole;
        $this->holeModification = $holeModification;
    }

    protected function createFailures($ir_id, $part_id, $fs)
    {
        foreach ($fs as $f) {
            $new = $this->failure->create($ir_id, $part_id, $f);

            if (array_key_exists('modificationTypeId', $f) && $f['modificationTypeId'] != null) {
                $ms = [[
                    'figureId' => $f['figureId'],
                    'modificationTypeId' => $f['modificationTypeId'],
                    'failureId' => $new->id
                ]];
                $this->createModifications($ir_id, $part_id, $ms);
            }
        }
    }

    protected function createModifications($ir_id, $part_id, $ms)
    {
        foreach ($ms as $m) {
            $new = $this->modification->create($ir_id, $part_id, $m);
        }
    }

    protected function createHoles($ir_id, $part_id, $hs)
    {
        foreach ($hs as $h) {
            $new = $this->hole->create($ir_id, $part_id, $h);

            if (array_key_exists('holeModificationTypeId', $h) && $h['holeModificationTypeId'] != null) {
                $hms = [[
                    'holeModificationTypeId' => $h['holeModificationTypeId'],
                    'holeId' => $new->id
                ]];
                $this->createHoleModifications($ir_id, $part_id, $hms);
            }
        }
    }

    protected function updateHoles($ir_id, $part_id, $hs)
    {
        foreach ($hs as $h) {
            $update = $this->hole->update($h);

            $this->holeModification->deleteByHoleId($h['id']);
            if (array_key_exists('holeModificationTypeId', $h) && $h['holeModificationTypeId'] != null) {
                $hms = [[
                    'holeModificationTypeId' => $h['holeModificationTypeId'],
                    'holeId' => $update->id
                ]];
                $this->createHoleModifications($ir_id, $part_id, $hms);
            }
        }
    }

    protected function createHoleModifications($ir_id, $part_id, $hms)
    {
        foreach ($hms as $hm) {
            $new = $this->holeModification->create($ir_id, $part_id, $hm);
        }
    }

    protected function deleteFailures($dfs)
    {
        $this->failure->deleteByIds($dfs);
    }

    protected function deleteModifications($dms)
    {
        $this->modification->deleteByIds($dfs);
    }

    public function exist($p, $i, $partId)
    {
        $ir = InspectionResult::identify($p, $i, $partId)->first();
        return $ir instanceof InspectionResult;
    }

    public function all($p, $i, $partId)
    {
        return $ir = InspectionResult::identify($p, $i, $partId)
            ->with([
                'failures' => function($q) {
                    $q->select('id', 'x', 'y', 'type_id', 'ir_id', 'figure_id');
                },
                'failures.figure' => function($q) {
                    $q->select('id', 'page');
                },
                'modifications' => function($q) {
                    $q->select('id', 'type_id', 'failure_id', 'ir_id', 'figure_id');
                },
                'modifications.failure' => function($q) {
                    $q->select('id', 'x', 'y');
                },
                'modifications.figure' => function($q) {
                    $q->select('id', 'page');
                },
                'holes' => function($q) {
                    $q->select('id', 'type_id', 'ir_id', 'status');
                },
                'holes.holeModification' => function($q) {
                    $q->select('id', 'type_id', 'hole_id');
                }
            ])
            ->select([
                'id',
                'process',
                'inspection',
                'line',
                'status',
                'comment',
                'created_choku',
                'created_by',
                'created_at'
            ])
            ->first();
    }

    public function create($param, $fs, $ms, $hs)
    {
        $new = new InspectionResult;
        $new->part_id = $param['part_id'];
        $new->process = $param['process'];
        $new->inspection = $param['inspection'];
        $new->line = $param['line'];
        $new->ft_ids = serialize($param['ft_ids']->toArray());
        $new->mt_ids = serialize($param['mt_ids']->toArray());
        $new->hmt_ids = serialize($param['hmt_ids']->toArray());
        $new->created_choku = $param['created_choku'];
        $new->created_by = $param['created_by'];
        $new->status = $param['status'];
        $new->comment = $param['comment'];
        $new->inspected_at = $this->now;
        $new->created_at = $this->now;
        $new->updated_at = $this->now;
        $new->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($new->id, $param['part_id'], $fs);
        }

        // Create modifications
        if (count($ms) !== 0) {
            $this->createModifications($new->id, $param['part_id'], $ms);
        }

        // Create holes
        if (count($hs) !== 0) {
            $this->createHoles($new->id, $param['part_id'], $hs);
        }

        return $new;
    }

    public function update($param, $fs, $ms, $hs, $dfs, $dms)
    {
        $ir = InspectionResult::identify($param['process'], $param['inspection'], $param['part_id'])->first();
        $ft_ids = array_unique(array_merge($param['ft_ids']->toArray(), unserialize($ir->ft_ids)));
        $mt_ids = array_unique(array_merge($param['mt_ids']->toArray(), unserialize($ir->mt_ids)));
        $hmt_ids = array_unique(array_merge($param['hmt_ids']->toArray(), unserialize($ir->hmt_ids)));

        $ir->ft_ids = serialize($ft_ids);
        $ir->mt_ids = serialize($mt_ids);
        $ir->hmt_ids = serialize($hmt_ids);
        $ir->updated_choku = $param['updated_choku'];
        $ir->updated_by = $param['updated_by'];
        $ir->status = $param['status'];
        $ir->comment = $param['comment'];
        $ir->updated_at = $this->now;
        $ir->save();

        // Create failures
        if (count($fs) !== 0) {
            $this->createFailures($ir->id, $param['part_id'], $fs);
        }

        // Create modifications
        if (count($ms) !== 0) {
            $this->createModifications($ir->id, $param['part_id'], $ms);
        }

        // Update holes
        if (count($hs) !== 0) {
            $this->updateHoles($ir->id, $param['part_id'], $hs);
        }

        // Delete failures
        if (count($dfs) !== 0) {
            $this->deleteFailures($dfs);
        }

        // Delete modifications
        if (count($dms) !== 0) {
            $this->deleteFailures($dms);
        }

        return $ir;
    }

    public function delete($p, $i, $partId)
    {
        $delete = InspectionResult::identify($p, $i, $partId)->delete();
        return $delete;
    }

    public function listForReport($p, $i, $line, $pn, $start, $end, $choku)
    {
        $chokus = [$choku, 'NA'];
        $ir = InspectionResult::with([
                'failures' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'modifications' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'holes' => function($q) {
                    return $q->select('id', 'ir_id', 'type_id', 'status');
                },
                'holes.holeModification' => function($q) {
                    return $q->select('hole_id', 'type_id');
                },
            ])
            ->where('latest', '=', 1)
            ->where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->where('line', '=', $line)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->whereIn('created_choku', $chokus)
            ->whereHas('parts', function($q) use($pn) {
                $q->where('pn', '=', $pn);
            })
            ->select(['id', 'status', 'comment', 'ft_ids', 'mt_ids', 'hmt_ids', 'created_choku', 'updated_choku', 'created_by', 'updated_by', 'inspected_at', 'created_at', 'updated_at'])
            ->orderBy('inspection_results.created_at', 'asc')
            ->get()
            ->map(function($ir) {
                return [
                    'status' => $ir->status,
                    'comment' => $ir->comment,
                    'ft_ids' => unserialize($ir->ft_ids),
                    'mt_ids' => unserialize($ir->mt_ids),
                    'hmt_ids' => unserialize($ir->hmt_ids),
                    'ht_ids' => $ir->holes->map(function($h) {
                        return $h->type_id;
                    }),
                    'created_choku' => $ir->created_choku,
                    'updated_choku' => $ir->updated_choku,
                    'created_by' => $ir->created_by,
                    'updated_by' => $ir->updated_by,
                    'inspected_at' => $ir->inspected_at,
                    'created_at' => $ir->created_at,
                    'updated_at' => $ir->updated_at,
                    'fs' => $ir->failures->map(function($f) {
                        return $f->type_id;
                    }),
                    'ms' => $ir->modifications->map(function($m) {
                        return $m->type_id;
                    }),
                    'hs' => $ir->holes->keyBy('type_id')->map(function($h) {
                        $hm = -1;
                        if ($h->holeModification) {
                            $hm = $h->holeModification->type_id;
                        }

                        return [
                            'status' => $h->status,
                            'hm' =>  $hm
                        ];
                    })
                ];
            });

        return $ir;
    }   
}
