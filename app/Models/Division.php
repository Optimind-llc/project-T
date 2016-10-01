<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Division
 * @package App\Models
 */
class Division extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'en';
    public $incrementing = false;

    public function inspectionGroups()
    {
        return $this->hasMany(
            'App\Models\InspectionGroup',
            'division_en',
            'en'
        );
    }
}
