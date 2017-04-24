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
            ],[
                'name'       => '水漏れ',
                'label'      => 41,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'シワ',
                'label'      => 42,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '波打ち',
                'label'      => 43,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'カラー座屈',
                'label'      => 44,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'カラー欠',
                'label'      => 45,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'リベット欠',
                'label'      => 46,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ナット欠',
                'label'      => 47,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ボルト焼付き',
                'label'      => 48,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'BKT欠',
                'label'      => 49,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ナット座屈',
                'label'      => 50,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '接着剤不足',
                'label'      => 51,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'ハジキ',
                'label'      => 52,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'スケ',
                'label'      => 53,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /*
         * create failure related table seeder
         */
        $table_name = 'ft_related';

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
            [1,  1], [2,  1], [3,  1], [4,  1], [5,  1], [6,  1], [7,  1],
            [8,  2], [9,  2], [10, 2], [11, 2], [12, 2], [13, 2], [14, 2],
            [15, 2], [16, 2], [41, 2]
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

        //成型_外観検査_リンフォース
        $m_gaikan_reinforce_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [5,  1], [6,  1], [7,  1],
            [8,  2], [9,  2], [10, 2], [11, 2], [12, 2], [13, 2], [14, 2],
            [15, 2], [16, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'molding',
                'inspection'    => 'gaikan',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($m_gaikan_reinforce_failures), $m_gaikan_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);


        //成型_外観検査_ラゲージインナ
        $m_gaikan_luggageInner_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [5,  1], [6,  1], [7,  1],
            [8,  2], [9,  2], [10, 2], [11, 2], [12, 2], [13, 2], [14, 2],
            [15, 2], [16, 2], [43, 2], [41, 2]
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

        //成型_外観検査_ラゲージアウタ
        $m_gaikan_luggageOuter_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [5,  1], [6,  1], [7,  1],
            [8,  2], [9,  2], [10, 2], [11, 2], [12, 2], [13, 2], [14, 2],
            [15, 2], [16, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'molding',
                'inspection'    => 'gaikan',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($m_gaikan_luggageOuter_failures), $m_gaikan_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        /*
         * 　穴あけ工程
         */

        //穴あけ_洗浄前外観検査_ドアインナ
        $h_gaikan_doorInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [18, 2], [44, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'maegaikan',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_doorInner_failures), $h_gaikan_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_洗浄前外観検査_リンフォース
        $h_gaikan_reinforce_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'maegaikan',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_reinforce_failures), $h_gaikan_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_洗浄前外観検査_ラゲージインナ
        $h_gaikan_luggageInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'maegaikan',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_luggageInner_failures), $h_gaikan_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_洗浄前外観検査_ラゲージアウタ
        $h_gaikan_luggageOuter_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'maegaikan',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_luggageOuter_failures), $h_gaikan_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);




        //穴あけ_洗浄後外観検査_ドアインナ
        $h_gaikan_doorInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [18, 2], [44, 2], [10, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'atogaikan',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_doorInner_failures), $h_gaikan_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_洗浄後外観検査_リンフォース
        $h_gaikan_reinforce_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'atogaikan',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_reinforce_failures), $h_gaikan_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_洗浄後外観検査_ラゲージインナ
        $h_gaikan_luggageInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'atogaikan',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_luggageInner_failures), $h_gaikan_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_洗浄後外観検査_ラゲージアウタ
        $h_gaikan_luggageOuter_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [9,  2],
            [20, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'atogaikan',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_gaikan_luggageOuter_failures), $h_gaikan_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);


        //穴あけ_穴検査_ドアインナ
        $h_ana_doorInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23,  2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [18, 2], [44,  2],
            [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_doorInner_failures), $h_ana_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_穴検査_リンフォース
        $h_ana_reinforce_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23, 2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_reinforce_failures), $h_ana_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_穴検査_ラゲージインナ
        $h_ana_luggageInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23, 2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_luggageInner_failures), $h_ana_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_穴検査_ラゲージアウタ
        $h_ana_luggageOuter_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23,  2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_luggageOuter_failures), $h_ana_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_手直検査_ドアインナ
        $h_tenaoshi_doorInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23,  2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [18, 2], [44,  2],
            [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'tenaoshi',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_tenaoshi_doorInner_failures), $h_tenaoshi_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_手直検査_リンフォース
        $h_tenaoshi_reinforce_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23, 2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'tenaoshi',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_tenaoshi_reinforce_failures), $h_tenaoshi_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_手直検査_ラゲージインナ
        $h_tenaoshi_luggageInner_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23, 2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [18, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'tenaoshi',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_tenaoshi_luggageInner_failures), $h_tenaoshi_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_手直検査_ラゲージアウタ
        $h_tenaoshi_luggageOuter_failures = [
            [1,  2], [2,  2], [3,  2], [4,  2], [5,  2], [19, 2], [23, 2],
            [24, 2], [9,  2], [20, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'tenaoshi',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_tenaoshi_luggageOuter_failures), $h_tenaoshi_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        /*
         * 　かしめ工程
         */

        //かしめ_かしめ後検査_ドアインナ
        $j_kashimego_doorInner_failures = [
            [1,  2], [4,  2], [25, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_doorInner_failures), $j_kashimego_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_かしめ後検査_リンフォース
        $j_kashimego_reinforce_failures = [
            [1,  2], [4,  2], [25, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_reinforce_failures), $j_kashimego_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_かしめ後検査_ラゲージインナ
        $j_kashimego_luggageInner_failures = [
            [1,  2], [4,  2], [25, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_luggageInner_failures), $j_kashimego_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_かしめ後検査_ラゲージアウタ
        $j_kashimego_luggageOuter_failures = [
            [1,  2], [4,  2], [25, 2], [21, 2], [22, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_luggageOuter_failures), $j_kashimego_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ_外周仕上_ラゲージアウタ
        $j_gaishushiage_luggageOuter_failures = [
            [1,  2], [4,  2], [21, 2], [2,  2], [3,  2], [11, 2],
            [32, 2], [19, 2], [29, 2], [17, 2], [18, 2], [41, 2]
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
            [1,  2], [4,  2], [21, 2], [2,  2], [3,  2], [11, 2], [32, 2],
            [19, 2], [29, 2], [17, 2], [18, 2], [9,  2], [16, 2], [41, 2]
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
            [1,  2], [4,  2], [21, 2], [2,  2], [3,  2], [11, 2],
            [32, 2], [19, 2], [29, 2], [17, 2], [18, 2], [41, 2]
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

        //かしめ_塗装受入後_ラゲージアウタ
        $j_tosoukeirego_luggageOuter_failures = [
            [33, 2], [34, 2], [35, 2], [1,  2], [4,  2], [3,  2], [36, 2],
            [37, 2], [38, 2], [39, 2], [40, 2], [53, 2], [17, 2], [54, 2],
            [2,  2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'tosoukeirego',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_tosoukeirego_luggageOuter_failures), $j_tosoukeirego_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //接着_接着後_ドアASSY
        $j_setchakugo_doorASSY_failures = [
            [1,  2], [4,  2], [26, 2], [27, 2], [21, 2], [28, 2], [3,  2],
            [2,  2], [8,  2], [11, 2], [32, 2], [19, 2], [29, 2], [30, 2],
            [31, 2], [45, 2], [52, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'setchakugo',
                'division'      => 'doorASSY',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_setchakugo_doorASSY_failures), $j_setchakugo_doorASSY_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //接着_接着後_ラゲージASSY
        $j_setchakugo_luggageASSY_failures = [
            [42, 2], [1,  2], [4,  2], [26, 2], [27, 2], [21, 2], [3,  2],
            [2,  2], [8,  2], [32, 2], [19, 2], [29, 2], [30, 2], [31, 2],
            [49, 2], [50, 2], [45, 2], [51, 2], [52, 2], [41, 2]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'setchakugo',
                'division'      => 'luggageASSY',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_setchakugo_luggageASSY_failures), $j_setchakugo_luggageASSY_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //接着_外観検査_ドアASSY
        $j_gaikan_doorASSY_failures = [
            [1,  2], [4,  2], [26, 2], [27, 2], [21, 2], [28, 2], [3,  2],
            [2,  2], [8,  2], [11, 2], [32, 2], [19, 2], [29, 2], [30, 2],
            [31, 2], [45, 2], [52, 2], [41, 2]
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
            [42, 2], [1,  2], [4,  2], [26, 2], [27, 2], [21, 2], [3,  2],
            [2,  2], [8,  2], [32, 2], [19, 2], [29, 2], [30, 2], [31, 2],
            [49, 2], [50, 2], [45, 2], [51, 2], [52, 2], [41, 2]
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
            [1,  2], [4,  2], [26, 2], [27, 2], [21, 2], [28, 2], [3,  2],
            [2,  2], [8,  2], [11, 2], [32, 2], [19, 2], [29, 2], [30, 2],
            [31, 2], [45, 2], [46, 2], [47, 2], [48, 2], [52, 2], [41, 2]
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
            [42, 2], [1,  2], [4,  2], [26, 2], [27, 2], [21, 2], [3,  2],
            [2,  2], [8,  2], [32, 2], [19, 2], [29, 2], [30, 2], [31, 2],
            [49, 2], [50, 2], [45, 2], [51, 2], [52, 2], [41, 2]
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