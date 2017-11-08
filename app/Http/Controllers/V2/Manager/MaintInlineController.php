<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\InlineType;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MaintInlineController
 * @package App\Http\Controllers\V2\Manager
 */
class MaintInlineController extends Controller
{
    public function get(Request $request)
    {
        $partTypePns = $request->partTypePns;

        $inlineTypes = InlineType::whereIn('pt_pn', $partTypePns)
            // ->with(['partType' => function($q) {
            //     $q->select(['pt_pn', 'name']);
            // }])
            ->get()
            ->map(function($h) {
                return [
                    'id' => $h->id,
                    'label' => $h->label,
                    'position' => $h->position,
                    'max' => $h->max,
                    'min' => $h->min,
                    'partName' => $h->partType->name
                ];
            })
            ->sortBy('partName')
            ->values();

        return [
            'data' => $inlineTypes,
            'message' => 'success'
        ];
    }

    public function update(Request $request)
    {
        $inline = InlineType::find($request->id);
        $inline->max = $request->max;
        $inline->min = $request->min;
        $inline->save();

        return ['message' => 'success'];
    }
}
