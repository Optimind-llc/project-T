<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Part
 * @package App\Models
 */
class PartFamily extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function parts()
    {
        return $this->hasMany(
            'App\Models\Client\Part',
            'family_id',
            'id'
        );
    }
}
