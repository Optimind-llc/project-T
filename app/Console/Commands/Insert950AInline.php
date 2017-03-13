<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Vehicle950A\PartType;
use App\Models\Vehicle950A\Part;
use App\Models\Vehicle950A\Page;
use App\Models\Vehicle950A\InspectionResult;

class Insert950AInline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert950AInline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert inline data for 950A to database';
    
    protected $dirPath;
    protected $backupPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->dirPath = config('path.'.config('app.server_place').'.950A.inline');
        $this->backupPath = config('path.'.config('app.server_place').'.950A.backup');
    }

    protected function saveInspection($pn, $panel_id, $inspected_at, $status, $inlines)
    {
        $newPart = Part::where('panel_id', $panel_id)
            ->where('pn', $pn)
            ->first();

        if (!$newPart instanceof Part) {
            $newPart = new Part;
            $newPart->panel_id = $panel_id;
            $newPart->pn = $pn;
            $newPart->save();
        }

        // Results older than self
        $m_inline_results_old = InspectionResult::where('process', '=', 'molding')
            ->where('inspection', '=', 'inline')
            ->where('part_id', '=', $newPart->id)
            ->where('inspected_at', '<=', $inspected_at)
            ->get();

        if ($m_inline_results_old->count() > 0) {
            foreach ($m_inline_results_old as $m_i_result) {
                $m_i_result->latest = 0;
                $m_i_result->save();
            }
        }

        // Results older than self
        $m_inline_results_new = InspectionResult::where('process', '=', 'molding')
            ->where('inspection', '=', 'inline')
            ->where('part_id', '=', $newPart->id)
            ->where('inspected_at', '>', $inspected_at)
            ->get();

        $latest = 1;
        if ($m_inline_results_new->count() > 0) {
            $latest = 0;
        }

        // Get choke from molding inspection of same panelID
        $m_gaikan_result = InspectionResult::where('process', '=', 'molding')
            ->where('inspection', '=', 'gaikan')
            ->where('part_id', '=', $newPart->id)
            ->first();

        $choku = 'NA';
        $created_by = '';
        $created_at = $inspected_at;
        if (!is_null($m_gaikan_result)) {
            $created_at = $m_gaikan_result->created_at;
            $choku = $m_gaikan_result->created_choku;
            $created_by = $m_gaikan_result->created_by;
        }

        // Save inline inspection result
        $newResult = new InspectionResult;
        $newResult->part_id = $newPart->id;
        $newResult->process = 'molding';
        $newResult->inspection = 'inline';
        $newResult->created_choku = $choku;
        $newResult->created_by = $created_by;
        $newResult->status = $status;
        $newResult->latest = $latest;
        $newResult->inspected_at = $inspected_at;
        $newResult->created_at = $created_at;
        $newResult->save();

        $figures = collect(DB::connection('950A')
            ->table('figures')
            ->where('process', '=', 'molding')
            ->where('inspection', '=', 'inline')
            ->select(['id', 'pt_pn', 'process', 'inspection'])
            ->get());

        $getFigureId = function($pn, $p, $i) use($figures) {
            return $figures->first(function($key, $v) use ($pn, $p, $i) {
                return $v->pt_pn == $pn && $v->process === $p && $v->inspection === $i;
            })->id;
        };

        $irId = $newResult->id;
        $part_id = $newPart->id;
        $data = array_map(function($i) use($irId, $part_id, $pn, $getFigureId, $inspected_at) {
            return [
                'type_id'    => $i[0],
                'status'     => $i[1],
                'ir_id'      => $irId,
                'part_id'    => $part_id,
                'figure_id'  => $getFigureId($pn, 'molding', 'inline'),
                'created_at' => $inspected_at
            ];
        }, $inlines);
        DB::connection('950A')->table('inlines')->insert($data);
    }

    protected function insertFile($filepath)
    {
        $file = new \SplFileObject($filepath['path']);
        $file->setFlags(\SplFileObject::READ_CSV);

        $now = Carbon::now();

        foreach ($file as $row => $data) {
            if ($filepath['head']  === 'M011' && $row === 0 && count($data) === 11) {
                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $data[0]);
                $pn = substr($data[1], 0, 10);

                if ($pn !== '6715111020') {
                    $message = 'CSV structure error: PN does not match in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->error($message);
                    $file = null;
                    // rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
                    return 0;
                }

                $this->info('M011 0 '.$pn.' '.$inspected_at->toDateTimeString().' has '.count($data).'columns');

                $panel_id = $data[3].$data[4];
                $status = $data[5] == 'OK' ? 1 : 0;

                $reinforceR_inlines = [
                    [10,$data[6]],
                    [11,$data[7]],
                    [12,$data[8]],
                    [13,$data[9]],
                    [14,$data[10]]
                ];

                $this->saveInspection($pn, $panel_id, $inspected_at, $status, $reinforceR_inlines);
            }
            elseif ($filepath['head']  === 'M011' && $row === 1 && count($data) === 21) {
                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $data[0]);
                $pn = substr($data[1], 0, 10);

                if ($pn !== '6714111020') {
                    $message = 'CSV structure error: PN does not match in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->error($message);
                    $file = null;
                    // rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
                    return 0;
                }

                $this->info('M011 1 '.$pn.' '.$inspected_at->toDateTimeString().' has '.count($data).'columns');

                $panel_id = $data[3].$data[4];
                $status = $data[5] == 'OK' ? 1 : 0;

                $doorInnerR_inlines = [
                    [1, $data[6] ],
                    [2, $data[7] ],
                    [3, $data[8] ],
                    [4, $data[9] ],
                    [5, $data[10]],
                    [6, $data[11]],
                    [7, $data[12]],
                    [8, $data[13]],
                    [9, $data[14]],
                    [15,$data[15]],
                    [16,$data[16]],
                    [17,$data[17]],
                    [18,$data[18]],
                    [19,$data[19]],
                    [20,$data[20]]
                ];

                $this->saveInspection($pn, $panel_id, $inspected_at, $status, $doorInnerR_inlines);
            }
            elseif ($filepath['head']  === 'M021' && $row === 0 && count($data) === 11) {
                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $data[0]);
                $pn = substr($data[1], 0, 10);

                if ($pn !== '6715211020') {
                    $message = 'CSV structure error: PN does not match in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->error($message);
                    $file = null;
                    // rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
                    return 0;
                }

                $this->info('M021 0 '.$pn.' '.$inspected_at->toDateTimeString().' has '.count($data).'columns');

                $panel_id = $data[3].$data[4];
                $status = $data[5] == 'OK' ? 1 : 0;

                $reinforceL_inlines = [
                    [30,$data[6]],
                    [31,$data[7]],
                    [32,$data[8]],
                    [33,$data[9]],
                    [34,$data[10]]
                ];

                $this->saveInspection($pn, $panel_id, $inspected_at, $status, $reinforceL_inlines);
            }
            elseif ($filepath['head']  === 'M021' && $row === 1 && count($data) === 21) {
                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $data[0]);
                $pn = substr($data[1], 0, 10);

                if ($pn !== '6714211020') {
                    $message = 'CSV structure error: PN does not match in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->error($message);
                    $file = null;
                    // rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
                    return 0;
                }

                $this->info('M021 1 '.$pn.' '.$inspected_at->toDateTimeString().' has '.count($data).'columns');

                $panel_id = $data[3].$data[4];
                $status = $data[5] == 'OK' ? 1 : 0;

                $doorInnerL_inlines = [
                    [21,$data[6] ],
                    [22,$data[7] ],
                    [23,$data[8] ],
                    [24,$data[9] ],
                    [25,$data[10]],
                    [26,$data[11]],
                    [27,$data[12]],
                    [28,$data[13]],
                    [29,$data[14]],
                    [35,$data[15]],
                    [36,$data[16]],
                    [37,$data[17]],
                    [38,$data[18]],
                    [39,$data[19]],
                    [40,$data[20]]
                ];

                $this->saveInspection($pn, $panel_id, $inspected_at, $status, $doorInnerL_inlines);
            }
            elseif ($filepath['head']  === 'M001' && $row === 0 && count($data) === 23) {
                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $data[0]);
                $pn = substr($data[1], 0, 10);

                if ($pn !== '6441211010' && $pn !== '6441211020') {
                    $message = 'CSV structure error: PN does not match in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->error($message);
                    $file = null;
                    // rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
                    return 0;
                }

                $this->info('M001 0 '.$pn.' '.$inspected_at->toDateTimeString().' has '.count($data).'columns');

                $panel_id = $data[3].$data[4];
                $status = $data[5] == 'OK' ? 1 : 0;

                if ($pn == '6441211010') {
                    $luggageInner_inlines = [
                        [41,$data[6] ],
                        [42,$data[7] ],
                        [43,$data[8] ],
                        [44,$data[9] ],
                        [45,$data[10]],
                        [46,$data[11]],
                        [47,$data[12]],
                        [48,$data[13]],
                        [49,$data[14]],
                        [50,$data[15]],
                        [51,$data[16]],
                        [52,$data[17]],
                        [53,$data[18]],
                        [54,$data[19]],
                        [55,$data[20]],
                        [56,$data[21]],
                        [57,$data[22]]
                    ];
                }
                elseif ($pn == '6441211020') {
                    $luggageInner_inlines = [
                        [58,$data[6] ],
                        [59,$data[7] ],
                        [60,$data[8] ],
                        [61,$data[9] ],
                        [62,$data[10]],
                        [63,$data[11]],
                        [64,$data[12]],
                        [65,$data[13]],
                        [66,$data[14]],
                        [67,$data[15]],
                        [68,$data[16]],
                        [69,$data[17]],
                        [70,$data[18]],
                        [71,$data[19]],
                        [72,$data[20]],
                        [73,$data[21]],
                        [74,$data[22]]
                    ];
                }

                $this->saveInspection($pn, $panel_id, $inspected_at, $status, $luggageInner_inlines);
            }
        }

        $file = null;
        unlink($filepath['path']);
        return 1;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = scandir($this->dirPath);

        $lists = array();
        foreach ($files as $file) {
            $filePath = $this->dirPath.DIRECTORY_SEPARATOR.$file;
            $extension = pathinfo($file)["extension"];

            if (is_file($filePath) && $extension == 'csv') {
                $lists[] = [
                    'name' => $file,
                    'path' => $filePath,
                    'process' => substr($file, 0, 1),
                    'head' => substr($file, 0, 4),
                    'line' => substr($file, 3, 1)
                ];
            }
        }

        if (count($lists) == 0) {
            $this->info('No CSV file in "'.$this->dirPath.'"');
        }

        $this->info(count($lists).' CSV file is found in '.$this->dirPath);

        $results = [];
        foreach ($lists as $list) {
            if ($list['head'] === 'M011' || $list['head'] === 'M021' || $list['head'] === 'M001') {
                $results[] = $this->insertFile($list);
            }
            else {
                rename($list['path'], $this->backupPath.DIRECTORY_SEPARATOR.$list['name']);
            }
        }

        $results_sum = array_sum($results);
        $results_count = count($results);

        if ($results_sum === $results_count) {
            $this->info('All ' .$results_sum. ' data was inserted to database.');
        }
        else {
            $message = $results_sum.' data was inserted to database. '.($results_count-$results_sum).' data was fail.';
            $this->info($message);
        }
    }
}
