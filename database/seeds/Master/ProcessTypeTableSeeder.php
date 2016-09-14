<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class ProcessTypeTableSeeder
 */
class ProcessTypeTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'process_types';
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
                'name'       => '成型工程１',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '成型工程２',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴あけ工程',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着工程１',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着工程２',
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