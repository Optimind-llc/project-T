<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DivisionTableSeeder
 */
class ProcessTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $table_name = 'processes';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            [
                'name'       => '成型',
                'id'         => 'molding',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴あけ',
                'id'         => 'holing',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着',
                'id'         => 'jointing',
                'sort'       => 3,
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