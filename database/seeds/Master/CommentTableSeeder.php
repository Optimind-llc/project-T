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
        $table_name = 'modifications';
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
                'name'       => '削り',
                'label'      => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '除去',
                'label'      => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '樹脂盛り',
                'label'      => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '交換',
                'label'      => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '再接着',
                'label'      => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '水漏れ',
                'label'      => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直し不要',
                'label'      => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直不可',
                'label'      => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ぼかし',
                'label'      => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '保留',
                'label'      => 9,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'modification_inspection';

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
            //止水
            [
                'modification_id' => 1,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 2,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 3,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 4,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 5,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 7,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 8,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 9,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 10,
                'inspection_id'   => 5,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 11,
                'inspection_id'   => 5,
                'type'            => 1,
                'sort'            => 1
            ],
            //仕上
            [
                'modification_id' => 1,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 2,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 3,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 4,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 5,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 7,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 8,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 9,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 10,
                'inspection_id'   => 6,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 11,
                'inspection_id'   => 6,
                'type'            => 1,
                'sort'            => 1
            ],
            //検査
            [
                'modification_id' => 1,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 2,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 3,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 4,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 5,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 7,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 8,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 9,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 10,
                'inspection_id'   => 7,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 11,
                'inspection_id'   => 7,
                'type'            => 1,
                'sort'            => 1
            ],
            //特検
            [
                'modification_id' => 1,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 2,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 3,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 4,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 5,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 7,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 8,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 9,
                'inspection_id'   => 8,
                'type'            => 2,
                'sort'            => 1
            ],
            //手直し
            [
                'modification_id' => 1,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 2,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 3,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 4,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 5,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 7,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 8,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 9,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 10,
                'inspection_id'   => 9,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 11,
                'inspection_id'   => 9,
                'type'            => 1,
                'sort'            => 1
            ],
            // 簡易CF
            [
                'modification_id' => 1,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 2,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 3,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 4,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 5,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 7,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 8,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 9,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 10,
                'inspection_id'   => 11,
                'type'            => 2,
                'sort'            => 1
            ],[
                'modification_id' => 11,
                'inspection_id'   => 11,
                'type'            => 1,
                'sort'            => 1
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}