<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InspectionGroup
 * @package App\Models
 */
class InspectionGroup extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function inspectors()
    {
        return $this->belongsToMany(
            'App\Models\Inspector',
            'inspector_inspection_group',
            'inspection_g_id',
            'inspector_id'
        )->withPivot('sort');
    }

    public function inspection()
    {
        return $this->belongsTo(
            'App\Models\Inspection',
            'inspection_id',
            'id'
        );
    }

    public function division()
    {
        return $this->belongsTo(
            'App\Models\Division',
            'division_en',
            'en'
        );
    }

    public function pageTypes()
    {
        return $this->hasMany(
            'App\Models\PageType',
            'group_id',
            'id'
        );
    }

    public function families()
    {
        return $this->hasMany(
            'App\Models\Client\InspectionFamily',
            'inspection_group_id',
            'id'
        );
    }

    public function findWithRelated($id)
    {
        $group = $this->with([
            'inspectors' => function($q) {
                $q->select(['id', 'name', 'group_code']);
            },
            'inspection' => function($q) {
                $q->select(['id']);
            },
            'inspection.failures' => function($q) {
                $q->select(['id', 'name', 'label']);
            },
            'inspection.modifications' => function($q) {
                $q->select(['id', 'name', 'label']);
            },
            'pageTypes',
            'pageTypes.figure',
            'pageTypes.figure.holes' => function($q) {
                $q->select(['id', 'point', 'label', 'direction', 'color', 'border', 'shape', 'figure_id']);
            },
            'pageTypes.partTypes' => function($q) {
                $q->select(['id', 'pn', 'name', 'sort', 'vehicle_num']);
            }
        ])
        ->find($id);

        $failures = $group->inspection->failures->map(function ($f) {
            return [
                'id' => $f->id,
                'label' => $f->label,
                'name' => $f->name,
                'type' => $f->pivot->type,
                'sort' => $f->pivot->sort
            ];
        })->toArray();

        foreach( $failures as $key => $row ) {
            $f_type_array[$key] = $row['type'];
            $f_label_array[$key] = $row['label'];
            $f_sort_array[$key] = $row['sort'];
        }

        array_multisort($f_type_array, $f_sort_array, $f_label_array, $failures);

        $modifications = $group->inspection->modifications->map(function ($m) {
            return [
                'id' => $m->id,
                'label' => $m->label,
                'message' => $m->name,
                'type' => $m->pivot->type,
                'sort' => $m->pivot->sort
            ];
        })->toArray();

        foreach( $modifications as $key => $row ) {
            $m_type_array[$key] = $row['type'];
            $m_label_array[$key] = $row['label'];
            $m_sort_array[$key] = $row['sort'];
        }

        if (count($modifications) !== 0 ) {
            array_multisort($m_type_array, $m_sort_array, $m_label_array, $modifications);
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
