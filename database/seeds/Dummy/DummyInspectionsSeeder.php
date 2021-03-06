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
    protected function createData($group, $id, $history, $hole_history)
    {
        /***** HARD CODE *****/
        $img = ['x' => 1740, 'y' => 800, 'margin' => 100, 'arrow' => 80];

        $createPart = function($part) use ($id) {
            $dummy_comments = ['小部品のコメントだよ', null];

            return [
                'partTypeId' => $part['id'],
                'panelId' => 'B'.str_pad($id, 7, 0, STR_PAD_LEFT),
                'status' => rand(0, 1),
                'comment' => $dummy_comments[array_rand($dummy_comments, 1)]
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

        $createHoleModification = function($holeModifications, $hole_history) {
            $HM = [];

            foreach ($hole_history as $key => $hole) {
                if ($hole['status'] == 2) {
                    array_push($HM, [
                        'holePageId' => $hole['holePageId'],
                        'holeModificationId' => $holeModifications[array_rand($holeModifications)]['id'],
                        'comment' => 'その他で追加できるコメント'
                    ]);
                }
            }

            return $HM;
        };

        $createPage = function($page) use ($img, $id, $createPart, $createFailures, $createHole, $createComments, $createHoleModification, $group, $history, $hole_history) {
            return [
                'pageId' => $page['id'],
                'table' => 'A',
                'parts' => $page['parts']->map($createPart),
                'failures' => count($group['failures']) ? $createFailures($group['failures']) : null,
                'holes' => count($page['figure']['holes']) ? array_map($createHole, $page['figure']['holes']->toArray()) : null,
                'comments' => count($history) ? $createComments($group['comments'], $history) : null,
                'holeModifications' => count($hole_history) ? $createHoleModification($group['holeModifications'], $hole_history) : null
            ];
        };

        $tyokus = ['黄直', '白直'];
        $tyoku = $tyokus[array_rand($tyokus)];

        return [
            'groupId' => $group['id'],
            'inspectorGroup' => $tyoku,
            'status' => 1,
            'comment' => ($group['id'] == 5 || $group['id'] == 6 || $group['id'] == 8) ? null : 'インナーのコメント',
            'inspector' => $tyoku.','.$group['inspectorGroups']['Y'][0]['name'],
            'pages' => $group['pages']->filter(function($p){
                    return $p['id'] != 14;
                    // return !($p['id'] == 14 || $p['id'] == 13);
                })
                ->map($createPage)
                ->toArray(),
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

        // //成型：検査：ライン１：インナー
        // $group = $controller->inspection(1);
        // for ($id = 1; $id <= 20; $id++) {
        //     $data = $this->createData($group['group'], $id ,[] ,[]);
        //     // var_dump(json_encode($data));
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //成型：検査：ライン２：インナー
        // $group = $controller->inspection(2);

        // for ($id = 21; $id <= 40; $id++) {
        //     $data = $this->createData($group['group'], $id ,[] ,[]);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //成型：検査：ライン１：アウター
        // $group = $controller->inspection(5);

        // for ($id = 1; $id <= 20; $id++) {
        //     $data = $this->createData($group['group'], $id ,[] ,[]);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //成型：検査：ライン２：アウター
        // $group = $controller->inspection(6);

        // for ($id = 21; $id <= 40; $id++) {
        //     $data = $this->createData($group['group'], $id ,[] ,[]);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        //穴あけ：外観検査：インナー
        $group = $controller->inspection(15);

        for ($id = 21; $id <= 30; $id++) {
            $data = $this->createData($group['group'], $id ,[] ,[]);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        //穴あけ：検査：インナー
        $group = $controller->inspection(4);

        for ($id = 21; $id <= 30; $id++) {
            $data = $this->createData($group['group'], $id ,[] ,[]);
            $request->setFamily($data);
            $controller->saveInspection($request);        
        }

        // //穴あけ：検査：アウター
        // $group = $controller->inspection(8);

        // for ($id = 1; $id <= 10; $id++) {
        //     $data = $this->createData($group['group'], $id ,[] ,[]);
        //     $request->setFamily($data);
        //     $controller->saveInspection($request);        
        // }

        // //接着：簡易CF：インナASSY
        // $group = $controller->inspection(16);

        // for ($id = 1; $id <= 10; $id++) {
        //     $data = $this->createData($group['group'], $id, [] ,[]);

        //     $request->setFamily($data);
        //     $controller->saveInspection($request);
        // }

        // //接着：止水：インナASSY
        // $group = $controller->inspection(10);

        // for ($id = 1; $id <= 8; $id++) {
        //     $request->set(7, 'B'.str_pad($id, 7, 0, STR_PAD_LEFT), [16]);
        //     $history = [];
        //     $groups = collect($controller->history($request)['group'])->map(function($g) {
        //         return $g['pages']->first()['failures']->toArray();
        //     });
        //     foreach ($groups as $g) {
        //         $history = array_merge($history, $g);
        //     }
            
        //     $data = $this->createData($group['group'], $id, $history ,[]);

        //     $request->setFamily($data);
        //     $controller->saveInspection($request);
        // }

        // // 接着：仕上：インナASSY
        // $group = $controller->inspection(11);

        // for ($id = 1; $id <= 6; $id++) {
        //     $request->set(7, 'B'.str_pad($id, 7, 0, STR_PAD_LEFT), [16, 10]);
        //     $history = [];
        //     $groups = collect($controller->history($request)['group'])->map(function($g) {
        //         return $g['pages']->first()['failures']->toArray();
        //     });
        //     foreach ($groups as $g) {
        //         $history = array_merge($history, $g);
        //     }

        //     $data = $this->createData($group['group'], $id, $history ,[]);

        //     $request->setFamily($data);
        //     $controller->saveInspection($request);
        // }

        // //接着：検査：インナASSY
        // $group = $controller->inspection(12);

        // for ($id = 1; $id <= 4; $id++) {
        //     $request->set(7, 'B'.str_pad($id, 7, 0, STR_PAD_LEFT), [16, 10, 11]);
        //     $history = [];
        //     $groups = collect($controller->history($request)['group'])->map(function($g) {
        //         return $g['pages']->first()['failures']->toArray();
        //     });
        //     foreach ($groups as $g) {
        //         $history = array_merge($history, $g);
        //     }

        //     $data = $this->createData($group['group'], $id, $history ,[]);

        //     $request->setFamily($data);
        //     $controller->saveInspection($request);
        // }

        // //接着：手直し：インナASSY
        // $group = $controller->inspection(14);

        // for ($id = 1; $id <= 2; $id++) {
        //     $request->set(7, 'B'.str_pad($id, 7, 0, STR_PAD_LEFT), [16, 10, 11, 12]);
        //     $history = [];
        //     $groups = collect($controller->history($request)['group'])->map(function($g) {
        //         return $g['pages']->first()['failures']->toArray();
        //     });
        //     foreach ($groups as $g) {
        //         $history = array_merge($history, $g);
        //     }

        //     $data = $this->createData($group['group'], $id, $history ,[]);

        //     $request->setFamily($data);
        //     $controller->saveInspection($request);
        // }
    }
}
