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

class UpdateInline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateInline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update inline data';

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

        $targetFamilies = InspectionFamily::where('created_by', '=', '精度検査')
            ->where('created_at', '>=', $today->subDays(22))
            ->with([
                'pages' => function($q) {
                    return $q->select(['pages.id', 'pages.family_id']);
                },
                'pages.parts' => function($q) {
                    return $q->select(['parts.id']);
                }
            ])
            ->get()
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'partId' => $f->pages->first()->parts->first()->id
                ];
            });

        foreach ($targetFamilies as $family) {
            $molding_result = DB::table('inspection_families')
                ->whereIn('inspection_group_id', [1, 2])
                ->join('pages as pg', function ($join) {
                    $join->on('pg.family_id', '=', 'inspection_families.id');
                })
                ->join('part_page as pp', function($join) {
                    $join->on('pp.page_id', '=', 'pg.id');
                })
                ->join('parts as pt', function ($join) use ($family) {
                    $join->on('pt.id', '=', 'pp.part_id')
                        ->where('pt.id', '=', $family['partId']);
                })
                ->select('inspection_families.*')
                ->first();

            $choku = '不明';
            $created_by = '精度検査';
            if (count($molding_result) >= 1) {
                $choku = $molding_result->inspector_group;
                $created_by = $molding_result->created_by;

                // Update new Family, inspection_group_id = 3
                $TBU = InspectionFamily::find($family['id']);
                $TBU->inspector_group = $choku;
                $TBU->created_by = $created_by;
                $TBU->created_at = $molding_result->created_at;
                $TBU->save();
            }

        }


        //For Jointing
        $targetFamilies = InspectionFamily::where('created_by', '=', '精度検査')
            ->where('inspection_group_id', '=', 9)
            ->where('created_at', '>=', $today->subDays(30))
            ->with([
                'pages' => function($q) {
                    return $q->select(['pages.id', 'pages.family_id']);
                },
                'pages.parts' => function($q) {
                    return $q->select(['parts.id']);
                }
            ])
            ->get()
            ->map(function($f) {
                return [
                    'id' => $f->id,
                    'partId' => $f->pages->first()->parts->first()->id
                ];
            });

        foreach ($targetFamilies as $family) {
            $molding_result = DB::table('inspection_families')
                ->whereIn('inspection_group_id', [11])
                ->join('pages as pg', function ($join) {
                    $join->on('pg.family_id', '=', 'inspection_families.id');
                })
                ->join('part_page as pp', function($join) {
                    $join->on('pp.page_id', '=', 'pg.id');
                })
                ->join('parts as pt', function ($join) use ($family) {
                    $join->on('pt.id', '=', 'pp.part_id')
                        ->where('pt.id', '=', $family['partId']);
                })
                ->select('inspection_families.*')
                ->first();

            $choku = '不明';
            $created_by = '精度検査';
            if (count($molding_result) >= 1) {
                $choku = $molding_result->inspector_group;
                $created_by = $molding_result->created_by;

                // Update new Family, inspection_group_id = 9
                $TBU = InspectionFamily::find($family['id']);
                $TBU->inspector_group = $choku;
                $TBU->created_by = $created_by;
                $TBU->created_at = $molding_result->created_at;
                $TBU->save();
            }

        }


        $message = 'success';
        $this->info($message);
    }
}
