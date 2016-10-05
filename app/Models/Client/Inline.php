<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inline
 * @package App\Models
 */
class Inline extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\PartType',
            'part_type_id',
            'id'
        );
    }
}
