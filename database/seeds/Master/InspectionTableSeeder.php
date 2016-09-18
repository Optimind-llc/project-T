<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectionTableSeeder
 */
class InspectionTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'inspections';
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
                'name'       => '検査',
                'en'         => 'check',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'インライン検査',
                'en'         => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '検査',
                'en'         => 'check',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'インライン検査',
                'en'         => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '止水',
                'en'         => 'water_stop',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '仕上',
                'en'         => 'finish',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '点検',
                'en'         => 'check',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '特検',
                'en'         => 'special_check',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直し',
                'en'         => 'adjust',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}