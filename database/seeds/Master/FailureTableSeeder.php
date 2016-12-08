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
                'label'      => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '凸',
                'label'      => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '凹',
                'label'      => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ワレ・ヒビ',
                'label'      => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ショート',
                'label'      => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'シボかすれ',
                'label'      => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '異物混入',
                'label'      => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白斑点',
                'label'      => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '樹脂抜け',
                'label'      => 9,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ヒケ',
                'label'      => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上型残り',
                'label'      => 11,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '下型残り',
                'label'      => 12,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加工不良',
                'label'      => 13,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ドライ',
                'label'      => 14,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リブ欠け',
                'label'      => 15,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '端末欠け',
                'label'      => 17,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'バリ',
                'label'      => 18,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '材質記号NG',
                'label'      => 19,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '仕上げ不良',
                'label'      => 20,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴糸残り',
                'label'      => 21,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤はみ出',
                'label'      => 22,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤付着',
                'label'      => 23,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '汚れ',
                'label'      => 24,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '剥離',
                'label'      => 25,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト塗料剥',
                'label'      => 26,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト欠',
                'label'      => 27,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リテーナー欠',
                'label'      => 28,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リテーナー誤品',
                'label'      => 29,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '隙間大',
                'label'      => 30,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '水漏れ',
                'label'      => 31,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '未凍結',
                'label'      => 16,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '精度NG',
                'label'      => 32,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴位置NG',
                'label'      => 33,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '肌不良',
                'label'      => 34,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'failure_inspection';

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
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 5,
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 7,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 8,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 10,
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 11,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 12,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 14,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 15,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 32,
                'inspection_id' => 1,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 35,
                'inspection_id' => 1,
                'type'       => 2,
                'sort'       => 1
            ],
            //穴あけ_検査
            [
                'failure_id' => 1,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 5,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 7,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 13,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 34,
                'inspection_id' => 3,
                'type'       => 2,
                'sort'       => 1
            ],
            //穴あけ_外観検査
            [
                'failure_id' => 1,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 5,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 7,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 13,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 10,
                'type'       => 2,
                'sort'       => 1
            ],
            //穴あけ_オフライン手直し検査
            [
                'failure_id' => 1,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 5,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 7,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 13,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 12,
                'type'       => 2,
                'sort'       => 1
            ],

            //接着 止水　
            [
                'failure_id' => 1,
                'inspection_id' => 5,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 5,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 16,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 17,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 21,
                'inspection_id' => 5,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 22,
                'inspection_id' => 5,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 23,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 5,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 25,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 26,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 29,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 30,
                'inspection_id' => 5,
                'type'       => 1,
                'sort'       => 0
            ],[
                'failure_id' => 31,
                'inspection_id' => 5,
                'type'       => 2,
                'sort'       => 1
            ],

            //接着 仕上
            [
                'failure_id' => 1,
                'inspection_id' => 6,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 6,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 16,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 17,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 21,
                'inspection_id' => 6,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 22,
                'inspection_id' => 6,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 23,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 6,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 25,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 26,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 29,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 30,
                'inspection_id' => 6,
                'type'       => 1,
                'sort'       => 0
            ],[
                'failure_id' => 31,
                'inspection_id' => 6,
                'type'       => 2,
                'sort'       => 1
            ],


            //接着 検査
            [
                'failure_id' => 1,
                'inspection_id' => 7,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 7,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 16,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 17,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 21,
                'inspection_id' => 7,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 22,
                'inspection_id' => 7,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 23,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 7,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 25,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 26,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 29,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 30,
                'inspection_id' => 7,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 7,
                'type'       => 2,
                'sort'       => 1
            ],


            //接着 特検
            [
                'failure_id' => 1,
                'inspection_id' => 8,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 8,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 16,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 17,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 21,
                'inspection_id' => 8,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 22,
                'inspection_id' => 8,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 23,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 8,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 25,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 26,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 29,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],
            [
                'failure_id' => 30,
                'inspection_id' => 8,
                'type'       => 1,
                'sort'       => 1
            ],
            [
                'failure_id' => 31,
                'inspection_id' => 8,
                'type'       => 2,
                'sort'       => 1
            ],


            //接着 手直し
            [
                'failure_id' => 1,
                'inspection_id' => 9,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 9,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 16,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 17,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 21,
                'inspection_id' => 9,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 22,
                'inspection_id' => 9,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 23,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 9,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 25,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 26,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 29,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 30,
                'inspection_id' => 9,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 9,
                'type'       => 2,
                'sort'       => 1
            ],
            //接着 簡易CF
            [
                'failure_id' => 1,
                'inspection_id' => 11,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 2,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 3,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 4,
                'inspection_id' => 11,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 6,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 9,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 16,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 17,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 21,
                'inspection_id' => 11,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 22,
                'inspection_id' => 11,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 23,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 24,
                'inspection_id' => 11,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 25,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 26,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 29,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 30,
                'inspection_id' => 11,
                'type'       => 1,
                'sort'       => 1
            ],[
                'failure_id' => 31,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 33,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],[
                'failure_id' => 34,
                'inspection_id' => 11,
                'type'       => 2,
                'sort'       => 1
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}