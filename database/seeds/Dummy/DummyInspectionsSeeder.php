<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Client\InspectionController;
use Illuminate\Http\Request;
use Database\Seeding\DummyRequest;

/**
 * Class DivisionTableSeeder
 */
class DummyInspectionsSeeder extends Seeder
{
    protected function createData($group, $id)
    {
        /***** HARD CODE *****/
        $img = ['x' => 1280, 'y' => 1024, 'margin' => 100, 'arrow' => 80];

        $createPart = function($part) use ($id) {
            return [
                'partTypeId' => $part['id'],
                'panelId' => 'A'.str_pad($id, 7, 0, STR_PAD_LEFT)
            ];
        };

        $createFailures = function($failures) use ($img) {
            $F = [];
            for ($i=0; $i<5; $i++) {
                $x = rand($img['margin'], $img['x'] - $img['margin']);
                $y = rand($img['margin'], $img['y'] - $img['margin']);

                array_push($F, [
                    'id' => $failures[array_rand($failures->toArray(), 1)]['id'],
                    'point' => $x . ',' . $y,
                    'pointSub' => $x + $img['arrow'] . ',' . $y + $img['arrow']
                ]);
            }

            return $F;
        };

        $createHole = function($hole) {
            return [
                'id' => $hole['id'],
                'status' => rand(0, 2)
            ];
        };

        $createPage = function($page) use ($img, $id, $createPart, $createFailures, $createHole, $group) {
            return [
                'pageId' => $page['id'],
                'status' => 1,
                'table' => 'A',
                'parts' => $page['parts']->map($createPart),
                'figureId' => $page['figure']['id'],
                'failures' => $createFailures($group['failures']),
                'holes' => $page['figure']['holes']->count() ? array_map($createHole, $page['figure']['holes']->toArray()) : null
            ];
        };

        return [
            'groupId' => $group['id'],
            'line' => 1,
            'inspectorGroup' => $group['inspectorGroups'][0]['name'],
            'inspector' => $group['inspectorGroups'][0]['name'].','.$group['inspectorGroups'][0]['inspectors'][0]['name'].','.$group['inspectorGroups'][0]['inspectors'][0]['code'],
            'pages' => $group['pages']->map($createPage),
            'photos' => [
                'example1.jpg',
                'example2.jpg',
                'example3.jpg',
                'example4.jpg'
            ]
        ];
    }

    public function run()
    {
        $request = new DummyRequest;
        $controller = new InspectionController;

        //成型：検査：インナ
        $request->set('molding', 'check', 'inner');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //成型：検査：小部品
        $request->set('molding', 'check', 'small');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //穴あけ：検査：インナ
        $request->set('holing', 'check', 'inner');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //穴あけ：検査：小部品
        $request->set('holing', 'check', 'small');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //接着：検査：インナASSY
        $request->set('jointing', 'water_stop', 'inner_assy');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }
    }
}
