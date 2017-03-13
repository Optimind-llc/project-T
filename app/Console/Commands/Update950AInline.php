<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Storage;
// Models
use App\Models\Vehicle950A\InspectionResult;

class Update950AInline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update950AInline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update 950A inline data';

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
        $today = Carbon::today();

        $targetInspectionResults = InspectionResult::where('created_choku', '=', 'NA')
            ->where('created_at', '>=', $today->subDays(1))
            ->get();

        foreach ($targetInspectionResults as $ir) {
            $m_g_ir = InspectionResult::where('part_id', '=', $ir->part_id)
                ->where('process', '=', 'molding')
                ->where('inspection', '=', 'gaikan')
                ->select(['part_id', 'process', 'inspection', 'created_choku', 'created_by'])
                ->first();

            if (!is_null($m_g_ir)) {
                $ir->created_choku = $m_g_ir->created_choku;
                $ir->created_by = $m_g_ir->created_by;
                $ir->save();

                $this->info('inspection_result_id = '.$ir->id. ' was updated');
            }
        }

        $message = 'success';
        $this->info($message);
    }
}
