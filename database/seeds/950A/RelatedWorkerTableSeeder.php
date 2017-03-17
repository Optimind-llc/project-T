<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectorTableSeeder
 */
class RelatedWorkerTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /*
         * Create choku table seeder
         */
        $table_name = 'chokus';

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
                'name'       => '黄直',
                'code'       => 'Y',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白直',
                'code'       => 'W',
                'status'     => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒直',
                'code'       => 'B',
                'status'     => 0,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /*
         * Create worker table seeder
         */
        $table_name = 'workers';

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
                'name'       => '田村 良二',
                'yomi'       => 'タムラリョウジ',
                'code'       => '0001',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高木 洋一',
                'yomi'       => 'タカギヨウイチ',
                'code'       => '0002',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山本 佳祐',
                'yomi'       => 'ヤマシタケイスケ',
                'code'       => '0003',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '森下 和哉',
                'yomi'       => 'モリシタカズヤ',
                'code'       => '0004',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浅田 英幸',
                'yomi'       => 'アサダヒデユキ',
                'code'       => '0005',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '青木 匠',
                'yomi'       => 'アオキタクミ',
                'code'       => '0006',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '谷口 勇人',
                'yomi'       => 'タニグチ',
                'code'       => '0007',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '矢澤 鉱一',
                'yomi'       => 'ヤザワコウイチ',
                'code'       => '0008',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大園 博美',
                'yomi'       => 'オオゾノヒロミ',
                'code'       => '0009',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川畑 英義',
                'yomi'       => 'カワバタヒデヨシ',
                'code'       => '0010',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '金谷 達弘',
                'yomi'       => 'カナヤタツヒロ',
                'code'       => '0011',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '阿部 哲士',
                'yomi'       => 'アベテツシ',
                'code'       => '0012',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒崎 将平',
                'yomi'       => 'クロサキ ショウヘイ',
                'code'       => '0013',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小川 晟央',
                'yomi'       => 'オガワマサオ',
                'code'       => '0014',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '古井 康真',
                'yomi'       => 'フルイヤスシ',
                'code'       => '0015',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大塚 絢貴',
                'yomi'       => 'オオツカアヤタカ',
                'code'       => '0016',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '濱田 政雄',
                'yomi'       => 'ハマダマサオ',
                'code'       => '0017',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岡本 崇弘',
                'yomi'       => 'オカモトタカヒロ',
                'code'       => '0018',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '戸上 憲一',
                'yomi'       => 'トガミケンイチ',
                'code'       => '0019',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西嶋 慎吾',
                'yomi'       => 'ニシジマシンゴ',
                'code'       => '0020',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '橋本 高浩',
                'yomi'       => 'ハシモトタカヒロ',
                'code'       => '0021',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西川 佳孝',
                'yomi'       => 'ニシカワヨシタカ',
                'code'       => '0022',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '嶋津 直樹',
                'yomi'       => 'シマズナオキ',
                'code'       => '0023',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '梅田 雄司',
                'yomi'       => 'ウメダユウジ',
                'code'       => '0024',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山尾 祐介',
                'yomi'       => 'ヤマオユウスケ',
                'code'       => '0025',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '白波瀬 忍',
                'yomi'       => 'シロナミセシノブ',
                'code'       => '0026',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中田 将吾',
                'yomi'       => 'ナカダショウゴ',
                'code'       => '0027',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '井坂 光雄',
                'yomi'       => 'イサカミツオ',
                'code'       => '0028',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大寺 真悟',
                'yomi'       => 'オオデラシンゴ',
                'code'       => '0029',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大谷 直人',
                'yomi'       => 'オオタニナオト',
                'code'       => '0030',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '児嶋 誠',
                'yomi'       => 'コジママコト',
                'code'       => '0031',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉武 伸浩',
                'yomi'       => 'ヨシタケノブヒロ',
                'code'       => '0032',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加藤 徹',
                'yomi'       => 'カトウトオル',
                'code'       => '0033',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '瀬川 瑛志',
                'yomi'       => 'セガワエイシ',
                'code'       => '0034',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '宗像 徹郎',
                'yomi'       => 'ムナカタテツロウ',
                'code'       => '0035',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '田村 豊',
                'yomi'       => 'タムラユタカ',
                'code'       => '0036',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '伊藤 伸一',
                'yomi'       => 'イトウシンイチ',
                'code'       => '0037',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中本 直樹',
                'yomi'       => 'ナカモトナオキ',
                'code'       => '0038',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '尾前 裕也',
                'yomi'       => 'オマエユウヤ',
                'code'       => '0039',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '蘭 龍二',
                'yomi'       => 'ランリュウジ',
                'code'       => '0040',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '渡辺 孝司',
                'yomi'       => 'ワタナベコウジ',
                'code'       => '0041',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大石 峰志',
                'yomi'       => 'オオイシタカシ',
                'code'       => '0042',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '土居 豊',
                'yomi'       => 'ツチイ ユタカ',
                'code'       => '0043',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '龍田 憲太',
                'yomi'       => 'タツタケンタ',
                'code'       => '0044',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小林 徹也',
                'yomi'       => 'コバヤシテツヤ',
                'code'       => '0045',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '渡久地 政通',
                'yomi'       => 'トグチマサトシ',
                'code'       => '0046',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浅野 秀明',
                'yomi'       => 'アサノヒデアキ',
                'code'       => '0047',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '蜂谷 拓也',
                'yomi'       => 'ハチヤタクヤ',
                'code'       => '0048',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '瀬畑 長正',
                'yomi'       => 'セバタナガマサ',
                'code'       => '0049',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '長畑 学',
                'yomi'       => 'ナガハタマナブ',
                'code'       => '0050',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉川 慎吾',
                'yomi'       => 'ヨシカワ シンゴ',
                'code'       => '0051',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '東野 博',
                'yomi'       => 'ヒガシノヒロシ',
                'code'       => '0052',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '藤川 徹',
                'yomi'       => 'フジカワトオル',
                'code'       => '0053',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '萩野 隆',
                'yomi'       => 'ハギノタカシ',
                'code'       => '0054',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '安田 康平',
                'yomi'       => 'ヤスダコウヘイ',
                'code'       => '0055',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /**
         * create inspector process related table seeder
         */
        $table_name = 'worker_related';

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
            //成型_外観検査_ドア_白
            [
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 1,
                'sort'      => 1
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 2,
                'sort'      => 2
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 3,
                'sort'      => 3
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 44,
                'sort'      => 4
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 5,
                'sort'      => 5
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 6,
                'sort'      => 6
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 7,
                'sort'      => 7
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 8,
                'sort'      => 8
            ],
            //成型_外観検査_ドア_黄
            [
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 9,
                'sort'      => 1
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 10,
                'sort'      => 2
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 11,
                'sort'      => 3
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 12,
                'sort'      => 4
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 13,
                'sort'      => 5
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 14,
                'sort'      => 6
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 15,
                'sort'      => 7
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 16,
                'sort'      => 8
            ],


            //穴あけ_塗装前外観検査_ドア_白
            [
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_外観検査_ドア_黄
            [
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'door',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //穴あけ_塗装前外観検査_ドア_白
            [
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_外観検査_ドア_黄
            [
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'door',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //穴あけ_穴検査_ドア_白
            [
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_穴検査_ドア_黄
            [
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'door',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //穴あけ_穴検査_ドア_白
            [
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_穴検査_ドア_黄
            [
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //かしめ/接着_かしめ後検査_ドア_白
            [
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 30,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 31,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 32,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 33,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 34,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 35,
                'sort'      => 6
            ],
            //かしめ/接着_かしめ後検査_ドア_黄
            [
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 36,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 37,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 38,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 39,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 40,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 41,
                'sort'      => 6
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'door',
                'worker_id' => 42,
                'sort'      => 7
            ],

            //かしめ/接着_外観検査_ドア_白
            [
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 30,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 31,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 32,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 33,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 34,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 35,
                'sort'      => 6
            ],
            //かしめ/接着_外観検査_ドア_黄
            [
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 36,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 37,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 38,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 39,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 40,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 41,
                'sort'      => 6
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'door',
                'worker_id' => 42,
                'sort'      => 7
            ],

            //かしめ/接着_手直_ドア_白
            [
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 30,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 31,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 32,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 33,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 34,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 35,
                'sort'      => 6
            ],
            //かしめ/接着_手直_ドア_黄
            [
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 36,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 37,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 38,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 39,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 40,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 41,
                'sort'      => 6
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'door',
                'worker_id' => 42,
                'sort'      => 7
            ],



            //成型_外観検査_ラゲージ_白
            [
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 1,
                'sort'      => 1
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 2,
                'sort'      => 2
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 3,
                'sort'      => 3
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 4
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 5,
                'sort'      => 5
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 6,
                'sort'      => 6
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 7,
                'sort'      => 7
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 8,
                'sort'      => 8
            ],
            //成型_外観検査_ラゲージ_黄
            [
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 9,
                'sort'      => 1
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 10,
                'sort'      => 2
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 11,
                'sort'      => 3
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 12,
                'sort'      => 4
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 13,
                'sort'      => 5
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 14,
                'sort'      => 6
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 15,
                'sort'      => 7
            ],[
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 16,
                'sort'      => 8
            ],


            //穴あけ_洗浄前外観検査_ラゲージ_白
            [
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_洗浄前外観検査_ラゲージ_黄
            [
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'division'   => 'luggage',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //穴あけ_洗浄後外観検査_ラゲージ_白
            [
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_洗浄後外観検査_ラゲージ_黄
            [
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'division'   => 'luggage',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //穴あけ_穴検査_ラゲージ_白
            [
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_穴検査_ラゲージ_黄
            [
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'ana',
                'division'   => 'luggage',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //穴あけ_手直検査_ラゲージ_白
            [
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 17,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 18,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 19,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 20,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 21,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 22,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 23,
                'sort'      => 7
            ],
            //穴あけ_穴検査_ラゲージ_黄
            [
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 16,
                'sort'      => 1
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 24,
                'sort'      => 2
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 25,
                'sort'      => 3
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 26,
                'sort'      => 4
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 27,
                'sort'      => 5
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 28,
                'sort'      => 6
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 29,
                'sort'      => 7
            ],[
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 55,
                'sort'      => 8
            ],

            //かしめ/接着_かしめ後検査_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 30,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 31,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 32,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 33,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 34,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 35,
                'sort'      => 6
            ],
            //かしめ/接着_かしめ後検査_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 36,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 37,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 38,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 39,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 40,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 41,
                'sort'      => 6
            ],[
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'division'   => 'luggage',
                'worker_id' => 42,
                'sort'      => 7
            ],

            //かしめ/接着_外周仕上_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 43,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 45,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 46,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 47,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 48,
                'sort'      => 6
            ],
            //かしめ/接着_外周仕上_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 49,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 50,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 51,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 52,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 53,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'division'   => 'luggage',
                'worker_id' => 54,
                'sort'      => 6
            ],

            //かしめ/接着_パテ修復後_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 43,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 45,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 46,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 47,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 48,
                'sort'      => 6
            ],
            //かしめ/接着_パテ修復後_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 49,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 50,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 51,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 52,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 53,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'division'   => 'luggage',
                'worker_id' => 54,
                'sort'      => 6
            ],

            //かしめ/接着_水検後_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 43,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 45,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 46,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 47,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 48,
                'sort'      => 6
            ],
            //かしめ/接着_水検後_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 49,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 50,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 51,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 52,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 53,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'division'   => 'luggage',
                'worker_id' => 54,
                'sort'      => 6
            ],

            //かしめ/接着_塗装受入後_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 43,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 45,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 46,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 47,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 48,
                'sort'      => 6
            ],
            //かしめ/接着_塗装受入後_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 49,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 50,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 51,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 52,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 53,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'division'   => 'luggage',
                'worker_id' => 54,
                'sort'      => 6
            ],


            //かしめ/接着_外観検査_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 43,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 45,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 46,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 47,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 48,
                'sort'      => 6
            ],
            //かしめ/接着_外観検査_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 49,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 50,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 51,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 52,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 53,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'division'   => 'luggage',
                'worker_id' => 54,
                'sort'      => 6
            ],

            //かしめ/接着_手直_ラゲージ_白
            [
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 43,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 44,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 45,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 46,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 47,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 48,
                'sort'      => 6
            ],
            //かしめ/接着_手直_ラゲージ_黄
            [
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 49,
                'sort'      => 1
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 50,
                'sort'      => 2
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 51,
                'sort'      => 3
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 52,
                'sort'      => 4
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 53,
                'sort'      => 5
            ],[
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'division'   => 'luggage',
                'worker_id' => 54,
                'sort'      => 6
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}