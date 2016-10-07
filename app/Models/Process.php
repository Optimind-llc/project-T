<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Process
 * @package App\Models
 */
class Process extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function inspections()
    {
        return $this->hasMany(
            'App\Models\Inspection',
            'process_id',
            'id'
        );
    }

    public function failures()
    {
        return $this->belongsToMany(
            'App\Models\Failure',
            'failure_process',
            'process_id',
            'failure_id'
        )
        ->withPivot('type');
    }

    public function getInspectionsHasSamePanelID($division_en, $panel_id, $enable_inspection_list)
    {
        return $this->inspections()
            ->whereIn('en', $enable_inspection_list)
            ->with([
                'groups' => function($query) use ($division_en) {
                    $query->where('division_en', $division_en);
                },
                'groups.families.pages' => function($query) use ($panel_id) {
                    $query->whereHas('parts', function($q) use ($panel_id) {
                        $q->where('panel_id', $panel_id);
                    })
                    ->get();
                },
                'groups.families.pages.failurePositions.failure'
            ])
            ->get();
    }
}
