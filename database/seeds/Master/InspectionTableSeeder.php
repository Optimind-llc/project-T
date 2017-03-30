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

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            [
                'name'       => '外観検査',
                'en'         => 'gaikan',
                'sort'       => 1,
                'process_id' => 'molding',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '精度検査',
                'en'         => 'inline',
                'sort'       => 2,
                'process_id' => 'molding',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴検査',
                'en'         => 'ana',
                'sort'       => 2,
                'process_id' => 'holing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '精度検査',
                'en'         => 'inline',
                'sort'       => 1,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '止水',
                'en'         => 'shisui',
                'sort'       => 3,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '仕上',
                'en'         => 'shiage',
                'sort'       => 4,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '検査',
                'en'         => 'kensa',
                'sort'       => 5,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '特検',
                'en'         => 'tokken',
                'sort'       => 6,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直し',
                'en'         => 'tenaoshi',
                'sort'       => 7,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //(追加)
            [
                'name'       => '外観検査',
                'en'         => 'gaikan',
                'sort'       => 1,
                'process_id' => 'holing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '簡易CF',
                'en'         => 'kanicf',
                'sort'       => 2,
                'process_id' => 'jointing',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'オフライン手直し',
                'en'         => 'offline',
                'sort'       => 3,
                'process_id' => 'holing',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}