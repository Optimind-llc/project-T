<?php

namespace App\Services;

use Carbon\Carbon;
// Models
use App\Models\Client\Part;
use App\Models\Client\Page;
use App\Models\Client\InspectionFamily;

class Result
{
    protected $partId;
    protected $partTypeId;
    protected $itionGId;
    protected $result;

    public function __construct($partId, $partTypeId, $itionGId)
    {
        $this->partId = $partId;
        $this->partTypeId = $partTypeId;
        $this->itionGId = $itionGId;
    }

    public function setDetails()
    {
        $partId = $this->partId;
        $partTypeId = $this->partTypeId;
        $itionGId = $this->itionGId;

        $part = Part::with([
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

        $this->result = $family;
        return $this;
    }

    public function get()
    {
        return $this->result;
    }
}