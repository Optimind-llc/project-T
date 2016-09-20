<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspector
 * @package App\Models
 */
class Inspector extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function groups()
    {
        return $this->belongsTo(
            'App\Models\InspectorGroup',
            'group_id',
            'id'
        );
    }

    public function processes()
    {
        return $this->belongsToMany(
            'App\Models\Process',
            'inspector_process',
            'inspector_id',
            'process_id'
        );
    }
}
