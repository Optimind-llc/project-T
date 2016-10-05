<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\PartType;
use App\Models\Client\Part;

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
        $file = new \SplFileObject($filepath);
        $file->setFlags(\SplFileObject::READ_CSV);

        $now = Carbon::now();

        foreach ($file as $key => $row) {
            if (count($row) === 28) {

                $inner_id = PartType::where('pn', substr($row[1], 0, 5))
                    ->first()
                    ->id;

                $panel_id = $row[3].$row[4];
                $newPart = Part::where('panel_id', $panel_id)
                    ->where('part_type_id', $inner_id)
                    ->first();

                if (!$newPart instanceof Part) {
                    $newPart = new Part;
                    $newPart->panel_id = $panel_id;
                    $newPart->part_type_id = $inner_id;
                    $newPart->save();
                }

                $inspected_at = Carbon::createFromFormat('Y/m/d H:i:s', $row[0]);

                $data = [
                    'status'     => $row[5] == 'OK' ? 1 : 0,
                    'p1'         => $row[6],
                    'p2'         => $row[7],
                    'p3'         => $row[8],
                    'p4'         => $row[9],
                    'p5'         => $row[10],
                    'p6'         => $row[11],
                    'p7'         => $row[12],
                    'p8'         => $row[13],
                    'p9'         => $row[14],
                    'p10'        => $row[15],
                    'p11'        => $row[16],
                    'p12'        => $row[17],
                    'p13'        => $row[18],
                    'p14'        => $row[19],
                    'p15'        => $row[20],
                    'p16'        => $row[21],
                    'p17'        => $row[22],
                    'p18'        => $row[23],
                    'p19'        => $row[24],
                    'p20'        => $row[25],
                    'p21'        => $row[26],
                    'p22'        => $row[27],
                    'part_id'    => $newPart->id,
                    'inspected_at' => $inspected_at,
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                DB::table('inlines')->insert($data);
                unlink($filepath);
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
        $dirPath = base_path('input');

        $files = scandir($dirPath);
     
        $lists = array();
        foreach ($files as $file) {
            $filePath = $dirPath.DIRECTORY_SEPARATOR.$file;
            if (is_file($filePath)) {
                $lists[] = $filePath;
            }
        }

        if (count($lists) == 0) {
            $this->info('no files');
        }

        foreach ($lists as $list) {
            $this->insertFile($list);
        }

        $this->info('success');
    }
}
