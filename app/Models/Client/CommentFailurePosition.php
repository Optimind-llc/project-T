<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentFailurePosition
 * @package App\Models
 */
class CommentFailurePosition extends Model
{
    protected $table = 'comment_failure_position';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function page()
    {
        return $this->belongsTo(
            'App\Models\Client\Part',
            'page_id',
            'id'
        );
    }

    public function comment()
    {
        return $this->belongsTo(
            'App\Models\Comment',
            'comment_id',
            'id'
        );
    }

    public function failurePosition()
    {
        return $this->belongsTo(
            'App\Models\Client\FailurePosition',
            'failure_position_id',
            'id'
        );
    }
}
