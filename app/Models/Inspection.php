<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inspection
 * @package App\Models
 */
class Inspection extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function groups()
    {
        return $this->hasMany(
            'App\Models\InspectionGroup',
            'inspection_id',
            'id'
        );
    }

    public function process()
    {
        return $this->belongsTo(
            'App\Models\Process',
            'process_id',
            'id'
        );
    }

    public function failures()
    {
        return $this->belongsToMany(
            'App\Models\Failure',
            'failure_inspection',
            'inspection_id',
            'failure_id'
        )
        ->withPivot(['type', 'sort']);
    }

    public function modifications()
    {
        return $this->belongsToMany(
            'App\Models\Modification',
            'modification_inspection',
            'inspection_id',
            'modification_id'
        )
        ->withPivot(['type', 'sort']);
    }

    /**
     * lineが指定された時はlineでフィルタリング
     * lineが指定されなかった時は人した最初のものを返す
     */
    public function getByDivisionWithRelated($division_en, $line)
    {
        if ($line) {
            return $this->groups()
                ->where('division_en', $division_en)
                ->where('line', $line)
                ->first();
        }

        return $this->groups()
            ->where('division_en', $division_en)
            ->first();
    }

    /**
     * lineが指定された時はlineでフィルタリング
     * lineが指定されなかった時は人した最初のものを返す
     */
    public function getGroupByVehicleDivisionLine($vehicle, $division_en, $line = null)
    {
        if ($line) {
            return $this->groups()
                ->where('vehicle_num', $vehicle)
                ->where('division_en', $division_en)
                ->where('line', $line)
                ->select(['id', 'vehicle_num', 'inspection_id', 'division_en', 'line'])
                ->first();
        }

        return $this->groups()
            ->where('vehicle_num', $vehicle)
            ->where('division_en', $division_en)
            ->select(['id', 'vehicle_num', 'inspection_id', 'division_en', 'line'])
            ->first();
    }
}
