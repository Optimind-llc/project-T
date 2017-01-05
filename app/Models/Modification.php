<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Modification
 * @package App\Models
 */
class Modification extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function pages()
    {
        return $this->belongsToMany(
            'App\Models\Client\Page',
            'comment_failure_position',
            'comment_id',
            'page_id'
        );
    }

    public function inspections()
    {
        return $this->belongsToMany(
            'App\Models\Inspection',
            'modification_inspection',
            'modification_id',
            'inspection_id'
        )->withPivot('type', 'sort');
    }
}
