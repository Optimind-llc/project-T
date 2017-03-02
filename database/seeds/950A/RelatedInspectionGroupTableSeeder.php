<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectorTableSeeder
 */
class RelatedInspectionGroupTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /*
         * Create process table seeder
         */
        $table_name = 'processes';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::connection('950A')->table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            [
                'name'       => '成型',
                'en'         => 'molding',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴あけ',
                'en'         => 'holing',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'かしめ/接着',
                'en'         => 'jointing',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /*
         * Create inspection table seeder
         */
        $table_name = 'inspections';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::connection('950A')->table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            [
                'name'       => '外観検査',
                'en'         => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '精度検査',
                'en'         => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴検査',
                'en'         => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'かしめ後検査',
                'en'         => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '外周仕上',
                'en'         => 'gaishushiage',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'パテ修復後',
                'en'         => 'pateshufukugo',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '水検後',
                'en'         => 'suikengo',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '塗装受入後',
                'en'         => 'tosoukeirego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直',
                'en'         => 'tenaoshi',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection('950A')->table($table_name)->insert($data);
    }
}