<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Failure
 * @package App\Models
 */
class Failure extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function processes()
    {
        return $this->belongsToMany(
            'App\Models\Process',
            'failure_process',
            'failure_id',
            'process_id'
        );
    }
}
