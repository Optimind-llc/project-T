<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pdf
 * @package App\Models
 */
class Pdf extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $table = 'pdf_templates';

    public function pageType()
    {
        return $this->hasOne(
            'App\Models\PageType',
            'pdf_id',
            'id'
        );
    }
}
