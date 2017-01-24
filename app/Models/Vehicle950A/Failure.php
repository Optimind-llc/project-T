<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class Failure extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

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
