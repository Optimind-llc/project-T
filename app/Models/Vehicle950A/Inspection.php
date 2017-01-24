<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspection
 * @package App\Models
 */
class Inspection extends Model
{
    protected $connection = '950A';
    protected $primaryKey = 'en';
    public $incrementing = false;
    protected $guarded = ['en'];

    public function groups()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\InspectionGroup',
            'inspection_en',
            'en'
        );
    }
}
