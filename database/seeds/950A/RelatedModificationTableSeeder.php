<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class FailureTableSeeder
 */
class RelatedModificationTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create failure table seeder
         */
        $table_name = 'modification_types';

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

        DB::connection('950A')->table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'mt_ig';

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