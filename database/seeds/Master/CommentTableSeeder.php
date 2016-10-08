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
            //仕上げ
            [
                'message'       => '削り',
                'sort'          => 1,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '除去',
                'sort'          => 2,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '樹脂盛り',
                'sort'          => 3,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '交換',
                'sort'          => 4,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '再接着',
                'sort'          => 5,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '水漏れ',
                'sort'          => 6,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '手直し不要',
                'sort'          => 7,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => 'その他',
                'sort'          => 8,
                'inspection_id' => 6,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            //手直し
            [
                'message'       => '削り',
                'sort'          => 1,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '除去',
                'sort'          => 2,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '樹脂盛り',
                'sort'          => 3,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '交換',
                'sort'          => 4,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '再接着',
                'sort'          => 5,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '水漏れ',
                'sort'          => 6,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => '手直し不要',
                'sort'          => 7,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'message'       => 'その他',
                'sort'          => 8,
                'inspection_id' => 9,
                'created_at'    => $now,
                'updated_at'    => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}