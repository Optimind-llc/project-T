<?php

namespace App\Models\Vehicle950A;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Choku
 * @package App\Models
 */
class Choku extends Model
{
    protected $connection = '950A';
    protected $primaryKey = 'code';
    protected $guarded = ['code'];
    public $incrementing = false;
}
