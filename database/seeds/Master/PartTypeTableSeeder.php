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
        $table_name = 'part_types';
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
                'pn'          => 67149,
                'name'        => 'バックドアインナ',
                'vehicle_id'  => 1,
                'division_id' => 1,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67119,
                'name'        => 'アッパー',
                'vehicle_id'  => 1,
                'division_id' => 2,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67175,
                'name'        => 'サイドR',
                'vehicle_id'  => 1,
                'division_id' => 2,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67176,
                'name'        => 'サイドL',
                'vehicle_id'  => 1,
                'division_id' => 2,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67177,
                'name'        => 'ロアR',
                'vehicle_id'  => 1,
                'division_id' => 2,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67178,
                'name'        => 'ロアL',
                'vehicle_id'  => 1,
                'division_id' => 2,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}