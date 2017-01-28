<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Figure
 * @package App\Models
 */
class Figure extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\PartType',
            'pt_pn',
            'pn'
        );
    }

    // public function holes()
    // {
    //     return $this->hasMany(
    //         'App\Models\Hole',
    //         'figure_id',
    //         'id'
    //     );
    // }
}
