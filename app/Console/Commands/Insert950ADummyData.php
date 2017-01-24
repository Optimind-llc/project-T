<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Vehicle950A\Client\InspectionController;
use Illuminate\Http\Request;
use App\Services\DummyRequest;
// Models
use App\Models\PartType;

class Insert950ADummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert950ADummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert 950A dummy data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getChoku($workers)
    {
        return array_rand($workers, 1);   
    }

    protected function getWorker($workers, $choku)
    {
        return $workers[$choku][array_rand($workers[$choku], 1)]['name'];
    }

    protected function createPart($panelId, $partTypes, $ft = [], $mt = [], $hmt = [])
    {
        $results = [];
        foreach ($partTypes as $partType) {
            $createFailures = function() use ($partType, $ft, $mt) {
                $margin = 10;

                $F = [];
                foreach ($partType['figures'] as $figure) {
                    for ($i=0; $i<4; $i++) {
                        array_push($F, [
                            'failureTypeId' => $ft[array_rand($ft)]['id'],
                            'figureId' => $figure['id'],
                            'x' => rand($margin, $figure['sizeX'] - $margin),
                            'y' => rand($margin, $figure['sizeY'] - $margin),
                            'modificationTypeId' => count($mt) === 0 ? null : $mt[array_rand($mt)]['id']
                        ]);
                    }
                }

                return $F;
            };


            $dummy_comments = ['コメントだよ', '', ''];

            $result = [
                'partTypeId' => $partType['id'],
                'panelId' => $panelId,
                'comment' => $dummy_comments[array_rand($dummy_comments, 1)],
                'status' => rand(0, 1),
                'failures' => count($ft) === 0 ? [] : $createFailures()
            ];

            array_push($results, $result);
        }

        return $results;



        // $createFailures = function($failures) use ($img) {
        //     $F = [];
        //     for ($i=0; $i<2; $i++) {
        //         $x = rand($img['margin'], $img['x'] - $img['margin']);
        //         $y = rand($img['margin'], $img['y'] - $img['margin']);

        //         array_push($F, [
        //             'id' => $failures[array_rand($failures)]['id'],
        //             'point' => $x . ',' . $y,
        //             'pointSub' => $x+$img['arrow'] . ',' . $y+$img['arrow']
        //         ]);
        //     }

        //     return $F;
        // };

        // $createHole = function($hole) {
        //     $holeStatus = rand(0, 2);
        //     $holeData = [
        //         'id' => $hole['id'],
        //         'status' => $holeStatus,
        //     ];

        //     if ($holeStatus == 2) {
        //         $holeData['holeModificationId'] = rand(1, 3);
        //     }
                            
        //     return $holeData;
        // };

        // $createComments = function($comments, $history) {
        //     $C = [];

        //     foreach (array_rand($history, 2) as $k) {
        //         array_push($C, [
        //             'failurePositionId' => $history[$k]['failurePositionId'],
        //             'commentId' => $comments[array_rand($comments)]['id'],
        //             'comment' => 'その他で追加できるコメント'
        //         ]);
        //     }

        //     return $C;
        // };

        // $createHoleModification = function($holeModifications, $hole_history) {
        //     $HM = [];

        //     foreach ($hole_history as $key => $hole) {
        //         if ($hole['status'] == 2) {
        //             array_push($HM, [
        //                 'holePageId' => $hole['holePageId'],
        //                 'holeModificationId' => $holeModifications[array_rand($holeModifications)]['id'],
        //                 'comment' => 'その他で追加できるコメント'
        //             ]);
        //         }
        //     }

        //     return $HM;
        // };

        // $createPage = function($page) use ($img, $id, $createPart, $createFailures, $createHole, $createComments, $createHoleModification, $group, $history, $hole_history) {
        //     return [
        //         'pageId' => $page['id'],
        //         'table' => 'A',
        //         'parts' => $page['parts']->map($createPart),
        //         'failures' => count($group['failures']) ? $createFailures($group['failures']) : null,
        //         'holes' => count($page['figure']['holes']) ? array_map($createHole, $page['figure']['holes']->toArray()) : null,
        //         'comments' => count($history) ? $createComments($group['comments'], $history) : null,
        //         'holeModifications' => count($hole_history) ? $createHoleModification($group['holeModifications'], $hole_history) : null
        //     ];
        // };

        // $tyokus = ['黄直', '白直', '黒直'];
        // $tyoku = $tyokus[array_rand($tyokus)];

        // return [
        //     'groupId' => $group['id'],
        //     'inspectorGroup' => $tyoku,
        //     'status' => 1,
        //     'comment' => ($group['id'] == 5 || $group['id'] == 6 || $group['id'] == 8) ? null : 'インナーのコメント',
        //     'inspector' => $tyoku.','.$group['inspectorGroups']['Y'][0]['name'],
        //     'pages' => $group['pages']->filter(function($p) {
        //         switch (rand(0, 6)) {
        //             case 0: $result = $p['id'] != 14; break;
        //             case 1: $result = ($p['id'] != 14 && $p['id'] != 13); break;
        //             case 2: $result = ($p['id'] != 14 && $p['id'] != 27); break;
        //             case 3: $result = ($p['id'] != 14 && $p['id'] != 28); break;
        //             case 4: $result = ($p['id'] != 14 && $p['id'] != 13 && $p['id'] != 27); break;
        //             case 5: $result = ($p['id'] != 14 && $p['id'] != 13 && $p['id'] != 28); break;
        //             case 6: $result = ($p['id'] != 14 && $p['id'] != 27 && $p['id'] != 28); break;
        //         }

        //         return $result;
        //     })
        //     ->map($createPage)
        //     ->toArray()
        // ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = new DummyRequest;
        $controller = new InspectionController;

        //成型_外観検査_ドア_ドアインナーR
        $process = 'molding';
        $inspection = 'gaikan';
        $request->setForGet($process, $inspection, ['ドアインナR']);
        $getted = $controller->getInspection($request);

        for ($i = 1; $i <= 1; $i++) {
            $panelId = 'X'.str_pad($i, 7, 0, STR_PAD_LEFT);
            $parts = $this->createPart($panelId, $getted['partTypes'], $getted['failures']);

            $choku = $this->getChoku($getted['workers']);
            $worker = $this->getWorker($getted['workers'], $choku);
            $line = 1;

            $request->setForSave($choku, $worker, $line, $parts);
            $controller->saveInspection($request);
        }

        $this->info('ok');
    }
}
