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

    public function partTypes()
    {
        return $this->hasMany(
            'App\Models\PartType',
            'vehicle_id',
            'id'
        );
    }
}
