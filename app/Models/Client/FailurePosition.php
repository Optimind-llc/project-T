<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FailurePage
 * @package App\Models
 */
class FailurePosition extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function failure()
    {
        return $this->belongsTo(
            'App\Models\Failure',
            'failure_id',
            'id'
        );
    }

    public function page()
    {
        return $this->belongsTo(
            'App\Models\Client\Page',
            'page_id',
            'id'
        );
    }

    public function part()
    {
        return $this->belongsTo(
            'App\Models\Client\Part',
            'part_id',
            'id'
        );
    }
}
