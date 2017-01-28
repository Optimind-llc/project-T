<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Part
 * @package App\Models
 */
class Part extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\PartType',
            'pn',
            'pn'
        );
    }

    public function result()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\InspectionResult',
            'id',
            'part_id'
        );
    }

    // public function pages()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Client\Page',
    //         'part_page',
    //         'part_id',
    //         'page_id'
    //     )->withPivot('status', 'comment');
    // }

    // public function family()
    // {
    //     return $this->belongsTo(
    //         'App\Models\Client\PartFamily',
    //         'family_id',
    //         'id'
    //     );
    // }

    // public function failurePositions()
    // {
    //     return $this->hasMany(
    //         'App\Models\Client\FailurePosition',
    //         'part_id',
    //         'id'
    //     );
    // }

    // public function inlines()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Inline',
    //         'inline_page',
    //         'part_id',
    //         'inline_id'
    //     )->withPivot('status', 'inspected_at');
    // }
}
