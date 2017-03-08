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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function saveInspection($pn, $panel_id, $inspected_at, $status, $inlines)
    {
        $now = Carbon::now();

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
        $created_by = 'NA';
        $created_at = $now;
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
        $data = array_map(function($i) use($irId, $part_id, $pn, $getFigureId, $now) {
            return [
                'type_id'    => $i[0],
                'status'     => $i[1],
                'ir_id'      => $irId,
                'part_id'    => $part_id,
                'figure_id'  => $getFigureId($pn, 'molding', 'inline'),
                'created_at' => $now
            ];
        }, $inlines);
        DB::connection('950A')->table('inlines')->insert($data);
    }

    protected function insertFile($filepath)
    {
        $file = new \SplFileObject($filepath['path']);
        $file->setFlags(\SplFileObject::READ_CSV);

        $now = Carbon::now();

        if (count($file) > 1) {
            $message = 'CSV structure error: '.count($file).' row is contained in "'.$filepath['path'].'"';
            \Log::error($message);
            $this->info($message);
            return 0;
        }

        foreach ($file as $key => $raw) {
            if ($key == 0) {
                /*
                 * Chack svg data structure.
                 */
                if (count($raw) < 26) {
                    $message = 'CSV structure error: '.count($raw).' columns is contained in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->info($message);
                    return 0;
                }
                /*
                 * If svg data collect.
                 */
                if (count($raw) >= 26) {
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
                }
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
        $dirPath = config('path.'.config('app.server_place').'.950A.inline');
        $backupPath = config('path.'.config('app.server_place').'.950A.backup');

        $files = scandir($dirPath);

        $lists = array();
        foreach ($files as $file) {
            $filePath = $dirPath.DIRECTORY_SEPARATOR.$file;
            $extension = pathinfo($file)["extension"];

            if (is_file($filePath) && $extension == 'csv') {
                $lists[] = [
                    'name' => $file,
                    'path' => $filePath,
                    'process' => substr($file, 0, 1),
                    'line' => substr($file, 1, 3)
                ];
            }
        }

        if (count($lists) == 0) {
            $this->info('No file in "'.$dirPath.'"');
        }

        $this->info(count($lists).' CSV file is found in '.$dirPath);

        $results = [];
        foreach ($lists as $list) {
            if ($list['line'] === '011') {
                rename($list['path'], $backupPath.DIRECTORY_SEPARATOR.$list['name']);
            }
            else {
                $results[] = $this->insertFile($list);
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
