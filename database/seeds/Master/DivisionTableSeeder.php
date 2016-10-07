<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DivisionTableSeeder
 */
class DivisionTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'divisions';
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
                'name'       => 'インナー',
                'en'         => 'inner',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'アウター',
                'en'         => 'small',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'インナーASSY',
                'en'         => 'inner_assy',
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