<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModificationFailurePosition
 * @package App\Models
 */
class ModificationFailurePosition extends Model
{
    protected $table = 'modification_failure_position';

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
            'App\Models\Modification',
            'm_id',
            'id'
        );
    }

    public function failurePosition()
    {
        return $this->belongsTo(
            'App\Models\Client\FailurePosition',
            'fp_id',
            'id'
        );
    }
}
