<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PageType
 * @package App\Models
 */
class PageType extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Figure',
            'figure_id',
            'id'
        );
    }

    public function pdf()
    {
        return $this->belongsTo(
            'App\Models\Pdf',
            'pdf_id',
            'id'
        );
    }

    public function group()
    {
        return $this->belongsTo(
            'App\Models\InspectionGroup',
            'group_id',
            'id'
        );
    }

    public function partTypes()
    {
        return $this->belongsToMany(
            'App\Models\PartType',
            'part_type_page_type',
            'page_type_id',
            'part_type_id'
        )
        ->withPivot('area');
    }

    public function pages()
    {
        return $this->hasMany(
            'App\Models\Client\Page',
            'page_type_id',
            'id'
        );
    }

    public function pagesWithRelated($itorG_name, $start_at, $end_at, $panel_id)
    {
        $pages = $this->pages()
            ->join('inspection_families as f', function ($join) use ($itorG_name) {
                $join->on('pages.family_id', '=', 'f.id')
                    ->whereIn('f.inspector_group', $itorG_name);
            })
            ->select('pages.*', 'f.inspector_group')
            ->join('part_page as pp', function ($join) use ($itorG_name) {
                $join->on('pp.page_id', '=', 'pages.id');
            })
            ->join('parts', function ($join) use ($itorG_name, $panel_id) {
                $aaa = $join->on('pp.part_id', '=', 'parts.id');
                if ($panel_id) {
                    $aaa->where('parts.panel_id', '=', $panel_id);
                }
            })
            ->groupBy('pages.id')
            ->with([
                'failurePositions' => function ($q) {
                    $q->select(['id', 'point', 'type', 'page_id', 'part_id', 'failure_id']);
                },
                'failurePositions.failure' => function ($q) {
                    $q->select(['id', 'name', 'label']);
                },
                'failurePositions.part' => function ($q) {
                    $q->select(['id', 'panel_id', 'part_type_id']);
                },
                'failurePositions.part.partType' => function ($q) {
                    $q->select(['id', 'name', 'pn']);
                },
                'comments',
                'comments.modification',
                'comments.failurePosition'
            ]);

        if ($start_at) {
            $pages->where('pages.created_at', '>=', $start_at)
                ->where('pages.created_at', '<=', $end_at);
        }
        
        return $pages->get();
    }
}
