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
                'path'         => 'molding_inner.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'molding_small_1.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'molding_small_2.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'holing_inner_1.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'holing_inner_2.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'holing_inner_3.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'holing_inner_4.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'holing_small_1.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'holing_small_2.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'jointing_assy.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'molding_inner_inline.png',
                'created_at'   => $now,
                'updated_at'   => $now
            ],[
                'path'         => 'jointing_assy_inline.png',
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