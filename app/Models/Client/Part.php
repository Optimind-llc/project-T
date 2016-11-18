<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Part
 * @package App\Models
 */
class Part extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\PartType',
            'part_type_id',
            'id'
        );
    }

    public function pages()
    {
        return $this->belongsToMany(
            'App\Models\Client\Page',
            'part_page',
            'part_id',
            'page_id'
        )->withPivot('status');
    }

    public function family()
    {
        return $this->belongsTo(
            'App\Models\Client\PartFamily',
            'family_id',
            'id'
        );
    }

    public function failurePositions()
    {
        return $this->hasMany(
            'App\Models\Client\FailurePosition',
            'part_id',
            'id'
        );
    }

    public function inlines()
    {
        return $this->belongsToMany(
            'App\Models\Inline',
            'inline_page',
            'part_id',
            'inline_id'
        )->withPivot('status');
    }

    public function detail()
    {
        $detail = $this->with([
            'partType',
            'pages' => function($q) use ($itionGId) {
                $q->join('inspection_families as if', function ($join) use ($itionGId) {
                    $join->on('pages.family_id', '=', 'if.id')
                        ->where('if.inspection_group_id', '=', $itionGId);
                })
                ->orderBy('if.inspected_at')
                ->select(['pages.*', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.status']);
            },
            'pages.failurePositions' => function ($q) use($part_id) {
                $q->where('part_id', '=', $part_id)
                    ->select(['id','page_id', 'part_id', 'failure_id']);
            },
            'pages.comments',
            'pages.comments.modification',
            'pages.comments.failurePosition',
            'pages.holePages' => function($q) use($partTypeId) {
                $q->join('holes', 'hole_page.hole_id', '=', 'holes.id')
                    ->where('holes.part_type_id', '=', $partTypeId)
                    ->select('hole_page.*')
                    ->get();
            },
            'pages.holePages.hole' => function($q) use($partTypeId) {
                $q->select(['id', 'label']);
            },
            'pages.holePages.holeModification' => function($q) {
                $q->select(['hole_modifications.id', 'name']);
            },
            'pages.inlines'
        ])
        ->find($part_id);
    }
}
