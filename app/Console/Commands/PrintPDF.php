<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Storage;
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
        $files = Storage::disk('inline')->files();

        $this->info(Storage::disk('inline')->root());

        // foreach ($files as $key => $value) {
        //     $this->info($value);
        //     $value->setFlags(SplFileObject::READ_CSV);
        // }

        // foreach ($lists as $list) {
        //     $com = 'C:\"Program Files (x86)\Adobe\Reader 11.0\Reader\AcroRd32.exe" /t /h '.$list;
        //     exec($com, $output);
        //     $this->info($output);
        //     sleep(5);
        //     unlink($list);
        // }

        $this->info('');
    }
}
