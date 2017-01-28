<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Part
 * @package App\Models
 */
class PartFamily extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function parts()
    {
        return $this->hasMany(
            'App\Models\Vehicle950A\Part',
            'family_id',
            'id'
        );
    }
}
