<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InspectionResult
 * @package App\Models
 */
class InspectionResult extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];
    protected $dates = ['inspected_at', 'exported_at'];

    public function process()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Process',
            'process',
            'en'
        );
    }

    public function inspection()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Inspection',
            'inspection',
            'en'
        );
    }

    public function part()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Part',
            'part_id',
            'id'
        );
    }

    public function failures()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Failure',
            'ir_id',
            'id'
        );
    }

    public function modifications()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Modification',
            'ir_id',
            'id'
        );
    }

    public function holes()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Hole',
            'ir_id',
            'id'
        );
    }

    public function inlines()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Inline',
            'ir_id',
            'id'
        );
    }

    public function scopeIdentify($query, $p, $i, $partId)
    {
        return $query->where('process', '=', $p)
            ->where('inspection', '=', $i)
            ->where('part_id', '=', $partId)
            ->where('latest', '=', 1);
    }

    // public function groups()
    // {
    //     return $this->belongsTo(
    //         'App\Models\InspectionGroup',
    //         'inspection_group_id',
    //         'id'
    //     );
    // }

    // public function pages()
    // {
    //     return $this->hasMany(
    //         'App\Models\Client\Page',
    //         'family_id',
    //         'id'
    //     );
    // }

    // public function photos()
    // {
    //     return $this->hasMany(
    //         'App\Models\Hole',
    //         'figure_id',
    //         'id'
    //     );
    // }
}
