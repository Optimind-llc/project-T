<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class FigureTableSeeder
 */
class FigureTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'figures';
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
                'path'         => '1',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '2',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '3',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '4',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '5',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '6',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '7',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '8',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '9',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '10',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '11',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '12',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '13',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '14',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '15',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '16',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => '17',
                'created_at'   => $now,
                'updated_at'   => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}