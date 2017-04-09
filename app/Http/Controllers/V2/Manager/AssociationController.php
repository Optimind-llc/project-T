<?php

namespace App\Http\Controllers\V2\Manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\Vehicle950A\PartFamily;
// Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AssociationController
 * @package App\Http\Controllers\Press\Manager
 */
class AssociationController extends Controller
{
    public function getFamilyByDate($vehicle, Request $request)
    {
        $type = $request->type;
        $start = Carbon::createFromFormat('Y-m-d-H i:s', $request->start.' 00:00');
        $end = Carbon::createFromFormat('Y-m-d-H i:s', $request->end.' 00:00')->addHours(1);

        $partFamilis = PartFamily::with('parts')
            ->where('part_families.updated_at', '>=', $start)
            ->where('part_families.updated_at', '<', $end)
            ->where('part_families.type', '=', $type)
            ->orderBy('part_families.updated_at')
            ->get()
            ->map(function($pf) {
                return [
                    'id' => $pf->id,
                    'type' => $pf->type,
                    'updatedAt' => $pf->updated_at->toDateTimeString(),
                    'parts' => $pf->parts->map(function($p) {
                        return [
                            'id' => $p->id,
                            'pn' => $p->pn,
                            'panelId' => $p->panel_id
                        ];
                    })
                ];
            })
            ->groupBy('type');

        return [
            'data' => $partFamilis
        ];
    }
}
