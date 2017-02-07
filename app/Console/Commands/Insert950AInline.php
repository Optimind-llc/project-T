<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\PartType;
use App\Models\Client\Part;
use App\Models\Client\Page;
use App\Models\Client\InspectionFamily;

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

    /**
     * Insert csv to database.
     *
     * @return boolen
     */
    protected function insertFile($file)
    {
        foreach ($file as $key => $raw) {
            if ($key == 0) {
                /*
                 * Chack svg data structure.
                 */
                if (count($raw) < 28) {
                    $message = 'CSV structure error: '.count($raw).' columns is contained in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->info($message);
                    return 0;
                }
                /*
                 * If svg data collect.
                 */
                if (count($raw) >= 28) {
                    $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $raw[0]);
                    $part_type_pn = substr($raw[1], 0, 5);

                    /*
                     * If inspected part is inner
                     */
                    if ($part_type_pn == '67149' && $filepath['process'] == 'M' && $filepath['line'] == '001') {
                        $this->info($filepath['line']);
                        $part_type_id = PartType::where('pn', $part_type_pn)
                            ->first()
                            ->id;

                        $panel_id = $raw[3].$raw[4];
                        $newPart = Part::where('panel_id', $panel_id)
                            ->where('part_type_id', $part_type_id)
                            ->first();

                        if (!$newPart instanceof Part) {
                            $newPart = new Part;
                            $newPart->panel_id = $panel_id;
                            $newPart->part_type_id = $part_type_id;
                            $newPart->save();
                        }

                        $status = $raw[5] == 'OK' ? 1 : 0;

                        // Get choke from molding inspection of same panelID
                        $molding_result = DB::table('inspection_families')
                            ->whereIn('inspection_group_id', [1, 2])
                            ->join('pages as pg', function ($join) {
                                $join->on('pg.family_id', '=', 'inspection_families.id');
                            })
                            ->join('part_page as pp', function($join) {
                                $join->on('pp.page_id', '=', 'pg.id');
                            })
                            ->join('parts as pt', function ($join) use ($newPart) {
                                $join->on('pt.id', '=', 'pp.part_id')
                                    ->where('pt.id', '=', $newPart->id);
                            })
                            ->select('inspection_families.*')
                            ->first();

                        $choku = '不明';
                        $created_by = '精度検査';
                        if (count($molding_result) >= 1) {
                            $choku = $molding_result->inspector_group;
                            $created_by = $molding_result->created_by;
                        }

                        // Create new Family, inspection_group_id = 3
                        $newFamily = new InspectionFamily;
                        $newFamily->inspection_group_id = 3;
                        $newFamily->inspector_group = $choku;
                        $newFamily->created_by = $created_by;
                        $newFamily->inspected_at = $inspected_at;
                        if (count($molding_result) >= 1) {
                            $newFamily->created_at = $molding_result->created_at;
                        } else {
                            $newFamily->created_at = $inspected_at;
                        }
                        $newFamily->status = $status;
                        $newFamily->save();

                        // Create new page, page_type_id = 3
                        $newPage = new Page;
                        $newPage->table = null;
                        $newPage->page_type_id = 3;
                        $newPage->family_id = $newFamily->id;
                        $newPage->save();

                        // Attach parts status to page
                        $newPage->parts()->attach($newPart->id, ['status' => $status]);

                        $data = [
                        ];

                        DB::table('inline_page')->insert($data);
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
        $dirPath = config('path.'.env('SERVER').'.950A.inline');
        $backupPath = config('path.'.env('SERVER').'.950A.backup');

        $files = scandir($dirPath);

        $results = [];
        foreach ($files as $file) {
            $now = Carbon::now();
            $filePath = $dirPath.DIRECTORY_SEPARATOR.$file;
            $extension = pathinfo($file)['extension'];

            if (is_file($filePath) && $extension === 'csv') {
                // $lists[] = [
                //     'name' => $file,
                //     'path' => $filePath,
                //     'process' => substr($file, 0, 1),　
                //     'line' => 1
                // ];

                $file = new \SplFileObject($filePath);
                $file->setFlags(\SplFileObject::READ_CSV);

                // count row of CSV file
                if (count($file) > 1) {
                    rename($filePath, $backupPath.DIRECTORY_SEPARATOR.$file);

                    $message = 'CSV structure error: '.count($file).' row is contained in "'.$filepath['path'].'"';
                    \Log::error($message);
                    $this->info($message);
                    $results[] = 0;
                }
                else {
                    $results[] = $this->insertFile($file);
                }

                $file = null;
                unlink($filepath['path']);
            }
        }

        if (count($lists) == 0) {
            $this->info('No CSV file in "'.$dirPath.'"');
            return true;
        }

        $this->info(count($lists).' CSV file is found in '.$dirPath);

        $results = [];
        foreach ($lists as $list) {
            if ($list['line'] === '011') {
                rename($list['path'], $backupPath.DIRECTORY_SEPARATOR.$list['name']);
            }
            else {
                $results[] = $this->insertFile($list);






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


                }

                $file = null;
                unlink($filepath['path']);
                return 1;




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
