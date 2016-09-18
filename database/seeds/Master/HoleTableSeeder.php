<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class HoleTableSeeder
 */
class HoleTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'holes';
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
                'x'          => 100,
                'y'          => 100,
                'sort'       => 1,
                'figure_id'  => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'sort'       => 2,
                'figure_id'  => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 300,
                'y'          => 300,
                'sort'       => 3,
                'figure_id'  => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 400,
                'y'          => 400,
                'sort'       => 4,
                'figure_id'  => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 500,
                'y'          => 500,
                'sort'       => 5,
                'figure_id'  => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 100,
                'y'          => 100,
                'sort'       => 1,
                'figure_id'  => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 100,
                'y'          => 100,
                'sort'       => 1,
                'figure_id'  => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 100,
                'y'          => 100,
                'sort'       => 1,
                'figure_id'  => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 100,
                'y'          => 100,
                'sort'       => 1,
                'figure_id'  => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 100,
                'y'          => 100,
                'sort'       => 1,
                'figure_id'  => 11,
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