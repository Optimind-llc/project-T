<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartType
 * @package App\Models
 */
class PartType extends Model
{
    protected $connection = '950A';
    protected $guarded = ['pn'];
    public $incrementing = false;

    public function figures()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Figure',
            'pt_pn',
            'pn'
        );
    }

    public function parts()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Part',
            'type_id',
            'id'
        );
    }

    // public function holes()
    // {
    //     return $this->hasMany(
    //         'App\Models\Hole',
    //         'part_type_id',
    //         'id'
    //     );
    // }

    // public function inlines()
    // {
    //     return $this->hasMany(
    //         'App\Models\Inline',
    //         'part_type_id',
    //         'id'
    //     );
    // }
}
