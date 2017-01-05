<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class InspectionFamily
 * @package App\Models
 */
class InspectionFamily extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $dates = ['inspected_at', 'deleted_at'];
    
    public function groups()
    {
        return $this->belongsTo(
            'App\Models\InspectionGroup',
            'inspection_group_id',
            'id'
        );
    }

    public function pages()
    {
        return $this->hasMany(
            'App\Models\Client\Page',
            'family_id',
            'id'
        );
    }

    // public function photos()
    // {
    //     return $this->hasMany(
    //         'App\Models\Hole',
    //         'figure_id',
    //         'id'
    //     );
    // }
}
