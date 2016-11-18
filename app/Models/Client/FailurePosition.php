<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FailurePage
 * @package App\Models
 */
class FailurePosition extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

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

    public function modifications()
    {
        return $this->hasMany(
            'App\Models\Client\ModificationFailurePosition',
            'fp_id',
            'id'
        );
    }
}
