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

    	// $association = [
    	// 	[
    	// 		'pn' => '67149',
    	// 		'panelId' => 'B0000011'
    	// 	],[
    	// 		'pn' => '67119',
    	// 		'panelId' => 'B0000011'
    	// 	]
    	// ];

        $parts = [];

    	foreach ($association as $part) {
            $part_type = PartType::where('pn', $part['pn'])->first();
    		$newPart = $part_type->parts()
    			->where('panel_id', $part['panelId'])
    			->first();

	        if ($newPart instanceof Part) {
	        	$family = $newPart->family;

	        	if ($family instanceof PartFamily) {
	        		throw new StoreResourceFailedException('This part already be associated others');
	        	}
	        }
            else {
                $newPart = new Part;
                $newPart->panel_id = $part['panelId'];
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
