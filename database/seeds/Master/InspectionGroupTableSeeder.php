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
                'division_id'   => 1,
                'inspection_id' => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 1,
                'inspection_id' => 2,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 1,
                'inspection_id' => 3,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 2,
                'inspection_id' => 1,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 2,
                'inspection_id' => 2,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 2,
                'inspection_id' => 3,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 3,
                'inspection_id' => 4,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 3,
                'inspection_id' => 5,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 3,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 3,
                'inspection_id' => 7,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 3,
                'inspection_id' => 8,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'division_id'   => 3,
                'inspection_id' => 9,
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