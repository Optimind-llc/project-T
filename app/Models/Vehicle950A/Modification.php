<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Modification
 * @package App\Models
 */
class Modification extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function figure()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Figure',
            'figure_id',
            'id'
        );
    }

    public function failure()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Failure',
            'failure_id',
            'id'
        );
    }

    // public function inspections()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Inspection',
    //         'failure_inspection',
    //         'failure_id',
    //         'inspection_id'
    //     )->withPivot('type', 'sort');
    // }
}
