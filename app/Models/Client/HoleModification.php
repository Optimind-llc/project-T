<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HoleModification
 * @package App\Models\Clien
 */
class HoleModification extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $table = 'hole_modifications';

    // public function parts()
    // {
    //     return $this->hasMany(
    //         'App\Models\Client\Part',
    //         'hole_page'
    //         'family_id',
    //         'id'
    //     );
    // }
}