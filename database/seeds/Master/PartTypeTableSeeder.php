<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PartTypeTableSeeder
 */
class PartTypeTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create part_type table seeder
         */
        $table_name = 'part_types';

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
                'pn'          => 67149,
                'name'        => 'バックドアインナ',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67119,
                'name'        => 'アッパー',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67175,
                'name'        => 'サイドR',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67176,
                'name'        => 'サイドL',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67177,
                'name'        => 'ロアR',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67178,
                'name'        => 'ロアL',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67007,
                'name'        => 'バックドアインナASSY',
                'vehicle_num' => '680A',
                'created_at'  => $now,
                'updated_at'  => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        /**
         * create part_type part_page related table seeder
         */
        $table_name = 'part_type_page_type';

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
                'page_type_id' => 1,
                'part_type_id' => 1,
            ],[
                'page_type_id' => 2,
                'part_type_id' => 1,
            ],[
                'page_type_id' => 3,
                'part_type_id' => 1,
            ],[
                'page_type_id' => 4,
                'part_type_id' => 1,
            ],[
                'page_type_id' => 5,
                'part_type_id' => 1,
            ],[
                'page_type_id' => 6,
                'part_type_id' => 1,
            ],[
                'page_type_id' => 7,
                'part_type_id' => 2,
            ],[
                'page_type_id' => 8,
                'part_type_id' => 3,
            ],[
                'page_type_id' => 8,
                'part_type_id' => 4,
            ],[
                'page_type_id' => 8,
                'part_type_id' => 5,
            ],[
                'page_type_id' => 8,
                'part_type_id' => 6,
            ],[
                'page_type_id' => 9,
                'part_type_id' => 2,
            ],[
                'page_type_id' => 9,
                'part_type_id' => 3,
            ],[
                'page_type_id' => 9,
                'part_type_id' => 4,
            ],[
                'page_type_id' => 9,
                'part_type_id' => 5,
            ],[
                'page_type_id' => 9,
                'part_type_id' => 6,
            ],[
                'page_type_id' => 10,
                'part_type_id' => 2,
            ],[
                'page_type_id' => 11,
                'part_type_id' => 3,
            ],[
                'page_type_id' => 11,
                'part_type_id' => 4,
            ],[
                'page_type_id' => 11,
                'part_type_id' => 5,
            ],[
                'page_type_id' => 11,
                'part_type_id' => 6,
            ],[
                'page_type_id' => 12,
                'part_type_id' => 7,
            ],[
                'page_type_id' => 13,
                'part_type_id' => 7,
            ],[
                'page_type_id' => 14,
                'part_type_id' => 7,
            ],[
                'page_type_id' => 15,
                'part_type_id' => 7,
            ],[
                'page_type_id' => 16,
                'part_type_id' => 7,
            ],[
                'page_type_id' => 17,
                'part_type_id' => 7,
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}