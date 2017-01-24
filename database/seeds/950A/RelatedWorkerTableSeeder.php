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

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        }

        $data = [
            // 成型　白直
            [
                'name'       => '後藤 芳光',
                'yomi'       => 'ゴトウ',
                'code'       => '0001',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 貴紫',
                'yomi'       => 'スズキ',
                'code'       => '0002',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 常喜',
                'yomi'       => 'スズキ',
                'code'       => '0003',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤 優輝',
                'yomi'       => 'サトウ',
                'code'       => '0004',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '北村 凌太郎',
                'yomi'       => 'キタムラ',
                'code'       => '0005',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上松 健太',
                'yomi'       => 'ウエマツ',
                'code'       => '0006',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '磯貝 寿樹',
                'yomi'       => 'イソガイ',
                'code'       => '0007',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '原田 真一',
                'yomi'       => 'ハラダ',
                'code'       => '0008',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '出口 政孝',
                'yomi'       => 'デグチ',
                'code'       => '0009',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大森 知明',
                'yomi'       => 'オオモリ',
                'code'       => '0010',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '堀内 翔悟',
                'yomi'       => 'ホリウチ',
                'code'       => '0011',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門田 洸樹',
                'yomi'       => 'カドタ',
                'code'       => '0012',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '早坂 孝道',
                'yomi'       => 'ハヤサカ',
                'code'       => '0013',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 成型　黄直
            [
                'name'       => '畑中 武太蔵',
                'yomi'       => 'ハタナカ',
                'code'       => '0014',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉田 健作',
                'yomi'       => 'ヨシダ',
                'code'       => '0015',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '八木 恵司',
                'yomi'       => 'ヤギ',
                'code'       => '0016',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岩本 大和',
                'yomi'       => 'イワモト',
                'code'       => '0017',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門倉 潤平',
                'yomi'       => 'カドクラ',
                'code'       => '0018',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川村 博康',
                'yomi'       => 'カワムラ',
                'code'       => '0019',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西村 乃武',
                'yomi'       => 'ニシムラ',
                'code'       => '0020',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '木里 裕也',
                'yomi'       => 'キザト',
                'code'       => '0021',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '下釜 千春',
                'yomi'       => 'シモガマ',
                'code'       => '0022',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '奥村 康平',
                'yomi'       => 'オクムラ',
                'code'       => '0023',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '板垣 孝輔',
                'yomi'       => 'イタガキ',
                'code'       => '0024',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川瀬 悠',
                'yomi'       => 'カワセ',
                'code'       => '0025',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中井 達也',
                'yomi'       => 'ナカイ',
                'code'       => '0026',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 成型　黒直
            [
                'name'       => '古川 達也',
                'yomi'       => 'フルカワ',
                'code'       => '0027',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒岩 良',
                'yomi'       => 'クロイワ',
                'code'       => '0028',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒川 航平',
                'yomi'       => 'クロカワ',
                'code'       => '0029',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '藤原 拓海',
                'yomi'       => 'フジワラ',
                'code'       => '0030',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉田 諒佑',
                'yomi'       => 'ヨシダ',
                'code'       => '0031',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '一色 誠司',
                'yomi'       => 'イッシキ',
                'code'       => '0032',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '新田 和範',
                'yomi'       => 'ニッタ',
                'code'       => '0033',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岡元 和浩',
                'yomi'       => 'オカモト',
                'code'       => '0034',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '坂本 一幸',
                'yomi'       => 'サカモト',
                'code'       => '0035',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '赤城 大樹',
                'yomi'       => 'アカギ',
                'code'       => '0036',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '河村 将光',
                'yomi'       => 'カワムラ',
                'code'       => '0037',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '永元 保隆',
                'yomi'       => 'ナガモト',
                'code'       => '0038',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大山 徳仁',
                'yomi'       => 'オオヤマ',
                'code'       => '0039',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '宗像 徹郎',
                'yomi'       => 'ムネカタ',
                'code'       => '0040',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],

            //穴あけ工程　白直
            [
                'name'       => '今野 良紀',
                'yomi'       => 'コンノ',
                'code'       => '0041',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '迫田 忠信',
                'yomi'       => 'サコタ',
                'code'       => '0042',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '稲本 竜士',
                'yomi'       => 'イナモト',
                'code'       => '0043',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門間 洸樹',
                'yomi'       => 'カドマ',
                'code'       => '0044',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '阿部 均',
                'yomi'       => 'アベ',
                'code'       => '0045',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 藍斗',
                'yomi'       => 'スズキ',
                'code'       => '0046',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小田代 大介',
                'yomi'       => 'コタダイ',
                'code'       => '0047',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '里園 友基',
                'yomi'       => 'サトゾノ',
                'code'       => '0048',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '水上 貢',
                'yomi'       => 'ミナカミ',
                'code'       => '0049',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中村 亮介',
                'yomi'       => 'ナカムラ',
                'code'       => '0050',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ工程　黄直
            [
                'name'       => '堀口 幸一',
                'yomi'       => 'ホリグチ',
                'code'       => '0051',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '末信 高弘',
                'yomi'       => 'スエノブ',
                'code'       => '0052',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '梶川 潤一郎',
                'yomi'       => 'カジカワ',
                'code'       => '0053',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '嶺山 仁志',
                'yomi'       => 'ミネヤマ',
                'code'       => '0054',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '田中 翔',
                'yomi'       => 'タナカ',
                'code'       => '0055',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤 恭一',
                'yomi'       => 'サトウ',
                'code'       => '0056',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小渕 賢一',
                'yomi'       => 'オブチ',
                'code'       => '0057',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山田 和生',
                'yomi'       => 'ヤマダ',
                'code'       => '0058',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高橋 広幹',
                'yomi'       => 'タカハシ',
                'code'       => '0059',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西脇 宙',
                'yomi'       => 'ニシワキ',
                'code'       => '0060',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山下 一男',
                'yomi'       => 'ヤマシタ',
                'code'       => '0061',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ工程　黒直
            [
                'name'       => '山内 高寛',
                'yomi'       => 'ヤマウチ',
                'code'       => '0062',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '波多野 充希',
                'yomi'       => 'ハタノ',
                'code'       => '0063',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上田 龍太',
                'yomi'       => 'ウエダ',
                'code'       => '0064',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '竹下 博城',
                'yomi'       => 'タケシタ',
                'code'       => '0065',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '内田 敏和',
                'yomi'       => 'ウチダ',
                'code'       => '0066',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '石橋 卓昇',
                'yomi'       => 'イシバシ',
                'code'       => '0067',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松山 亮太',
                'yomi'       => 'マツヤマ',
                'code'       => '0068',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門馬 弘尚',
                'yomi'       => 'モンバ',
                'code'       => '0069',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '服部 智之',
                'yomi'       => 'ハットリ',
                'code'       => '0070',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '早川 正博',
                'yomi'       => 'ハヤカワ',
                'code'       => '0071',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岩永 秀道',
                'yomi'       => 'イワナガ',
                'code'       => '0072',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],

            // 接着工程　白直
            [
                'name'       => '山本 雄策',
                'yomi'       => 'ヤマモト',
                'code'       => '0073',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浦丸 弘喜',
                'yomi'       => 'ウラマル',
                'code'       => '0074',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '古田 宏樹',
                'yomi'       => 'フルタ',
                'code'       => '0075',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加々良 豊',
                'yomi'       => 'カガラ',
                'code'       => '0076',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松井 規浩',
                'yomi'       => 'マツイ',
                'code'       => '0077',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '平野 篤史',
                'yomi'       => 'ヒラノ',
                'code'       => '0078',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '市川 俊之',
                'yomi'       => 'イチカワ',
                'code'       => '0079',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '菊池 幸司',
                'yomi'       => 'キクチ',
                'code'       => '0080',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '久野 力',
                'yomi'       => 'ヒサノ',
                'code'       => '0081',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松田 勝三',
                'yomi'       => 'マツダ',
                'code'       => '0082',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '竹内 匡之',
                'yomi'       => 'タケウチ',
                'code'       => '0083',
                'choku_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 接着工程　黄直
            [
                'name'       => '河田 翼',
                'yomi'       => 'カワタ',
                'code'       => '0084',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '廣政 大輔',
                'yomi'       => 'ヒロマサ',
                'code'       => '0085',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山中 英司',
                'yomi'       => 'ヤマナカ',
                'code'       => '0086',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '告吉 貴史',
                'yomi'       => 'ツゲヨシ',
                'code'       => '0087',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中田 浩二',
                'yomi'       => 'ナカタ',
                'code'       => '0088',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '村山 貴志',
                'yomi'       => 'ムラヤマ',
                'code'       => '0089',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '近松 伸一',
                'yomi'       => 'チカマツ',
                'code'       => '0090',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '横田 和宣',
                'yomi'       => 'ヨコタ',
                'code'       => '0091',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小笠原 拓哉',
                'yomi'       => 'オガサワラ',
                'code'       => '0092',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '森山 和江',
                'yomi'       => 'モリヤマ',
                'code'       => '0093',
                'choku_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 接着工程　黒直
            [
                'name'       => '奥口 朋哉',
                'yomi'       => 'オクグチ',
                'code'       => '0094',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '青山 政光',
                'yomi'       => 'アオヤマ',
                'code'       => '0095',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '真塩 隆英',
                'yomi'       => 'マジオ',
                'code'       => '0096',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '平野 隆',
                'yomi'       => 'ヒラノ',
                'code'       => '0097',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '永田 優',
                'yomi'       => 'ナガタ',
                'code'       => '0098',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '盛川 賢一',
                'yomi'       => 'モリカワ',
                'code'       => '0099',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鍛冶屋 仁',
                'yomi'       => 'カジヤ',
                'code'       => '0100',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高田 一輝',
                'yomi'       => 'タカダ',
                'code'       => '0101',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '遠藤 恵介',
                'yomi'       => 'エンドウ',
                'code'       => '0102',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高橋 祐也',
                'yomi'       => 'タカハシ',
                'code'       => '0103',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川口 隆司',
                'yomi'       => 'カワグチ',
                'code'       => '0104',
                'choku_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /**
         * create inspector process related table seeder
         */
        $table_name = 'worker_inspection_group';

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
            //成型_外観検査_ドア_白
            [
                'ig_id'     => 1,
                'worker_id' => 1,
                'sort'      => 1
            ],[
                'ig_id'     => 1,
                'worker_id' => 2,
                'sort'      => 2
            ],
            //成型_外観検査_ドア_黄
            [
                'ig_id'     => 1,
                'worker_id' => 3,
                'sort'      => 3
            ],[
                'ig_id'     => 1,
                'worker_id' => 4,
                'sort'      => 4
            ],


            //穴あけ_外観検査_ドア_白
            [
                'ig_id'     => 3,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 3,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //穴あけ_外観検査_ドア_黄
            [
                'ig_id'     => 3,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 3,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //穴あけ_穴検査_ドア_白
            [
                'ig_id'     => 4,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 4,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //穴あけ_穴検査_ドア_黄
            [
                'ig_id'     => 4,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 4,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_かしめ後検査_ドア_白
            [
                'ig_id'     => 5,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 5,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_かしめ後検査_ドア_黄
            [
                'ig_id'     => 5,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 5,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_止水_ドア_白
            [
                'ig_id'     => 10,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 10,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_止水_ドア_黄
            [
                'ig_id'     => 10,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 10,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_外観検査_ドア_白
            [
                'ig_id'     => 12,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 12,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_外観検査_ドア_黄
            [
                'ig_id'     => 12,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 12,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_手直_ドア_白
            [
                'ig_id'     => 13,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 13,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_手直_ドア_黄
            [
                'ig_id'     => 13,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 13,
                'worker_id' => 8,
                'sort'      => 4
            ],



            //成型_外観検査_ラゲージ_白
            [
                'ig_id'     => 14,
                'worker_id' => 1,
                'sort'      => 1
            ],[
                'ig_id'     => 14,
                'worker_id' => 2,
                'sort'      => 2
            ],
            //成型_外観検査_ラゲージ_黄
            [
                'ig_id'     => 14,
                'worker_id' => 3,
                'sort'      => 3
            ],[
                'ig_id'     => 14,
                'worker_id' => 4,
                'sort'      => 4
            ],


            //穴あけ_外観検査_ラゲージ_白
            [
                'ig_id'     => 16,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 16,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //穴あけ_外観検査_ラゲージ_黄
            [
                'ig_id'     => 16,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 16,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //穴あけ_穴検査_ラゲージ_白
            [
                'ig_id'     => 17,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 17,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //穴あけ_穴検査_ラゲージ_黄
            [
                'ig_id'     => 17,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 17,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_かしめ後検査_ラゲージ_白
            [
                'ig_id'     => 18,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 18,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_かしめ後検査_ラゲージ_黄
            [
                'ig_id'     => 18,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 18,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_外周仕上_ラゲージ_白
            [
                'ig_id'     => 19,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 19,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_外周仕上_ラゲージ_黄
            [
                'ig_id'     => 19,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 19,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_パテ修復後_ラゲージ_白
            [
                'ig_id'     => 20,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 20,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_パテ修復後_ラゲージ_黄
            [
                'ig_id'     => 20,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 20,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_水検後_ラゲージ_白
            [
                'ig_id'     => 21,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 21,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_水検後_ラゲージ_黄
            [
                'ig_id'     => 21,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 21,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_塗装受入後_ラゲージ_白
            [
                'ig_id'     => 22,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 22,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_塗装受入後_ラゲージ_黄
            [
                'ig_id'     => 22,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 22,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_外観検査_ラゲージ_白
            [
                'ig_id'     => 25,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 25,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_外観検査_ラゲージ_黄
            [
                'ig_id'     => 25,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 25,
                'worker_id' => 8,
                'sort'      => 4
            ],

            //かしめ/接着_手直_ラゲージ_白
            [
                'ig_id'     => 26,
                'worker_id' => 5,
                'sort'      => 1
            ],[
                'ig_id'     => 26,
                'worker_id' => 6,
                'sort'      => 2
            ],
            //かしめ/接着_手直_ラゲージ_黄
            [
                'ig_id'     => 26,
                'worker_id' => 7,
                'sort'      => 3
            ],[
                'ig_id'     => 26,
                'worker_id' => 8,
                'sort'      => 4
            ],

        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}