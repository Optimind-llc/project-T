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
        );
    }

    public function family()
    {
        return $this->belongsTo(
            'App\Models\Client\PartFamily',
            'family_id',
            'id'
        );
    }
}
