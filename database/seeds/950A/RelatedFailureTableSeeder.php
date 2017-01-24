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
                'name'       => 'ヒケ',
                'label'      => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '未凍結',
                'label'      => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'シボかすれ',
                'label'      => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '異物混入',
                'label'      => 9,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白斑点',
                'label'      => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '樹脂抜け',
                'label'      => 11,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上型残り',
                'label'      => 12,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '下型残り',
                'label'      => 13,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リブ欠け',
                'label'      => 14,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒斑点',
                'label'      => 15,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '面荒れ',
                'label'      => 16,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ピンホール',
                'label'      => 17,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボイド',
                'label'      => 18,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'バリ',
                'label'      => 19,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加工不良',
                'label'      => 20,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '剥離',
                'label'      => 21,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴位置NG',
                'label'      => 22,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴径小',
                'label'      => 23,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '穴径大',
                'label'      => 24,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '座屈',
                'label'      => 25,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤はみ出',
                'label'      => 26,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤付着',
                'label'      => 27,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リベット座屈',
                'label'      => 28,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '汚れ',
                'label'      => 29,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト塗料剥',
                'label'      => 30,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト欠',
                'label'      => 31,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '端末欠け',
                'label'      => 32,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ワキ',
                'label'      => 33,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ブツ',
                'label'      => 34,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '糸ブツ',
                'label'      => 35,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '肌不良',
                'label'      => 36,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '色ムラ',
                'label'      => 37,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ダスト',
                'label'      => 38,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'タレ',
                'label'      => 39,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'カブリ',
                'label'      => 40,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
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
            ],[
                'ig_id'   => 1,
                'type_id' => 2,
                'type'    => 1,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 3,
                'type'    => 1,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 4,
                'type'    => 1,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 5,
                'type'    => 1,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 6,
                'type'    => 1,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 7,
                'type'    => 1,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 8,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 9,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 10,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 11,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 12,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 13,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 14,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 15,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 16,
                'type'    => 2,
                'sort'    => 1
            ],[
                'ig_id'   => 1,
                'type_id' => 17,
                'type'    => 2,
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