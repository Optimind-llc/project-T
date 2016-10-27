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
        $img = ['x' => 1740, 'y' => 800, 'margin' => 100, 'arrow' => 80];

        $createPart = function($part) use ($id) {
            return [
                'partTypeId' => $part['id'],
                'panelId' => 'B'.str_pad($id, 7, 0, STR_PAD_LEFT),
                'status' => rand(0, 1)
            ];
        };

        $createFailures = function($failures) use ($img) {
            $F = [];
            for ($i=0; $i<10; $i++) {
                $x = rand($img['margin'], $img['x'] - $img['margin']);
                $y = rand($img['margin'], $img['y'] - $img['margin']);

                array_push($F, [
                    'id' => $failures[array_rand($failures)]['id'],
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

            foreach (array_rand($history, 2) as $k) {
                array_push($C, [
                    'failurePositionId' => $history[$k]['failurePositionId'],
                    'commentId' => $comments[array_rand($comments)]['id'],
                    'comment' => 'その他で追加できるコメント'
                ]);
            }

            return $C;
        };

        $createPage = function($page) use ($img, $id, $createPart, $createFailures, $createHole, $createComments, $group) {
            return [
                'pageId' => $page['id'],
                'table' => 'A',
                'parts' => $page['parts']->map($createPart),
                'failures' => count($group['failures']) ? $createFailures($group['failures']) : null,
                'holes' => count($page['figure']['holes']) ? array_map($createHole, $page['figure']['holes']->toArray()) : null,
                'comments' => count($group['comments']) ? $createComments($group['comments'], $page['history']) : null
            ];
        };

        $tyokus = ['黄直', '白直'];
        $tyoku = $tyokus[array_rand($tyokus)];

        return [
            'groupId' => $group['id'],
            'inspectorGroup' => $tyoku,
            'status' => 1,
            'inspector' => $tyoku.','.$group['inspectorGroups']['Y'][0]['name'],
            'pages' => $group['pages']->map($createPage)->toArray(),
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

        //成型：検査：ライン１：インナー
        $group = $controller->inspection(1);
        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            // var_dump(json_encode($data));
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //成型：検査：ライン２：インナー
        $group = $controller->inspection(2);

        for ($id = 11; $id <= 20; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //成型：検査：ライン１：アウター
        $group = $controller->inspection(5);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //成型：検査：ライン２：アウター
        $group = $controller->inspection(6);

        for ($id = 11; $id <= 20; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //穴あけ：検査：インナー
        $group = $controller->inspection(4);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //穴あけ：検査：アウター
        $group = $controller->inspection(8);

        for ($id = 1; $id <= 10; $id++) {
            $data = $this->createData($group['group'], $id);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        // //接着：止水：インナASSY
        // $group = $controller->inspection(10);

        // for ($id = 1; $id <= 10; $id++) {
        //     $data = $this->createData($group['group'], $id);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //接着：仕上：インナASSY
        // for ($id = 1; $id <= 10; $id++) {
        //     $request->set('jointing', 'finish', 'inner_assy', '', 'B'.str_pad($id, 7, 0, STR_PAD_LEFT));
        //     $group = $controller->inspection($request);

        //     $data = $this->createData($group['group'], $id);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //接着：検査：インナASSY
        // $group = $controller->inspection(12);

        // for ($id = 1; $id <= 10; $id++) {
        //     $data = $this->createData($group['group'], $id);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //接着：特検：インナASSY
        // $group = $controller->inspection(13);

        // for ($id = 1; $id <= 10; $id++) {
        //     $data = $this->createData($group['group'], $id);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //接着：手直し：インナASSY
        // for ($id = 1; $id <= 10; $id++) {
        //     $request->set('jointing', 'adjust', 'inner_assy', '', 'B'.str_pad($id, 7, 0, STR_PAD_LEFT));
        //     $group = $controller->inspection($request);

        //     $data = $this->createData($group['group'], $id);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);
        // }
    }
}
