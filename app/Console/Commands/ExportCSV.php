<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Vehicle950A\InspectionResult;

class ExportCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportCSV';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'export csv file for 950A';

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
        $ir = InspectionResult::where('inspection_en', '!=', 'inline')
            ->whereColumn('exported_at', '<', 'updated_at')
            ->get(['id'])
            ->map(function($ir) {
                return $ir->id;
            })
            ->toArray();

        $this->info('ok');
    }
}
