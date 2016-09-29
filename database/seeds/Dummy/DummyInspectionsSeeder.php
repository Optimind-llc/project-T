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
                    'id' => $failures->random()['id'],
                    'point' => $x . ',' . $y,
                    'pointSub' => $x+$img['arrow'] . ',' . $y+$img['arrow']
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

        $createComments = function($comments, $history) {
            $C = [];

            foreach (array_rand($history, 10) as $k) {
                array_push($C, [
                    'failurePositionId' => $history[$k]['failurePositionId'],
                    'commentId' => $comments->random()['id'],
                ]);
            }

            return $C;
        };

        $createPage = function($page) use ($img, $id, $createPart, $createFailures, $createHole, $createComments, $group) {
            return [
                'pageId' => $page['id'],
                'status' => 1,
                'table' => 'A',
                'parts' => $page['parts']->map($createPart),
                'failures' => $group['failures']->count() ? $createFailures($group['failures']) : null,
                'holes' => $page['figure']['holes']->count() ? array_map($createHole, $page['figure']['holes']->toArray()) : null,
                'comments' => $group['comments']->count() ? $createComments($group['comments'], $page['history']) : null
            ];
        };

        return [
            'groupId' => $group['id'],
            'line' => 1,
            'inspectorGroup' => '黄直',
            'inspector' => '黄直,'.$group['inspectorGroups']['Y'][0]['name'].','.$group['inspectorGroups']['Y'][0]['code'],
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

        //接着：止水：インナASSY
        $request->set('jointing', 'water_stop', 'inner_assy');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //接着：仕上：インナASSY
        $request->set('jointing', 'finish', 'inner_assy');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //接着：点検：インナASSY
        $request->set('jointing', 'check', 'inner_assy');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //接着：特検：インナASSY
        $request->set('jointing', 'special_check', 'inner_assy');
        $group = $controller->inspection($request);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //接着：手直し：インナASSY
        for ($id = 1; $id <= 10; $id++) {
            $request->set('jointing', 'adjust', 'inner_assy', 'A'.str_pad($id, 7, 0, STR_PAD_LEFT));
            $group = $controller->inspection($request);

            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);
        }
    }
}
