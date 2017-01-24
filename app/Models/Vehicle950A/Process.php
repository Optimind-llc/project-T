<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Process
 * @package App\Models
 */
class Process extends Model
{
    protected $connection = '950A';
    protected $primaryKey = 'en';
    public $incrementing = false;
    protected $guarded = ['en'];

    public function groups()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\InspectionGroup',
            'process_en',
            'en'
        );
    }

    // public function getInspectionsHasSamePanelID($division_en, $panel_id, $enable_inspection_list)
    // {
    //     return $this->inspections()
    //         ->whereIn('en', $enable_inspection_list)
    //         ->with([
    //             'groups' => function($query) use ($division_en) {
    //                 $query->where('division_en', $division_en);
    //             },
    //             'groups.families.pages' => function($query) use ($panel_id) {
    //                 $query->whereHas('parts', function($q) use ($panel_id) {
    //                     $q->where('panel_id', $panel_id);
    //                 })
    //                 ->get();
    //             },
    //             'groups.families.pages.failurePositions.failure'
    //         ])
    //         ->get();
    // }
}
