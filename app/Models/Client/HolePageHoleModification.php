<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HolePageHoleModification
 * @package App\Models
 */
class HolePageHoleModification extends Model
{
    protected $table = 'hole_page_hole_modification';
    protected $guarded = ['id'];

    public function holePage()
    {
        return $this->belongsTo(
            'App\Models\Client\HolePage',
            'hp_id',
            'id'
        );
    }
}
