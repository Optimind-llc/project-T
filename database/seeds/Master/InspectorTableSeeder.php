<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectorTableSeeder
 */
class InspectorTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create inspector table seeder
         */
        $table_name = 'inspectors';

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
                'name'       => '中田',
                'code'       => '0001',
                'group_id'   => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '前田',
                'code'       => '0002',
                'group_id'   => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉田',
                'code'       => '0003',
                'group_id'   => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山田',
                'code'       => '0004',
                'group_id'   => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤',
                'code'       => '0005',
                'group_id'   => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '斉藤',
                'code'       => '0006',
                'group_id'   => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加藤',
                'code'       => '0007',
                'group_id'   => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '後藤',
                'code'       => '0008',
                'group_id'   => 2,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        /**
         * create inspector process related table seeder
         */
        $table_name = 'inspector_process';

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
                'inspector_id' => 1,
                'process_id'   => 1,
            ],[
                'inspector_id' => 2,
                'process_id'   => 2,
            ],[
                'inspector_id' => 3,
                'process_id'   => 3,
            ],[
                'inspector_id' => 4,
                'process_id'   => 1,
            ],[
                'inspector_id' => 4,
                'process_id'   => 2,
            ],[
                'inspector_id' => 4,
                'process_id'   => 3,
            ],[
                'inspector_id' => 5,
                'process_id'   => 1,
            ],[
                'inspector_id' => 6,
                'process_id'   => 2,
            ],[
                'inspector_id' => 7,
                'process_id'   => 3,
            ],[
                'inspector_id' => 8,
                'process_id'   => 1,
            ],[
                'inspector_id' => 8,
                'process_id'   => 2,
            ],[
                'inspector_id' => 8,
                'process_id'   => 3,
            ]
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}