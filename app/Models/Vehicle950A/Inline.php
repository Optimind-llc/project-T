<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inline
 * @package App\Models
 */
class Inline extends Model
{
    protected $connection = '950A';
    protected $guarded = ['id'];

    public function type()
    {
        return $this->belongsTo(
            'App\Models\Vehicle950A\InlineType',
            'type_id',
            'id'
        );
    }
}
