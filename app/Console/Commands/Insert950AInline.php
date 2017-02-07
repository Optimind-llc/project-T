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

        foreach ($file as $key => $raw) {}

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
        // $dirPath = config('path.inline');

        $dirPath = config('path.'.env('SERVER').'.inline');
        $backupPath = config('path.'.env('SERVER').'.backup');

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
