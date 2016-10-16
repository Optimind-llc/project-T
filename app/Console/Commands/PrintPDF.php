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

class PrintPDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'printPdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert inline data to';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dirPath = storage_path('pdf');
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
            $com = 'C:\"Program Files (x86)\Adobe\Reader 11.0\Reader\AcroRd32.exe" /t /h '.$list;
            exec($com);
            sleep(4);
            unlink($list);
        }

        $this->info('Inline data was inserted to database.');
    }
}
