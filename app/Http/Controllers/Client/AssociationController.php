<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Models
use App\Models\PartType;
use App\Models\Client\Part;
use App\Models\Client\PartFamily;
// Exceptions
use Dingo\Api\Exception\StoreResourceFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AssociationController
 * @package App\Http\Controllers
 */
class AssociationController extends Controller
{
    /*
     * Get page types from vehicle_code, process_en, inspection_en, division_en, line
     */
    public function saveAssociation(Request $request)
    {
        $association = $request->association;
        if (!$association) {
            throw new StoreResourceFailedException('JSON in Request body should contain association key');
        }

        $association['67007'] = $association['67149'];

        $parts = [];

    	foreach ($association as $pn => $panel_id) {
            $part_type = PartType::where('pn', $pn)->first();
    		$newPart = $part_type->parts()
    			->where('panel_id', $panel_id)
    			->first();

	        if ($newPart instanceof Part) {
	        	$family = $newPart->family;

	        	if ($family instanceof PartFamily) {
	        		throw new StoreResourceFailedException('This part(pn = '.$pn.', panel_id = '.$panel_id.') already be associated others ');
	        	}
	        }
            else {
                $newPart = new Part;
                $newPart->panel_id = $panel_id;
                $newPart->part_type_id = $part_type->id;
                $newPart->save();
            }

            array_push($parts, $newPart);
    	}

        $newFamily = new PartFamily;
        $newFamily->associated_by = null;
        $newFamily->save();

        foreach ($parts as $part) {
            $newFamily->parts()->save($part);
        }

        return "ok";
    }
}
