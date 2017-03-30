<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InspectorTableSeeder
 */
class InspectorTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create inspector table seeder
         */
        $table_name = 'inspectors';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            // 成型　白直
            [
                'name'       => '後藤 芳光',
                'yomi'       => 'ゴトウ',
                'code'       => '0001',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 貴紫',
                'yomi'       => 'スズキ',
                'code'       => '0002',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 常喜',
                'yomi'       => 'スズキ',
                'code'       => '0003',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤 優輝',
                'yomi'       => 'サトウ',
                'code'       => '0004',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '北村 凌太郎',
                'yomi'       => 'キタムラ',
                'code'       => '0005',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上松 健太',
                'yomi'       => 'ウエマツ',
                'code'       => '0006',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '磯貝 寿樹',
                'yomi'       => 'イソガイ',
                'code'       => '0007',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '原田 真一',
                'yomi'       => 'ハラダ',
                'code'       => '0008',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '出口 政孝',
                'yomi'       => 'デグチ',
                'code'       => '0009',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大森 知明',
                'yomi'       => 'オオモリ',
                'code'       => '0010',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '堀内 翔悟',
                'yomi'       => 'ホリウチ',
                'code'       => '0011',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門田 洸樹',
                'yomi'       => 'カドタ',
                'code'       => '0012',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '早坂 孝道',
                'yomi'       => 'ハヤサカ',
                'code'       => '0013',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 成型　黄直
            [
                'name'       => '畑中 武太蔵',
                'yomi'       => 'ハタナカ',
                'code'       => '0014',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉田 健作',
                'yomi'       => 'ヨシダ',
                'code'       => '0015',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '八木 恵司',
                'yomi'       => 'ヤギ',
                'code'       => '0016',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岩本 大和',
                'yomi'       => 'イワモト',
                'code'       => '0017',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門倉 潤平',
                'yomi'       => 'カドクラ',
                'code'       => '0018',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川村 博康',
                'yomi'       => 'カワムラ',
                'code'       => '0019',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西村 乃武',
                'yomi'       => 'ニシムラ',
                'code'       => '0020',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '木里 裕也',
                'yomi'       => 'キザト',
                'code'       => '0021',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '下釜 千春',
                'yomi'       => 'シモガマ',
                'code'       => '0022',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '奥村 康平',
                'yomi'       => 'オクムラ',
                'code'       => '0023',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '板垣 孝輔',
                'yomi'       => 'イタガキ',
                'code'       => '0024',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川瀬 悠',
                'yomi'       => 'カワセ',
                'code'       => '0025',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中井 達也',
                'yomi'       => 'ナカイ',
                'code'       => '0026',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 成型　黒直
            [
                'name'       => '古川 達也',
                'yomi'       => 'フルカワ',
                'code'       => '0027',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒岩 良',
                'yomi'       => 'クロイワ',
                'code'       => '0028',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒川 航平',
                'yomi'       => 'クロカワ',
                'code'       => '0029',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '藤原 拓海',
                'yomi'       => 'フジワラ',
                'code'       => '0030',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉田 諒佑',
                'yomi'       => 'ヨシダ',
                'code'       => '0031',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '一色 誠司',
                'yomi'       => 'イッシキ',
                'code'       => '0032',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '新田 和範',
                'yomi'       => 'ニッタ',
                'code'       => '0033',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岡元 和浩',
                'yomi'       => 'オカモト',
                'code'       => '0034',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '坂本 一幸',
                'yomi'       => 'サカモト',
                'code'       => '0035',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '赤城 大樹',
                'yomi'       => 'アカギ',
                'code'       => '0036',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '河村 将光',
                'yomi'       => 'カワムラ',
                'code'       => '0037',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '永元 保隆',
                'yomi'       => 'ナガモト',
                'code'       => '0038',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '大山 徳仁',
                'yomi'       => 'オオヤマ',
                'code'       => '0039',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '宗像 徹郎',
                'yomi'       => 'ムネカタ',
                'code'       => '0040',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],

            //穴あけ工程　白直
            [
                'name'       => '今野 良紀',
                'yomi'       => 'コンノ',
                'code'       => '0041',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '迫田 忠信',
                'yomi'       => 'サコタ',
                'code'       => '0042',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '稲本 竜士',
                'yomi'       => 'イナモト',
                'code'       => '0043',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門間 洸樹',
                'yomi'       => 'カドマ',
                'code'       => '0044',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '阿部 均',
                'yomi'       => 'アベ',
                'code'       => '0045',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 藍斗',
                'yomi'       => 'スズキ',
                'code'       => '0046',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小田代 大介',
                'yomi'       => 'コタダイ',
                'code'       => '0047',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '里園 友基',
                'yomi'       => 'サトゾノ',
                'code'       => '0048',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '水上 貢',
                'yomi'       => 'ミナカミ',
                'code'       => '0049',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中村 亮介',
                'yomi'       => 'ナカムラ',
                'code'       => '0050',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ工程　黄直
            [
                'name'       => '堀口 幸一',
                'yomi'       => 'ホリグチ',
                'code'       => '0051',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '末信 高弘',
                'yomi'       => 'スエノブ',
                'code'       => '0052',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '梶川 潤一郎',
                'yomi'       => 'カジカワ',
                'code'       => '0053',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '嶺山 仁志',
                'yomi'       => 'ミネヤマ',
                'code'       => '0054',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '田中 翔',
                'yomi'       => 'タナカ',
                'code'       => '0055',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤 恭一',
                'yomi'       => 'サトウ',
                'code'       => '0056',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小渕 賢一',
                'yomi'       => 'オブチ',
                'code'       => '0057',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山田 和生',
                'yomi'       => 'ヤマダ',
                'code'       => '0058',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高橋 広幹',
                'yomi'       => 'タカハシ',
                'code'       => '0059',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西脇 宙',
                'yomi'       => 'ニシワキ',
                'code'       => '0060',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山下 一男',
                'yomi'       => 'ヤマシタ',
                'code'       => '0061',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ工程　黒直
            [
                'name'       => '山内 高寛',
                'yomi'       => 'ヤマウチ',
                'code'       => '0062',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '波多野 充希',
                'yomi'       => 'ハタノ',
                'code'       => '0063',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上田 龍太',
                'yomi'       => 'ウエダ',
                'code'       => '0064',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '竹下 博城',
                'yomi'       => 'タケシタ',
                'code'       => '0065',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '内田 敏和',
                'yomi'       => 'ウチダ',
                'code'       => '0066',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '石橋 卓昇',
                'yomi'       => 'イシバシ',
                'code'       => '0067',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松山 亮太',
                'yomi'       => 'マツヤマ',
                'code'       => '0068',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門馬 弘尚',
                'yomi'       => 'モンバ',
                'code'       => '0069',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '服部 智之',
                'yomi'       => 'ハットリ',
                'code'       => '0070',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '早川 正博',
                'yomi'       => 'ハヤカワ',
                'code'       => '0071',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岩永 秀道',
                'yomi'       => 'イワナガ',
                'code'       => '0072',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],

            // 接着工程　白直
            [
                'name'       => '山本 雄策',
                'yomi'       => 'ヤマモト',
                'code'       => '0073',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浦丸 弘喜',
                'yomi'       => 'ウラマル',
                'code'       => '0074',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '古田 宏樹',
                'yomi'       => 'フルタ',
                'code'       => '0075',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加々良 豊',
                'yomi'       => 'カガラ',
                'code'       => '0076',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松井 規浩',
                'yomi'       => 'マツイ',
                'code'       => '0077',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '平野 篤史',
                'yomi'       => 'ヒラノ',
                'code'       => '0078',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '市川 俊之',
                'yomi'       => 'イチカワ',
                'code'       => '0079',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '菊池 幸司',
                'yomi'       => 'キクチ',
                'code'       => '0080',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '久野 力',
                'yomi'       => 'ヒサノ',
                'code'       => '0081',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松田 勝三',
                'yomi'       => 'マツダ',
                'code'       => '0082',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '竹内 匡之',
                'yomi'       => 'タケウチ',
                'code'       => '0083',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 接着工程　黄直
            [
                'name'       => '河田 翼',
                'yomi'       => 'カワタ',
                'code'       => '0084',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '廣政 大輔',
                'yomi'       => 'ヒロマサ',
                'code'       => '0085',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山中 英司',
                'yomi'       => 'ヤマナカ',
                'code'       => '0086',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '告吉 貴史',
                'yomi'       => 'ツゲヨシ',
                'code'       => '0087',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '中田 浩二',
                'yomi'       => 'ナカタ',
                'code'       => '0088',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '村山 貴志',
                'yomi'       => 'ムラヤマ',
                'code'       => '0089',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '近松 伸一',
                'yomi'       => 'チカマツ',
                'code'       => '0090',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '横田 和宣',
                'yomi'       => 'ヨコタ',
                'code'       => '0091',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '小笠原 拓哉',
                'yomi'       => 'オガサワラ',
                'code'       => '0092',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '森山 和江',
                'yomi'       => 'モリヤマ',
                'code'       => '0093',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 接着工程　黒直
            [
                'name'       => '奥口 朋哉',
                'yomi'       => 'オクグチ',
                'code'       => '0094',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '青山 政光',
                'yomi'       => 'アオヤマ',
                'code'       => '0095',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '真塩 隆英',
                'yomi'       => 'マジオ',
                'code'       => '0096',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '平野 隆',
                'yomi'       => 'ヒラノ',
                'code'       => '0097',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '永田 優',
                'yomi'       => 'ナガタ',
                'code'       => '0098',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '盛川 賢一',
                'yomi'       => 'モリカワ',
                'code'       => '0099',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鍛冶屋 仁',
                'yomi'       => 'カジヤ',
                'code'       => '0100',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高田 一輝',
                'yomi'       => 'タカダ',
                'code'       => '0101',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '遠藤 恵介',
                'yomi'       => 'エンドウ',
                'code'       => '0102',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '高橋 祐也',
                'yomi'       => 'タカハシ',
                'code'       => '0103',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '川口 隆司',
                'yomi'       => 'カワグチ',
                'code'       => '0104',
                'group_code' => 'B',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        /**
         * create inspector process related table seeder
         */
        $table_name = 'inspector_inspection_group';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            //成型_検査_インナ_ライン１_白
            [
                'inspection_g_id' => 1,
                'inspector_id'    => 1,
                'sort'            => 1
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 2,
                'sort'            => 2
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 3,
                'sort'            => 3
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 4,
                'sort'            => 4
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 5,
                'sort'            => 5
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 6,
                'sort'            => 6
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 7,
                'sort'            => 7
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 8,
                'sort'            => 8
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 9,
                'sort'            => 9
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 10,
                'sort'            => 10
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 11,
                'sort'            => 11
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 12,
                'sort'            => 12
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 13,
                'sort'            => 13
            ],
            //成型_検査_インナ_ライン１_黄
            [
                'inspection_g_id' => 1,
                'inspector_id'    => 14,
                'sort'            => 1
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 15,
                'sort'            => 2
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 16,
                'sort'            => 3
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 17,
                'sort'            => 4
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 18,
                'sort'            => 5
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 19,
                'sort'            => 6
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 20,
                'sort'            => 7
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 21,
                'sort'            => 8
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 22,
                'sort'            => 9
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 23,
                'sort'            => 10
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 24,
                'sort'            => 11
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 25,
                'sort'            => 12
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 26,
                'sort'            => 13
            ],
            //成型_検査_インナ_ライン１_黒
            [
                'inspection_g_id' => 1,
                'inspector_id'    => 27,
                'sort'            => 1
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 28,
                'sort'            => 2
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 29,
                'sort'            => 3
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 30,
                'sort'            => 4
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 31,
                'sort'            => 5
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 32,
                'sort'            => 6
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 33,
                'sort'            => 7
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 34,
                'sort'            => 8
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 35,
                'sort'            => 9
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 36,
                'sort'            => 10
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 37,
                'sort'            => 11
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 38,
                'sort'            => 12
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 39,
                'sort'            => 13
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 40,
                'sort'            => 14
            ],



            //成型_検査_インナ_ライン２_白
            [
                'inspection_g_id' => 2,
                'inspector_id'    => 1,
                'sort'            => 1
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 2,
                'sort'            => 2
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 3,
                'sort'            => 3
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 4,
                'sort'            => 4
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 5,
                'sort'            => 5
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 6,
                'sort'            => 6
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 7,
                'sort'            => 7
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 8,
                'sort'            => 8
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 9,
                'sort'            => 9
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 10,
                'sort'            => 10
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 11,
                'sort'            => 11
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 12,
                'sort'            => 12
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 13,
                'sort'            => 13
            ],
            //成型_検査_インナ_ライン２_黄
            [
                'inspection_g_id' => 2,
                'inspector_id'    => 14,
                'sort'            => 1
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 15,
                'sort'            => 2
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 16,
                'sort'            => 3
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 17,
                'sort'            => 4
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 18,
                'sort'            => 5
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 19,
                'sort'            => 6
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 20,
                'sort'            => 7
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 21,
                'sort'            => 8
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 22,
                'sort'            => 9
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 23,
                'sort'            => 10
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 24,
                'sort'            => 11
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 25,
                'sort'            => 12
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 26,
                'sort'            => 13
            ],
            //成型_検査_インナ_ライン２_黒
            [
                'inspection_g_id' => 2,
                'inspector_id'    => 27,
                'sort'            => 1
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 28,
                'sort'            => 2
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 29,
                'sort'            => 3
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 30,
                'sort'            => 4
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 31,
                'sort'            => 5
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 32,
                'sort'            => 6
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 33,
                'sort'            => 7
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 34,
                'sort'            => 8
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 35,
                'sort'            => 9
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 36,
                'sort'            => 10
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 37,
                'sort'            => 11
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 38,
                'sort'            => 12
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 39,
                'sort'            => 13
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 40,
                'sort'            => 14
            ],



            //成型_検査_アウター_ライン１_白
            [
                'inspection_g_id' => 5,
                'inspector_id'    => 1,
                'sort'            => 1
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 2,
                'sort'            => 2
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 3,
                'sort'            => 3
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 4,
                'sort'            => 4
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 5,
                'sort'            => 5
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 6,
                'sort'            => 6
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 7,
                'sort'            => 7
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 8,
                'sort'            => 8
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 9,
                'sort'            => 9
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 10,
                'sort'            => 10
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 11,
                'sort'            => 11
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 12,
                'sort'            => 12
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 13,
                'sort'            => 13
            ],
            //成型_検査_アウター_ライン１_黄
            [
                'inspection_g_id' => 5,
                'inspector_id'    => 14,
                'sort'            => 1
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 15,
                'sort'            => 2
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 16,
                'sort'            => 3
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 17,
                'sort'            => 4
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 18,
                'sort'            => 5
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 19,
                'sort'            => 6
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 20,
                'sort'            => 7
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 21,
                'sort'            => 8
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 22,
                'sort'            => 9
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 23,
                'sort'            => 10
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 24,
                'sort'            => 11
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 25,
                'sort'            => 12
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 26,
                'sort'            => 13
            ],
            //成型_検査_アウター_ライン１_黒
            [
                'inspection_g_id' => 5,
                'inspector_id'    => 27,
                'sort'            => 1
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 28,
                'sort'            => 2
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 29,
                'sort'            => 3
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 30,
                'sort'            => 4
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 31,
                'sort'            => 5
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 32,
                'sort'            => 6
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 33,
                'sort'            => 7
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 34,
                'sort'            => 8
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 35,
                'sort'            => 9
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 36,
                'sort'            => 10
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 37,
                'sort'            => 11
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 38,
                'sort'            => 12
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 39,
                'sort'            => 13
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 40,
                'sort'            => 14
            ],




            //成型_検査_アウター_ライン２_白
            [
                'inspection_g_id' => 6,
                'inspector_id'    => 1,
                'sort'            => 1
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 2,
                'sort'            => 2
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 3,
                'sort'            => 3
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 4,
                'sort'            => 4
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 5,
                'sort'            => 5
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 6,
                'sort'            => 6
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 7,
                'sort'            => 7
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 8,
                'sort'            => 8
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 9,
                'sort'            => 9
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 10,
                'sort'            => 10
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 11,
                'sort'            => 11
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 12,
                'sort'            => 12
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 13,
                'sort'            => 13
            ],
            //成型_検査_アウター_ライン２_黄
            [
                'inspection_g_id' => 6,
                'inspector_id'    => 14,
                'sort'            => 1
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 15,
                'sort'            => 2
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 16,
                'sort'            => 3
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 17,
                'sort'            => 4
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 18,
                'sort'            => 5
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 19,
                'sort'            => 6
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 20,
                'sort'            => 7
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 21,
                'sort'            => 8
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 22,
                'sort'            => 9
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 23,
                'sort'            => 10
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 24,
                'sort'            => 11
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 25,
                'sort'            => 12
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 26,
                'sort'            => 13
            ],
            //成型_検査_アウター_ライン２_黒
            [
                'inspection_g_id' => 6,
                'inspector_id'    => 27,
                'sort'            => 1
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 28,
                'sort'            => 2
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 29,
                'sort'            => 3
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 30,
                'sort'            => 4
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 31,
                'sort'            => 5
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 32,
                'sort'            => 6
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 33,
                'sort'            => 7
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 34,
                'sort'            => 8
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 35,
                'sort'            => 9
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 36,
                'sort'            => 10
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 37,
                'sort'            => 11
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 38,
                'sort'            => 12
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 39,
                'sort'            => 13
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 40,
                'sort'            => 14
            ],





            //穴あけ_外観検査_インナー_白
            [
                'inspection_g_id' => 15,
                'inspector_id'    => 41,
                'sort'            => 1
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 42,
                'sort'            => 2
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 43,
                'sort'            => 3
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 44,
                'sort'            => 4
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 45,
                'sort'            => 5
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 46,
                'sort'            => 6
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 47,
                'sort'            => 7
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 48,
                'sort'            => 8
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 49,
                'sort'            => 9
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 50,
                'sort'            => 10
            ],
            //穴あけ_外観検査_インナー_黄
            [
                'inspection_g_id' => 15,
                'inspector_id'    => 51,
                'sort'            => 1
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 52,
                'sort'            => 2
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 53,
                'sort'            => 3
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 54,
                'sort'            => 4
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 55,
                'sort'            => 5
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 56,
                'sort'            => 6
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 57,
                'sort'            => 7
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 58,
                'sort'            => 8
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 59,
                'sort'            => 9
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 60,
                'sort'            => 10
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 61,
                'sort'            => 11
            ],
            //穴あけ_外観検査_インナー_黒
            [
                'inspection_g_id' => 15,
                'inspector_id'    => 62,
                'sort'            => 1
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 63,
                'sort'            => 2
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 64,
                'sort'            => 3
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 65,
                'sort'            => 4
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 66,
                'sort'            => 5
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 67,
                'sort'            => 6
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 68,
                'sort'            => 7
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 69,
                'sort'            => 8
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 70,
                'sort'            => 9
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 71,
                'sort'            => 10
            ],[
                'inspection_g_id' => 15,
                'inspector_id'    => 72,
                'sort'            => 11
            ],



            //穴あけ_穴検査_インナー_白
            [
                'inspection_g_id' => 4,
                'inspector_id'    => 41,
                'sort'            => 1
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 42,
                'sort'            => 2
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 43,
                'sort'            => 3
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 44,
                'sort'            => 4
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 45,
                'sort'            => 5
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 46,
                'sort'            => 6
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 47,
                'sort'            => 7
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 48,
                'sort'            => 8
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 49,
                'sort'            => 9
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 50,
                'sort'            => 10
            ],
            //穴あけ_穴検査_インナー_黄
            [
                'inspection_g_id' => 4,
                'inspector_id'    => 51,
                'sort'            => 1
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 52,
                'sort'            => 2
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 53,
                'sort'            => 3
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 54,
                'sort'            => 4
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 55,
                'sort'            => 5
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 56,
                'sort'            => 6
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 57,
                'sort'            => 7
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 58,
                'sort'            => 8
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 59,
                'sort'            => 9
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 60,
                'sort'            => 10
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 61,
                'sort'            => 11
            ],
            //穴あけ_穴検査_インナー_黒
            [
                'inspection_g_id' => 4,
                'inspector_id'    => 62,
                'sort'            => 1
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 63,
                'sort'            => 2
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 64,
                'sort'            => 3
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 65,
                'sort'            => 4
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 66,
                'sort'            => 5
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 67,
                'sort'            => 6
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 68,
                'sort'            => 7
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 69,
                'sort'            => 8
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 70,
                'sort'            => 9
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 71,
                'sort'            => 10
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 72,
                'sort'            => 11
            ],




            //穴あけ_穴検査_アウター_白
            [
                'inspection_g_id' => 8,
                'inspector_id'    => 41,
                'sort'            => 1
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 42,
                'sort'            => 2
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 43,
                'sort'            => 3
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 44,
                'sort'            => 4
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 45,
                'sort'            => 5
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 46,
                'sort'            => 6
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 47,
                'sort'            => 7
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 48,
                'sort'            => 8
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 49,
                'sort'            => 9
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 50,
                'sort'            => 10
            ],
            //穴あけ_穴検査_アウター_黄
            [
                'inspection_g_id' => 8,
                'inspector_id'    => 51,
                'sort'            => 1
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 52,
                'sort'            => 2
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 53,
                'sort'            => 3
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 54,
                'sort'            => 4
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 55,
                'sort'            => 5
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 56,
                'sort'            => 6
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 57,
                'sort'            => 7
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 58,
                'sort'            => 8
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 59,
                'sort'            => 9
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 60,
                'sort'            => 10
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 61,
                'sort'            => 11
            ],
            //穴あけ_穴検査_アウター_黒
            [
                'inspection_g_id' => 8,
                'inspector_id'    => 62,
                'sort'            => 1
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 63,
                'sort'            => 2
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 64,
                'sort'            => 3
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 65,
                'sort'            => 4
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 66,
                'sort'            => 5
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 67,
                'sort'            => 6
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 68,
                'sort'            => 7
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 69,
                'sort'            => 8
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 70,
                'sort'            => 9
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 71,
                'sort'            => 10
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 72,
                'sort'            => 11
            ],




            //接着_簡易CF_ASSY_白
            [
                'inspection_g_id' => 16,
                'inspector_id'    => 73,
                'sort'            => 1
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 74,
                'sort'            => 2
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 75,
                'sort'            => 3
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 76,
                'sort'            => 4
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 77,
                'sort'            => 5
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 78,
                'sort'            => 6
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 80,
                'sort'            => 7
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 83,
                'sort'            => 8
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 82,
                'sort'            => 9
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 81,
                'sort'            => 10
            ],
            //接着_簡易CF_ASSY_黄
            [
                'inspection_g_id' => 16,
                'inspector_id'    => 84,
                'sort'            => 1
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 85,
                'sort'            => 2
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 86,
                'sort'            => 3
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 87,
                'sort'            => 4
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 88,
                'sort'            => 5
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 89,
                'sort'            => 6
            ],
            //接着_簡易CF_ASSY_黒
            [
                'inspection_g_id' => 16,
                'inspector_id'    => 94,
                'sort'            => 1
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 95,
                'sort'            => 2
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 96,
                'sort'            => 3
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 97,
                'sort'            => 4
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 98,
                'sort'            => 5
            ],[
                'inspection_g_id' => 16,
                'inspector_id'    => 99,
                'sort'            => 6
            ],





            //接着_止水_ASSY_白
            [
                'inspection_g_id' => 10,
                'inspector_id'    => 79,
                'sort'            => 1
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 74,
                'sort'            => 2
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 75,
                'sort'            => 3
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 76,
                'sort'            => 4
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 77,
                'sort'            => 5
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 78,
                'sort'            => 6
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 80,
                'sort'            => 7
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 83,
                'sort'            => 8
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 82,
                'sort'            => 9
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 81,
                'sort'            => 10
            ],
            //接着_止水_ASSY_黄色
            [
                'inspection_g_id' => 10,
                'inspector_id'    => 90,
                'sort'            => 1
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 85,
                'sort'            => 2
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 86,
                'sort'            => 3
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 87,
                'sort'            => 4
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 88,
                'sort'            => 5
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 89,
                'sort'            => 6
            ],
            //接着_止水_ASSY_黒
            [
                'inspection_g_id' => 10,
                'inspector_id'    => 100,
                'sort'            => 1
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 95,
                'sort'            => 2
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 96,
                'sort'            => 3
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 97,
                'sort'            => 4
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 98,
                'sort'            => 5
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 99,
                'sort'            => 6
            ],





            //接着_仕上_ASSY_白
            [
                'inspection_g_id' => 11,
                'inspector_id'    => 80,
                'sort'            => 1
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 75,
                'sort'            => 2
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 76,
                'sort'            => 3
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 74,
                'sort'            => 4
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 77,
                'sort'            => 5
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 78,
                'sort'            => 6
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 83,
                'sort'            => 7
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 82,
                'sort'            => 8
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 81,
                'sort'            => 9
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 79,
                'sort'            => 10
            ],
            //接着_仕上_ASSY_黄
            [
                'inspection_g_id' => 11,
                'inspector_id'    => 91,
                'sort'            => 1
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 23,
                'sort'            => 2
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 85,
                'sort'            => 3
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 86,
                'sort'            => 4
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 87,
                'sort'            => 5
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 88,
                'sort'            => 6
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 89,
                'sort'            => 7
            ],
            //接着_仕上_ASSY_黒
            [
                'inspection_g_id' => 11,
                'inspector_id'    => 101,
                'sort'            => 1
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 102,
                'sort'            => 2
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 95,
                'sort'            => 3
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 96,
                'sort'            => 4
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 97,
                'sort'            => 5
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 98,
                'sort'            => 6
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 99,
                'sort'            => 7
            ],





            //接着_検査_ASSY_白
            [
                'inspection_g_id' => 12,
                'inspector_id'    => 81,
                'sort'            => 1
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 82,
                'sort'            => 2
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 83,
                'sort'            => 3
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 74,
                'sort'            => 4
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 75,
                'sort'            => 5
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 76,
                'sort'            => 6
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 77,
                'sort'            => 7
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 78,
                'sort'            => 8
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 80,
                'sort'            => 7
            ],
            //接着_検査_ASSY_黄
            [
                'inspection_g_id' => 12,
                'inspector_id'    => 92,
                'sort'            => 1
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 93,
                'sort'            => 2
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 91,
                'sort'            => 3
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 85,
                'sort'            => 4
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 86,
                'sort'            => 5
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 87,
                'sort'            => 6
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 88,
                'sort'            => 7
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 89,
                'sort'            => 8
            ],
            //接着_検査_ASSY_黒
            [
                'inspection_g_id' => 12,
                'inspector_id'    => 103,
                'sort'            => 1
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 104,
                'sort'            => 2
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 95,
                'sort'            => 3
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 96,
                'sort'            => 4
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 97,
                'sort'            => 5
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 98,
                'sort'            => 6
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 99,
                'sort'            => 7
            ],








            //接着_手直_ASSY_白
            [
                'inspection_g_id' => 14,
                'inspector_id'    => 74,
                'sort'            => 1
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 82,
                'sort'            => 2
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 77,
                'sort'            => 3
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 78,
                'sort'            => 4
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 83,
                'sort'            => 5
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 75,
                'sort'            => 6
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 76,
                'sort'            => 7
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 80,
                'sort'            => 8
            ],
            //接着_手直_ASSY_黄
            [
                'inspection_g_id' => 14,
                'inspector_id'    => 86,
                'sort'            => 1
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 87,
                'sort'            => 2
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 88,
                'sort'            => 3
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 89,
                'sort'            => 4
            ],
            //接着_手直_ASSY_黒
            [
                'inspection_g_id' => 14,
                'inspector_id'    => 95,
                'sort'            => 1
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 96,
                'sort'            => 2
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 97,
                'sort'            => 3
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 98,
                'sort'            => 4
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 99,
                'sort'            => 5
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}