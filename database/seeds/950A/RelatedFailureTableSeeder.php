<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class FailureTableSeeder
 */
class RelatedFailureTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create failure table seeder
         */
        $table_name = 'failure_types';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
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

        DB::connection('950A')->table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'ft_ig';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        }

        $data = [
            //成型_外観検査_ドア
            [
                'ig_id'   => 1,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //穴あけ_外観検査_ドア
            [
                'ig_id'   => 3,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //穴あけ_穴検査_ドア
            [
                'ig_id'   => 4,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_かしめ後検査_ドア
            [
                'ig_id'   => 5,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_止水_ドア
            [
                'ig_id'   => 10,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_外観検査_ドア
            [
                'ig_id'   => 12,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_手直_ドア
            [
                'ig_id'   => 13,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],


            //成型_外観検査_ラゲージ
            [
                'ig_id'   => 14,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //穴あけ_外観検査_ラゲージ
            [
                'ig_id'   => 16,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //穴あけ_穴検査_ラゲージ
            [
                'ig_id'   => 17,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_かしめ後検査_ラゲージ
            [
                'ig_id'   => 18,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_外周仕上_ラゲージ
            [
                'ig_id'   => 19,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_パテ修復後_ラゲージ
            [
                'ig_id'   => 20,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_水検後_ラゲージ
            [
                'ig_id'   => 21,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_塗装受入後_ラゲージ
            [
                'ig_id'   => 22,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_外観検査_ラゲージ
            [
                'ig_id'   => 25,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],

            //かしめ/接着_手直_ラゲージ
            [
                'ig_id'   => 26,
                'type_id' => 1,
                'type'    => 1,
                'sort'    => 1
            ],
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}