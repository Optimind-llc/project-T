<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InlineType
 * @package App\Models
 */
class InlineType extends Model
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
}
