<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectorGroupTableSeeder
 */
class InspectorGroupTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $table_name = 'inspector_groups';

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
                'name'       => '黄直',
                'code'       => 'Y',
                'start_at'   => 6,
                'end_at'     => 14,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白直',
                'code'       => 'W',
                'start_at'   => 15,
                'end_at'     => 23,
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