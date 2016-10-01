<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 * @package App\Models
 */
class Vehicle extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $primaryKey = 'number';
    public $incrementing = false;

    public function partTypes()
    {
        return $this->hasMany(
            'App\Models\PartType',
            'vehicle_num',
            'number'
        );
    }
}
