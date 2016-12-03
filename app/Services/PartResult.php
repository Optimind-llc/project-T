<?php

namespace App\Services;

use Carbon\Carbon;
// Models
use App\Models\Client\Part;
use App\Models\Client\Page;
use App\Models\Client\InspectionFamily;

class PartResult
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

    public function formatForRerefence()
    {
        $part = $this->result;

        if ($part->pages->count() > 0) {
            $merged_page = $part->pages->reduce(function($carry, $page) {
                $carry->put('tyoku', $page->inspector_group);
                $createdBy = explode(',', $page->created_by);
                $carry->put('createdBy', array_key_exists(1, $createdBy) ? $createdBy[1] : $createdBy[0]);
                $carry->put('updatedBy', $page->updated_by ? explode(',', $page->updated_by)[1] : '');
                $carry->put('failures', $page->failurePositions->merge($carry->has('failures') ? $carry['failures'] : []));
                $carry->put('modifications', $page->comments->merge($carry->has('modifications') ? $carry['modifications'] : []));
                $carry->put('hModifications', $page->hModifications->map(function($p) {
                    return $p->pivot;
                })->merge($carry->has('hModifications') ? $carry['hModifications'] : []));
                $carry->put('holes', $page->holePages->merge($carry->has('holes') ? $carry['holes'] : []));
                $carry->put('inlines', $page->inlines);
                $carry->put('createdAt', $page->created_at->format('Y-m-d H:i:s'));
                $carry->put('updatedAt', $page->updated_at->format('Y-m-d H:i:s'));
                $carry->put('inspectedAt', $page->inspected_at);
                $carry->put('status', $page->pivot->status);
                if (!is_null($page->comment)) {
                    $carry->put('comment', $page->comment);
                }
                if (!is_null($page->pivot->comment)) {
                    $carry->put('comment', $page->pivot->comment);
                }

                return $carry;
            }, collect([]));
        }
        else {
            $this->result = null;
            return $this;
        }

        $this->result = collect([
            'vehicle' => $part->partType->vehicle_num,
            'pn' => $part->partType->pn,
            'name' => $part->partType->name,
            'panelId' => $part->panel_id,
            'tyoku' => $merged_page['tyoku'],
            'createdBy' => $merged_page['createdBy'],
            'updatedBy' => $merged_page['updatedBy'],
            'createdAt' => $merged_page['createdAt'],
            'updatedAt' => $merged_page['updatedAt'],
            'inspectedAt' => $merged_page['inspectedAt'],
            'status' => $merged_page['status'],
            'comment' => $merged_page->has('comment') ? $merged_page['comment'] : null,
            'failures' => array_count_values($merged_page['failures']->map(function($f) {
                return $f->failure_id;
            })
            ->toArray()),
            'modifications' => array_count_values($merged_page['modifications']->map(function($m) {
                return $m->modification->id;
            })
            ->toArray()),
            'holes' => $merged_page['holes']->map(function($h) {
                $m = null;
                if ($h->holeModification->count() != 0) {
                    $m['id'] = $h->holeModification->first()->id;
                    $m['name'] = $h->holeModification->first()->name;
                };

                return [
                    'id' => $h->hole->id,
                    'status' => $h->status,
                    'm' => $m,
                ];
            })
            ->toArray(),
            'hModifications' => array_count_values($merged_page['hModifications']->map(function($hm) {
                return $hm->hm_id;
            })
            ->toArray()),
            'inlines' => $merged_page['inlines']->map(function($i) {
                return collect([
                    'id' => $i->id,
                    'sort' => $i->sort,
                    'max' => $i->max_tolerance,
                    'min' => $i->min_tolerance,
                    'status' => $i->pivot->status
                ]);
            })->keyBy('id')
        ]);

        return $this;
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
                ->select(['pages.*', 'if.inspection_group_id', 'if.inspector_group', 'if.created_by', 'if.updated_by', 'if.created_at', 'if.updated_at', 'if.inspected_at', 'if.status', 'if.comment']);
            },
            'pages.failurePositions' => function ($q) use($partId) {
                $q->where('part_id', '=', $partId)
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
            'pages.hModifications',
            'pages.inlines'
        ])
        ->find($partId);

        $this->result = $part;
        return $this;
    }

    public function get()
    {
        return $this->result;
    }
}