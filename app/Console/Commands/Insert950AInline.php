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
                    rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
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
                    rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
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
                    rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
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
                    rename($filepath['path'], $this->backupPath.DIRECTORY_SEPARATOR.$filepath['name']);
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
        }

        $file = null;
        unlink($filepath['path']);
        return 1;

        foreach ($file as $key => $raw) {
            if ($key == 0) {
                /*
                 * Chack svg data structure.
                 */
                if (count($raw) > 27) {
                    $message = 'CSV structure error: '.count($raw).' columns is contained in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->info($message);
                    return 0;
                }
                /*
                 * If svg data collect.
                 */
                if (count($raw) < 27) {
                    $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $raw[0]);
                    $pn = substr($raw[1], 0, 10);

                    $this->info($pn);
                    /*
                     * If inspected part is inner
                     */
                    if ($pn == '6714111020' && $filepath['process'] == 'M') {
                        $this->info($filepath['line']);

                        $panel_id = $raw[3].$raw[4];
                        $status = $raw[5] == 'OK' ? 1 : 0;

                        $doorInnerR_inlines = [
                            [1, $raw[6] ],
                            [2, $raw[7] ],
                            [3, $raw[8] ],
                            [4, $raw[9] ],
                            [5, $raw[10]],
                            [6, $raw[11]],
                            [7, $raw[12]],
                            [8, $raw[13]],
                            [9, $raw[14]],
                            [15,$raw[20]],
                            [16,$raw[21]],
                            [17,$raw[22]],
                            [18,$raw[23]],
                            [19,$raw[24]],
                            [20,$raw[25]]
                        ];

                        $this->saveInspection($pn, $panel_id, $inspected_at, $status, $doorInnerR_inlines);

                        // For reinforceR
                        $reinforceR_inlines = [
                            [10,$raw[15]],
                            [11,$raw[16]],
                            [12,$raw[17]],
                            [13,$raw[18]],
                            [14,$raw[19]]
                        ];

                        $this->saveInspection('6715111020', $panel_id, $inspected_at, $status, $reinforceR_inlines);
                    }
                    elseif ($pn == '6714211020' && $filepath['process'] == 'M') {
                        $this->info($filepath['line']);

                        $panel_id = $raw[3].$raw[4];
                        $status = $raw[5] == 'OK' ? 1 : 0;

                        $doorInnerR_inlines = [
                            [21,$raw[6] ],
                            [22,$raw[7] ],
                            [23,$raw[8] ],
                            [24,$raw[9] ],
                            [25,$raw[10]],
                            [26,$raw[11]],
                            [27,$raw[12]],
                            [28,$raw[13]],
                            [29,$raw[14]],
                            [35,$raw[20]],
                            [36,$raw[21]],
                            [37,$raw[22]],
                            [38,$raw[23]],
                            [39,$raw[24]],
                            [40,$raw[25]]
                        ];

                        $this->saveInspection($pn, $panel_id, $inspected_at, $status, $doorInnerR_inlines);

                        // For reinforceR
                        $reinforceR_inlines = [
                            [20,$raw[15]],
                            [21,$raw[16]],
                            [22,$raw[17]],
                            [23,$raw[18]],
                            [24,$raw[19]]
                        ];

                        $this->saveInspection('6715211020', $panel_id, $inspected_at, $status, $reinforceR_inlines);
                    }
                    elseif ($pn == '6441211010' && $filepath['process'] == 'M') {
                        $this->info($filepath['line']);

                        $panel_id = $raw[3].$raw[4];
                        $status = $raw[5] == 'OK' ? 1 : 0;

                        $luggageInnerSTD_inlines = [
                            [41,$raw[6] ],
                            [42,$raw[7] ],
                            [43,$raw[8] ],
                            [44,$raw[9] ],
                            [45,$raw[10]],
                            [46,$raw[11]],
                            [47,$raw[12]],
                            [48,$raw[13]],
                            [49,$raw[14]],
                            [50,$raw[15]],
                            [51,$raw[16]],
                            [52,$raw[17]],
                            [53,$raw[18]],
                            [54,$raw[19]],
                            [55,$raw[20]],
                            [56,$raw[21]],
                            [57,$raw[22]]
                        ];

                        $this->saveInspection($pn, $panel_id, $inspected_at, $status, $luggageInnerSTD_inlines);
                    }
                    elseif ($pn == '6441211020' && $filepath['process'] == 'M') {
                        $this->info($filepath['line']);

                        $panel_id = $raw[3].$raw[4];
                        $status = $raw[5] == 'OK' ? 1 : 0;

                        $luggageInnerARW_inlines = [
                            [58,$raw[6] ],
                            [59,$raw[7] ],
                            [60,$raw[8] ],
                            [61,$raw[9] ],
                            [62,$raw[10]],
                            [63,$raw[11]],
                            [64,$raw[12]],
                            [65,$raw[13]],
                            [66,$raw[14]],
                            [67,$raw[15]],
                            [68,$raw[16]],
                            [69,$raw[17]],
                            [70,$raw[18]],
                            [71,$raw[19]],
                            [72,$raw[20]],
                            [73,$raw[21]],
                            [74,$raw[22]]
                        ];

                        $this->saveInspection($pn, $panel_id, $inspected_at, $status, $luggageInnerARW_inlines);
                    }
                }
            }
        }
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
            if ($list['head'] === 'M011' || $list['head'] === 'M021') {
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
