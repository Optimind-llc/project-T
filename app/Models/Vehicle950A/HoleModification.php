<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HoleModification
 * @package App\Models
 */
class HoleModification extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function hole()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\Hole',
            'hole_id',
            'id'
        );
    }

    public function type()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\HoleModificationType',
            'type_id',
            'id'
        );
    }
}
