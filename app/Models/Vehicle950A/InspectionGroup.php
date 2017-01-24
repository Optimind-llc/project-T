<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InspectionGroup
 * @package App\Models
 */
class InspectionGroup extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function inspection()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Inspection',
            'inspection_en',
            'en'
        );
    }

    public function process()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Process',
            'process_en',
            'en'
        );
    }

    public function workers()
    {
        return $this->belongsToMany(
            'App\Models\Vehicle950A\Worker',
            'worker_inspection_group',
            'ig_id',
            'worker_id'
        )->withPivot('sort');
    }

    public function formatedWorkers()
    {
        return $this->workers->map(function ($w) {
            return [
                'id' => $w->id,
                'name' => $w->name,
                'choku' => $w->choku_code,
                'sort' => $w->pivot->sort
            ];
        })
        ->sortBy('sort')
        ->groupBy('choku');
    }

    public function failureTypes()
    {
        return $this->belongsToMany(
            'App\Models\Vehicle950A\FailureType',
            'ft_ig',
            'ig_id',
            'type_id'
        )->withPivot('sort', 'type');
    }

    public function sortedFailureTypes()
    {
        $sorted = $this->failureTypes->map(function($f) {
                return [
                    'id' => $f->id,
                    'label' => $f->label,
                    'name' => $f->name,
                    'type' => $f->pivot->type,
                    'sort' => $f->pivot->sort
                ];
            })
            ->toArray();

        foreach($sorted as $key => $row) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }

        if (count($sorted) > 0) {
            array_multisort($f_type_array, $f_sort_array, $f_label_array, $sorted);
        }

        return collect($sorted);
    }

    // public function families()
    // {
    //     return $this->hasMany(
    //         'App\Models\Client\InspectionFamily',
    //         'inspection_group_id',
    //         'id'
    //     );
    // }


    public function sortedModifications()
    {
        $sortedModifications = $this->inspection->modifications->map(function ($m) {
                return [
                    'id' => $m->id,
                    'label' => $m->label,
                    'name' => $m->name,
                    'type' => $m->pivot->type,
                    'sort' => $m->pivot->sort
                ];
            })
            ->toArray();

        foreach($sortedModifications as $key => $row) {
            // $m_type_array[$key] = $row['type'];
            $m_label_array[$key] = $row['label'];
            $m_sort_array[$key] = $row['sort'];
        }

        if (count($sortedModifications) > 0) {
            // array_multisort($m_type_array, $m_sort_array, $m_label_array, $sortedModifications);
            array_multisort($m_sort_array, $m_label_array, $sortedModifications);
        }

        return collect($sortedModifications);
    }

    public function sortedHoleModifications()
    {
        $sortedHoleModifications = $this->inspection->hModifications->map(function ($m) {
                return [
                    'id' => $m->id,
                    'label' => $m->label,
                    'name' => $m->name,
                    'type' => $m->pivot->type,
                    'sort' => $m->pivot->sort
                ];
            })
            ->toArray();

        foreach($sortedHoleModifications as $key => $row) {
            $hm_type_array[$key] = $row['type'];
            $hm_label_array[$key] = $row['label'];
            $hm_sort_array[$key] = $row['sort'];
        }

        if (count($sortedHoleModifications) > 0) {
            array_multisort($hm_type_array, $hm_sort_array, $hm_label_array, $sortedHoleModifications);
        }

        return collect($sortedHoleModifications);
    }

    public function findWithRelated($id)
    {
        $group = $this->with([
            'inspectors' => function($q) {
                $q->where('status', '=', 1)->select(['id', 'name', 'group_code']);
            },
            'inspection' => function($q) {
                $q->select(['id']);
            },
            'inspection.failures' => function($q) {
                $q->select(['id', 'name', 'label', 'status']);
            },
            'inspection.modifications' => function($q) {
                $q->select(['id', 'name', 'label']);
            },
            'inspection.hModifications' => function($q) {
                $q->select(['id', 'name', 'label']);
            },
            'pageTypes',
            'pageTypes.figure',
            'pageTypes.figure.holes' => function($q) {
                $q->select(['id', 'point', 'label', 'direction', 'color', 'border', 'shape', 'figure_id', 'part_type_id']);
            },
            'pageTypes.partTypes' => function($q) {
                $q->select(['id', 'pn', 'name', 'sort', 'vehicle_num']);
            }
        ])
        ->find($id);

        $failures = $group->inspection->failures
        ->filter(function ($f) {
            return $f->status === 1;
        })
        ->map(function ($f) {
            return [
                'id' => $f->id,
                'label' => $f->label,
                'name' => $f->name,
                'type' => $f->pivot->type,
                'sort' => $f->pivot->sort
            ];
        })
        ->toArray();

        foreach( $failures as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }

        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failures);

        $modifications = $group->inspection->modifications->map(function ($m) {
            return [
                'id' => $m->id,
                'label' => intval($m->label),
                'message' => $m->name,
                'type' => $m->pivot->type,
                'sort' => $m->pivot->sort
            ];
        })->toArray();

        foreach( $modifications as $key => $row ) {
            $m_label_array[$key] = $row['label'];
            $m_sort_array[$key] = $row['sort'];
        }

        if (count($modifications) !== 0 ) {
            // array_multisort($m_type_array, $m_sort_array, $m_label_array, $modifications);
            array_multisort($m_sort_array, $m_label_array, $modifications);
        }

        $hModifications = $group->inspection->hModifications->map(function ($hm) {
            return [
                'id' => $hm->id,
                'label' => $hm->label,
                'name' => $hm->name,
                'type' => $hm->pivot->type,
                'sort' => $hm->pivot->sort
            ];
        })->toArray();

        foreach( $hModifications as $key => $row ) {
            $hm_type_array[$key] = $row['type'];
            $hm_label_array[$key] = $row['label'];
            $hm_sort_array[$key] = $row['sort'];
        }

        if (count($hModifications) !== 0 ) {
            array_multisort($hm_type_array, $hm_sort_array, $hm_label_array, $hModifications);
        }

        return [
            'id' => $group->id,
            'inspectorGroups' => $group->inspectors->map(function ($i) {
                return [
                    'id' => $i->id,
                    'name' => $i->name,
                    'group' => $i->group_code,
                    'sort' => $i->pivot->sort
                ];
            })
            ->sortBy('sort')
            ->groupBy('group'),
            'failures' => $failures,
            'comments' => $modifications,
            'holeModifications' => $hModifications,
            'pages' => $group->pageTypes->map(function ($page) {
                return [
                    'id' => $page->id,
                    'parts' => $page->partTypes->map(function ($part) {
                        return [
                            'id' => $part->id,
                            'name' => $part->name,
                            'pn' => $part->pn,
                            'vehicle' => $part->vehicle_num,
                            'area' => $part->pivot->area
                        ];
                    }),
                    'figure' => [
                        'path' => 'img/figures/'.$page->figure->path,
                        'holes' => $page->figure->holes
                    ]
                ];
            })
        ];
    }
}
