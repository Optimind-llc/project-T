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
    protected $guarded = ['id'];

    public function figures()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Figure',
            'pt_id',
            'id'
        );
    }

    // public function parts()
    // {
    //     return $this->hasMany(
    //         'App\Models\Client\Part',
    //         'part_type_id',
    //         'id'
    //     );
    // }

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
