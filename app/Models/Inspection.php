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

    public function comments()
    {
        return $this->hasMany(
            'App\Models\Comment',
            'inspection_id',
            'id'
        );
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
                ->with([
                    'pageTypes',
                    'inspectors' => function ($q) {
                        $q->orderBy('sort');
                    },
                    'pageTypes.partTypes',
                    'pageTypes.partTypes.vehicle',
                    'pageTypes.figure',
                    'pageTypes.figure.holes',
                    'inspection.process.failures',
                    'inspection.comments'
                ])
                ->first();
        }

        return $this->groups()
            ->where('division_en', $division_en)
            ->with([
                'pageTypes',
                'inspectors' => function ($q) {
                    $q->orderBy('sort');
                },
                'pageTypes.partTypes',
                'pageTypes.partTypes.vehicle',
                'pageTypes.figure',
                'pageTypes.figure.holes',
                'pageTypes.pdf',
                'inspection.process.failures',
                'inspection.comments'
            ])
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
