<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class FailureTableSeeder
 */
class FailureTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create failure table seeder
         */
        $table_name = 'failures';

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
                'name'       => 'キズ',
                'sort'       => '1',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '凸',
                'sort'       => '2',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '凹',
                'sort'       => '3',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ワレ・ヒビ',
                'sort'       => '4',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ヒケ',
                'sort'       => '5',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'シボかすれ',
                'sort'       => '6',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '異物混入',
                'sort'       => '7',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白斑店',
                'sort'       => '8',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '樹脂抜け',
                'sort'       => '9',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '寸欠',
                'sort'       => '10',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リブ抜け',
                'sort'       => '11',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'バリ残り',
                'sort'       => '12',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '素材判定NG',
                'sort'       => '13',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '外周仕上げNG',
                'sort'       => '14',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴糸残り',
                'sort'       => '15',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤はみ出し',
                'sort'       => '16',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤付着',
                'sort'       => '17',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リテーナー欠',
                'sort'       => '18',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ほにゃらら',
                'sort'       => '19',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'sort'       => '20',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'failure_process';

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
                'failure_id' => 1,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 1,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 1,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 2,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 2,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 2,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 3,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 3,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 3,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 4,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 4,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 4,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 5,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 5,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 5,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 6,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 6,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 6,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 7,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 7,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 7,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 8,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 8,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 8,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 9,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 9,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 9,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 10,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 10,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 10,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 11,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 11,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 11,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 12,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 12,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 12,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 13,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 13,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 13,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 14,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 14,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 14,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 15,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 15,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 15,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 16,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 16,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 16,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 17,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 17,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 17,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 18,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 18,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 18,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 19,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 19,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 19,
                'process_id' => 3,
                'type'       => 1
            ],[
                'failure_id' => 20,
                'process_id' => 1,
                'type'       => 1
            ],[
                'failure_id' => 20,
                'process_id' => 2,
                'type'       => 1
            ],[
                'failure_id' => 20,
                'process_id' => 3,
                'type'       => 1
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}