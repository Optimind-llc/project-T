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

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::connection('950A')->table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

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
                'name'       => '伸展防止',
                'label'      => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '交換',
                'label'      => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '再接着',
                'label'      => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直し不要',
                'label'      => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '手直し不可',
                'label'      => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'mt_related';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::connection('950A')->table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        //成型_外観検査_ドアインナ
        $m_gaikan_doorInner_failures = [
            [1,  1], [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'molding',
                'inspection'    => 'gaikan',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($m_gaikan_doorInner_failures), $m_gaikan_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //成型_外観検査_ラゲージインナ
        $m_gaikan_luggageInner_failures = [
            [1,  1], [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'molding',
                'inspection'    => 'gaikan',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($m_gaikan_luggageInner_failures), $m_gaikan_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_外周仕上_ラゲージアウタ
        $j_gaishushiage_luggageOuter_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'gaishushiage',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_gaishushiage_luggageOuter_failures), $j_gaishushiage_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_パテ修復後_ラゲージアウタ
        $j_pateshufukugo_luggageOuter_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'pateshufukugo',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_pateshufukugo_luggageOuter_failures), $j_pateshufukugo_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_水検後_ラゲージアウタ
        $j_suikengo_luggageOuter_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'suikengo',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_suikengo_luggageOuter_failures), $j_suikengo_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);


        //接着_外観検査_ドアASSY
        $j_gaikan_doorASSY_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'gaikan',
                'division'      => 'doorASSY',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_gaikan_doorASSY_failures), $j_gaikan_doorASSY_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //接着_外観検査_ラゲージASSY
        $j_gaikan_luggageASSY_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'gaikan',
                'division'      => 'luggageASSY',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_gaikan_luggageASSY_failures), $j_gaikan_luggageASSY_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //接着_手直_ドアASSY
        $j_tenaoshi_doorASSY_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'tenaoshi',
                'division'      => 'doorASSY',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_tenaoshi_doorASSY_failures), $j_tenaoshi_doorASSY_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //接着_手直_ラゲージASSY
        $j_tenaoshi_luggageASSY_failures = [
            [1,  1], [2, 1], [3,  1], [5,  1], [6, 1], [7, 1], [8, 1],
            [9, 1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'tenaoshi',
                'division'      => 'luggageASSY',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_tenaoshi_luggageASSY_failures), $j_tenaoshi_luggageASSY_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}