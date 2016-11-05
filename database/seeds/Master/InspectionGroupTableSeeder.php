<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectionGroupTableSeeder
 */
class InspectionGroupTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'inspection_groups';
        $now = Carbon::now();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        }

        $data = [
            [
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => '1',
                'inspection_id' => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => '2',
                'inspection_id' => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 2,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 3,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'small',
                'vehicle_num'   => '680A',
                'line'          => '1',
                'inspection_id' => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'small',
                'vehicle_num'   => '680A',
                'line'          => '2',
                'inspection_id' => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'small',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 2,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'small',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 3,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 4,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 5,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 7,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 8,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            //(追加) 穴あけ：外観検査：インナー
            [
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 10,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            //(追加) 接着：簡易CF：インナーASSY
            [
                'division_en'   => 'inner_assy',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            //(追加) 穴あけ：オフライン手直し：インナー
            [
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            //(追加) 穴あけ：オフライン手直し：アウター
            [
                'division_en'   => 'inner',
                'vehicle_num'   => '680A',
                'line'          => null,
                'inspection_id' => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}