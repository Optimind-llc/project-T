<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PageTypeTableSeeder
 */
class PageTypeTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'page_types';
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
                'number'     => 1,
                'group_id'   => 1,
                'figure_id'  => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 2,
                'figure_id'  => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 3,
                'figure_id'  => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 2,
                'group_id'   => 3,
                'figure_id'  => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 3,
                'group_id'   => 3,
                'figure_id'  => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 4,
                'group_id'   => 3,
                'figure_id'  => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 4,
                'figure_id'  => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 2,
                'group_id'   => 4,
                'figure_id'  => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 5,
                'figure_id'  => 9,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 6,
                'figure_id'  => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 2,
                'group_id'   => 6,
                'figure_id'  => 11,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 7,
                'figure_id'  => 12,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 8,
                'figure_id'  => 13,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 9,
                'figure_id'  => 14,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 10,
                'figure_id'  => 15,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 11,
                'figure_id'  => 16,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 12,
                'figure_id'  => 17,
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