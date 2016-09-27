<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package App\Models
 */
class Comment extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function inspections()
    {
        return $this->belongsTo(
            'App\Models\Inspection',
            'figure_id',
            'id'
        );
    }

    public function pages()
    {
        return $this->belongsToMany(
            'App\Models\Client\Page',
            'comment_failure_position',
            'comment_id',
            'page_id'
        );
    }
}
