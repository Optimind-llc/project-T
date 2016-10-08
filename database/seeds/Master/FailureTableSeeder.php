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
                'name'       => 'ショート',
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
                'name'       => '白斑点',
                'sort'       => '8',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '樹脂抜け',
                'sort'       => '9',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ヒケ',
                'sort'       => '10',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上型残り',
                'sort'       => '11',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '下型残り',
                'sort'       => '12',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加工不良',
                'sort'       => '13',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ドライ',
                'sort'       => '14',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リブ欠け',
                'sort'       => '15',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '端末欠け',
                'sort'       => '16',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'バリ',
                'sort'       => '17',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '材質記号NG',
                'sort'       => '18',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '仕上げ不良',
                'sort'       => '19',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴糸残り',
                'sort'       => '20',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤はみ出',
                'sort'       => '21',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤付着',
                'sort'       => '22',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '汚れ',
                'sort'       => '23',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '剥離',
                'sort'       => '24',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト塗料剥',
                'sort'       => '25',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト欠',
                'sort'       => '26',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リテーナー欠',
                'sort'       => '27',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リテーナー誤品',
                'sort'       => '28',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '隙間大',
                'sort'       => '29',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '水漏れ',
                'sort'       => '30',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'sort'       => '99',
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
            //成型
            [
                'failure_id' => 1,
                'process_id' => 'molding',
                'type'       => 1
            ],[
                'failure_id' => 2,
                'process_id' => 'molding',
                'type'       => 1
            ],[
                'failure_id' => 3,
                'process_id' => 'molding',
                'type'       => 1
            ],[
                'failure_id' => 4,
                'process_id' => 'molding',
                'type'       => 1
            ],[
                'failure_id' => 5,
                'process_id' => 'molding',
                'type'       => 1
            ],[
                'failure_id' => 6,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 7,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 8,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 9,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 10,
                'process_id' => 'molding',
                'type'       => 1
            ],[
                'failure_id' => 11,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 12,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 14,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 15,
                'process_id' => 'molding',
                'type'       => 2
            ],[
                'failure_id' => 31,
                'process_id' => 'molding',
                'type'       => 2
            ],
            //穴あけ
            [
                'failure_id' => 1,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 2,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 3,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 4,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 5,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 7,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 13,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 24,
                'process_id' => 'holing',
                'type'       => 2
            ],[
                'failure_id' => 31,
                'process_id' => 'holing',
                'type'       => 2
            ],
            //接着
            [
                'failure_id' => 1,
                'process_id' => 'jointing',
                'type'       => 1
            ],[
                'failure_id' => 2,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 3,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 4,
                'process_id' => 'jointing',
                'type'       => 1
            ],[
                'failure_id' => 6,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 9,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 16,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 17,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 21,
                'process_id' => 'jointing',
                'type'       => 1
            ],[
                'failure_id' => 22,
                'process_id' => 'jointing',
                'type'       => 1
            ],[
                'failure_id' => 23,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 24,
                'process_id' => 'jointing',
                'type'       => 1
            ],[
                'failure_id' => 25,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 26,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 29,
                'process_id' => 'jointing',
                'type'       => 2
            ],[
                'failure_id' => 30,
                'process_id' => 'jointing',
                'type'       => 1
            ],[
                'failure_id' => 31,
                'process_id' => 'jointing',
                'type'       => 2
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}