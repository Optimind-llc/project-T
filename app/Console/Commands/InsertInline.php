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

class InsertInline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insertInline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert inline data to database';

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
    protected function insertFile($filepath)
    {
        $file = new \SplFileObject($filepath['path']);
        $file->setFlags(\SplFileObject::READ_CSV);

        $now = Carbon::now();

        foreach ($file as $key => $raw) {
            /*
             * If svg data collect.
             */
            if (count($raw) === 28) {
                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $raw[0]);
                $part_type_pn = substr($raw[1], 0, 5);

                /*
                 * If inspected part is inner
                 */
                if ($part_type_pn == '67149' && $filepath['process'] == 'M') {
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

                    // Create new Family, inspection_group_id = 3
                    $newFamily = new InspectionFamily;
                    $newFamily->inspection_group_id = 3;
                    $newFamily->inspector_group = '不明';
                    $newFamily->created_by = '精度検査';
                    $newFamily->inspected_at = $inspected_at;
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
                        [
                            'status'       => $raw[6],
                            'inline_id'    => 1,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[7],
                            'inline_id'    => 2,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[8],
                            'inline_id'    => 4,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[9],
                            'inline_id'    => 6,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[10],
                            'inline_id'    => 8,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[11],
                            'inline_id'    => 14,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[12],
                            'inline_id'    => 9,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[13],
                            'inline_id'    => 7,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[14],
                            'inline_id'    => 5,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[15],
                            'inline_id'    => 3,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[16],
                            'inline_id'    => 10,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[17],
                            'inline_id'    => 11,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[18],
                            'inline_id'    => 19,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[19],
                            'inline_id'    => 20,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[20],
                            'inline_id'    => 12,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[21],
                            'inline_id'    => 15,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[22],
                            'inline_id'    => 17,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[23],
                            'inline_id'    => 13,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[24],
                            'inline_id'    => 18,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[25],
                            'inline_id'    => 16,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[26],
                            'inline_id'    => 21,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[27],
                            'inline_id'    => 22,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ]
                    ];

                    DB::table('inline_page')->insert($data);
                }

                /*
                 * If inspected part is inner
                 */
                if ($part_type_pn == '67149' && $filepath['process'] == 'J') {
                    // Change pn to inner ASSY
                    $part_type_pn = '67007';

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

                    // Create new Family, inspection_group_id = 9
                    $newFamily = new InspectionFamily;
                    $newFamily->inspection_group_id = 9;
                    $newFamily->inspector_group = '不明';
                    $newFamily->created_by = '精度検査';
                    $newFamily->status = $status;
                    $newFamily->save();

                    // Create new page, page_type_id = 15
                    $newPage = new Page;
                    $newPage->table = null;
                    $newPage->page_type_id = 15;
                    $newPage->family_id = $newFamily->id;
                    $newPage->save();

                    // Attach parts status to page
                    $newPage->parts()->attach($newPart->id, ['status' => $status]);

                    $data = [
                        [
                            'status'       => $raw[6],
                            'inline_id'    => 23,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[7],
                            'inline_id'    => 24,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[8],
                            'inline_id'    => 26,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[9],
                            'inline_id'    => 28,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[10],
                            'inline_id'    => 30,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[11],
                            'inline_id'    => 43,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[12],
                            'inline_id'    => 34,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[13],
                            'inline_id'    => 36,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[14],
                            'inline_id'    => 27,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[15],
                            'inline_id'    => 25,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[16],
                            'inline_id'    => 42,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[17],
                            'inline_id'    => 29,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[18],
                            'inline_id'    => 31,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[19],
                            'inline_id'    => 44,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[20],
                            'inline_id'    => 35,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[21],
                            'inline_id'    => 37,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[22],
                            'inline_id'    => 38,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[23],
                            'inline_id'    => 40,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[24],
                            'inline_id'    => 32,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[25],
                            'inline_id'    => 39,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[26],
                            'inline_id'    => 41,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ],[
                            'status'       => $raw[27],
                            'inline_id'    => 33,
                            'page_id'      => $newPage->id,
                            'part_id'      => $newPart->id,
                            'inspected_at' => $inspected_at,
                            'created_at'   => $now,
                            'updated_at'   => $now
                        ]
                    ];

                    DB::table('inline_page')->insert($data);
                }
            }
        }

        $file = null;
        unlink($filepath['path']);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $dirPath = config('path.inline');

        $dirPath = config('path.'.env('SERVER').'.inline');
        // $dirPath = 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'InLine';

        $files = scandir($dirPath);
        $this->info(count($files).' CSV file is found in '.$dirPath);

        $lists = array();
        foreach ($files as $file) {
            $filePath = $dirPath.DIRECTORY_SEPARATOR.$file;

            if (is_file($filePath)) {
                $lists[] = [
                    'path' => $filePath,
                    'process' => substr($file, 0, 1)
                ];
            }
        }

        if (count($lists) == 0) {
            $this->info('no files');
        }

        foreach ($lists as $list) {
            $this->insertFile($list);
        }

        $this->info('All data was inserted to database.');
    }
}
