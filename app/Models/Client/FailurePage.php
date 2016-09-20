<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FailurePage
 * @package App\Models
 */
class FailurePage extends Model
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

    public function comment()
    {
        return $this->belongsTo(
            'App\Models\Client\Comment',
            'comment_id',
            'id'
        );
    }
}
