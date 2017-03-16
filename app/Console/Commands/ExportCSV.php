<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Models
use App\Models\Vehicle950A\InspectionResult;
// Services
use App\Services\Vehicle950A\Choku;

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


    protected function exportCSV($filePath, $data)
    {
        if(touch($filePath)){
            $file = new \SplFileObject($filePath, 'w');

            foreach($data as $key => $val){
                $export_raw[] = mb_convert_encoding($val, 'SJIS-win');
            }

            $file->fputcsv($export_raw);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();

        $irs = InspectionResult::with([
                'part',
                'failures' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
                'modifications' => function($q) {
                    return $q->select('ir_id', 'type_id');
                },
            ])
            ->where('process', '!=', 'holing')
            ->where('inspection', '!=', 'inline')
            ->where(function($query) {
                $query->whereNull('exported_at')->orWhere(function($query) {
                    $query->whereColumn('exported_at', '<', 'updated_at');
                });
            })
            ->get();


        $this->info($irs->count());

        $dirPath = config('path.'.config('app.server_place').'.950A.output');

        foreach ($irs as $ir) {
            $pn = $ir->part->pn;
            $pn1 = substr($pn, 0, 5);
            $pn2 = substr($pn, 5, 5);

            $panelId = $ir->part->panel_id;
            $idHead = substr($panelId, 0, 1);
            $idBody = substr($panelId, 1, 7);

            $p = $ir->process;
            $l = str_pad($ir->line, 3, 0, STR_PAD_LEFT);
            $i = $ir->inspection;

            $chokuCode = $ir->created_choku;
            $choku_obj = new Choku($ir->inspected_at);
            $chokuNum = $choku_obj->getChokuNum();

            $by = $ir->created_by;
            $at = $ir->inspected_at;

            $s = 0;
            if ($ir->status === 0) { $s = 1; }

            $data = ['950A', $pn1, $pn2, $idHead, $idBody, $p, $l, $i, $chokuCode, $chokuNum, $by, $s];

            $fs = array_count_values($ir->failures->map(function($f) {
                return $f->type_id;
            })->toArray());

            $ftIds = unserialize($ir->ft_ids);
            foreach ($ftIds as $ftId) {
                $count = null;
                $count = 0;
                if (array_key_exists($ftId, $fs)) {
                    $count = $fs[$ftId];
                }
                array_push($data, $count);
            }

            $ms = array_count_values($ir->failures->map(function($m) {
                return $m->type_id;
            })->toArray());

            $mtIds = unserialize($ir->mt_ids);
            foreach ($mtIds as $mtId) {
                $count = null;
                $count = 0;
                if (array_key_exists($mtId, $ms)) {
                    $count = $ms[$mtId];
                }
                array_push($data, $count);
            }

            array_push($data, $at->toDateTimeString());

            $fileName = strtoupper(substr($p, 0, 1)).$l.'_'.$pn1.$pn2.'_'.$at->format('Ymd_His');
            $filePath = $dirPath.DIRECTORY_SEPARATOR.ucfirst($p).DIRECTORY_SEPARATOR.$fileName.'.csv';
            $this->exportCSV($filePath, $data);

            $ir->exported_at = $now;
            $ir->updated_at = $now;
            $ir->save();
        }

        $this->info('success');
    }
}
