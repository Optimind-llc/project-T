<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\V2\Client\InspectionController;
use Illuminate\Http\Request;
use App\Services\DummyRequest;
// Repositories
use App\Repositories\WorkerRepository;
use App\Repositories\FailureTypeRepository;
use App\Repositories\ModificationTypeRepository;
use App\Repositories\HoleModificationTypeRepository;
use App\Repositories\InspectionResultRepository;

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
    protected $worker;
    protected $failureType;
    protected $modificationType;
    protected $holeModificationType;
    protected $inspectionResult;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct (
        WorkerRepository $worker,
        FailureTypeRepository $failureType,
        ModificationTypeRepository $modificationType,
        HoleModificationTypeRepository $holeModificationType,
        InspectionResultRepository $inspectionResult
    )
    {
        parent::__construct();

        $this->worker = $worker;
        $this->failureType = $failureType;
        $this->modificationType = $modificationType;
        $this->holeModificationType = $holeModificationType;
        $this->inspectionResult = $inspectionResult;
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
                'pn' => $partType['pn'],
                'panelId' => $panelId,
                'comment' => $dummy_comments[array_rand($dummy_comments, 1)],
                'status' => rand(0, 1),
                'failures' => count($ft) === 0 ? [] : $createFailures()
            ];

            array_push($results, $result);
        }

        return $results;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = new DummyRequest;
        $controller = new InspectionController($this->worker, $this->failureType, $this->modificationType, $this->holeModificationType, $this->inspectionResult);

        //成型_外観検査_ドア_ドアインナーR
        $process = 'molding';
        $inspection = 'gaikan';
        $request->setForGet($process, $inspection, ['ドアインナR']);
        $getted = $controller->getInspection('950A', $request);

        for ($i = 1; $i <= 5; $i++) {
            $panelId = 'X'.str_pad($i, 7, 0, STR_PAD_LEFT);
            $parts = $this->createPart($panelId, $getted['partTypes'], $getted['failures']);

            $choku = $this->getChoku($getted['workers']);
            $worker = $this->getWorker($getted['workers'], $choku);
            $line = 1;

            $request->setForSave($choku, $worker, $line, $parts);
            $controller->saveInspection('950A', $request);
        }

        $this->info('ok');
    }
}
