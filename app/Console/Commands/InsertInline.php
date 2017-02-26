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

        if (count($file) > 1) {
            $message = 'CSV structure error: '.count($file).' row is contained in "'.$filepath['path'].'"';
            \Log::error($message);
            $this->info($message);
            return 0;
        }

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
                    if ($part_type_pn == '67149' && $filepath['process'] == 'M' && $filepath['line'] == '002') {
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
                        $newFamily->inspection_group_id = 19;
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
                        $newPage->page_type_id = 32;
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
                        $newFamily->inspected_at = $inspected_at;
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
        }

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
        $dirPath = config('path.'.config('app.server_place').'.inline');
        $backupPath = config('path.'.config('app.server_place').'.backup');

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
