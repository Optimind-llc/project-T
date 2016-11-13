<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Part
 * @package App\Models
 */
class Part extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function partType()
    {
        return $this->belongsTo(
            'App\Models\PartType',
            'part_type_id',
            'id'
        );
    }

    public function pages()
    {
        return $this->belongsToMany(
            'App\Models\Client\Page',
            'part_page',
            'part_id',
            'page_id'
        )->withPivot('status');
    }

    public function family()
    {
        return $this->belongsTo(
            'App\Models\Client\PartFamily',
            'family_id',
            'id'
        );
    }

    public function failurePositions()
    {
        return $this->hasMany(
            'App\Models\Client\FailurePosition',
            'part_id',
            'id'
        );
    }

    public function inlines()
    {
        return $this->belongsToMany(
            'App\Models\Inline',
            'inline_page',
            'part_id',
            'inline_id'
        )->withPivot('status');
    }
}
