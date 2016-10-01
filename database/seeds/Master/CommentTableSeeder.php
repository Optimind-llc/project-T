<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CommentTableSeeder
 */
class CommentTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'comments';
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
                'message'       => '削り',
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '除去',
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '樹脂盛り',
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '交換',
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '再接着',
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => 'その他',
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}