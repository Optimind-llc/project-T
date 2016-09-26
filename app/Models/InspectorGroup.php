<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InspectorGroup
 * @package App\Models
 */
class InspectorGroup extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function inspectors()
    {
        return $this->hasMany(
            'App\Models\Inspector',
            'group_id',
            'id'
        );
    }

    public function findInspectorsByProcessEn($process_en) {
        return $this->with([
                'inspectors' => function ($q) use ($process_en) {
                    $q->whereHas('processes', function ($q) use ($process_en) {
                        $q->where('en', $process_en);
                    })
                    ->get();
                }
            ])
            ->where('id', '>', 0)
            ->get()
            ->map(function ($group, $key) {
                return [
                    'name' => $group->name,
                    'code' => $group->code,
                    'inspectors' => $group['inspectors']->map(function ($inspector, $key) {
                        return [
                            'name' => $inspector->name,
                            'code' => $inspector->code
                        ];
                    })
                ];
            });
    }
}
