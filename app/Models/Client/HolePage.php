<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HolePage
 * @package App\Models\Clien
 */
class HolePage extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $table = 'hole_page';

    public function holeModification()
    {
        return $this->belongsToMany(
            'App\Models\HoleModification',
            'hole_page_hole_modification',
            'hp_id',
            'hm_id'
        );
    }

    public function hole()
    {
        return $this->belongsTo(
            'App\Models\Hole',
            'hole_id',
            'id'
        );
    }
}