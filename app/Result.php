<?php

namespace App;

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

    public function formatForClient()
    {
        $family = $this->result;

        $exc = explode(',', $family->created_by);
        $inspectedBy = count($exc) > 1 ? $exc[1] : $exc[0];

        if ($family->updated_by) {
            $exu = explode(',', $family->created_by);
            $inspectedBy = count($exu) > 1 ? $exu[1] : $exu[0];
        }

        $this->result = collect([
            'inspectionGroupId' => $family->inspection_group_id,
            'status' => $family->status,
            'comment' => '書き込まれたコメント 同じパーツを複数ページで検査してる時はここ',
            'choku' => $family->inspector_group,
            'inspectedBy' => $inspectedBy,
            'inspectedAt' => $family->updated_at->format('m月d日'),
            'pages' => $family->pages->map(function($page) {
                return [
                    'parts' => $page->parts->map(function($part) {
                        return [
                            'id' => $part->id,
                            'vehicle_num' => $part->partType->vehicle_num,
                            'pn' => $part->partType->pn,
                            'name' => $part->partType->name,
                            'panelId' => $part->panel_id,
                            'status' => $part->pivot->status,
                            'comment' => '書き込まれたコメント 同じページで複数パーツを検査してる時はここ',
                        ];
                    }),
                    'failures' => $page->failurePositions->map(function($fp) {
                        $cLabel = '';
                        if ($fp->modifications->count() !== 0) {
                            $cLabel = $fp->modifications->first()->modification->label;
                        }

                        return collect([
                            'failurePositionId' => $fp->id,
                            'label' => $fp->failure->label,
                            'point' => $fp->point,
                            'cLabel' => $cLabel
                        ]);
                    }),
                    'holes' => $page->holePages->map(function($hp) {
                        $mId = 0;
                        $mLabel = '';
                        if ($hp->holeModification->count() !== 0) {
                            $hm = $hp->holeModification->first();
                            $mId = $hm->id;
                            $mLabel = $hm->label;
                        }

                        return collect([
                            'holeId' => $hp->hole_id,
                            'status' => $hp->status,
                            'mId' => $mId,
                            'mLabel' => $mLabel
                        ]);
                    })
                ];
            })
        ]);

        return $this;
    }

    public function setDetails()
    {
        $partId = $this->partId;
        $partTypeId = $this->partTypeId;
        $itionGId = $this->itionGId;

        $pages = Page::whereHas('parts', function ($q) use ($partId) {
            $q->where('parts.id', '=', $partId);
        })
        ->get(['id'])
        ->map(function($page) {
            return $page->id;
        })
        ->toArray();

        $family = InspectionFamily::where('inspection_group_id', '=', $itionGId)
            ->whereHas('pages', function ($q) use ($pages) {
                $q->whereIn('id', $pages);
            })
            ->with([
                'pages',
                'pages.parts',
                'pages.parts.partType',
                'pages.failurePositions',
                'pages.failurePositions.failure' => function($q) {
                    $q->select(['id', 'label']);
                },
                'pages.failurePositions.modifications.modification' => function($q) {
                    $q->select(['id', 'name', 'label']);
                },
                'pages.comments',
                'pages.comments.modification',
                'pages.comments.failurePosition',
                'pages.holePages',
                'pages.holePages.hole' => function($q) use($partTypeId) {
                    $q->select(['id', 'label']);
                },
                'pages.holePages.holeModification' => function($q) {
                    $q->select(['hole_modifications.id', 'name', 'label']);
                }
            ])
            ->first();

        $this->result = $family;
        return $this;
    }

    public function get()
    {
        return $this->result;
    }
}
