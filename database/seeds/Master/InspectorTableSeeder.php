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
                'name'       => '吉田 諒佑',
                'code'       => '0001',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 貴紫',
                'code'       => '0002',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '鈴木 常喜',
                'code'       => '0003',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤 優輝',
                'code'       => '0004',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒川 航平',
                'code'       => '0005',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '一色 誠司',
                'code'       => '0006',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '磯貝 寿樹',
                'code'       => '0007',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加藤 瞭',
                'code'       => '0008',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '下釜 千春',
                'code'       => '0009',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '黒岩 良',
                'code'       => '00010',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '木里 祐也',
                'code'       => '00011',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '山中 英司',
                'code'       => '00012',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '板垣 孝輔',
                'code'       => '00013',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '畑中 武太蔵',
                'code'       => '00014',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松山 亮太',
                'code'       => '00015',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門田 健司',
                'code'       => '00016',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '阿部 均',
                'code'       => '00017',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '早坂 孝道',
                'code'       => '00018',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '迫田 忠信',
                'code'       => '00019',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '稲本 竜士',
                'code'       => '00020',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '波多野 充希',
                'code'       => '00021',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '門間 洸樹',
                'code'       => '00022',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '竹下 博城',
                'code'       => '00023',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '二神 崇瑛',
                'code'       => '00024',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '今野 良紀',
                'code'       => '00025',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '石橋 卓昇',
                'code'       => '00026',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '田中 翔',
                'code'       => '00027',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岩本 大和',
                'code'       => '00028',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '内田 敏和',
                'code'       => '00029',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '藤原 拓海',
                'code'       => '00030',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '佐藤 恭一',
                'code'       => '00031',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '梶川 潤一郎',
                'code'       => '00032',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '末信 高弘',
                'code'       => '00033',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '上田 龍太',
                'code'       => '00034',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '堀口 幸一',
                'code'       => '00035',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '西村 乃武',
                'code'       => '00036',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '市川 俊之',
                'code'       => '00037',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '浦丸 弘喜',
                'code'       => '00038',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '平野 篤史',
                'code'       => '00039',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '盛川 賢一',
                'code'       => '00040',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '松田 勝三',
                'code'       => '00041',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '加治屋 仁',
                'code'       => '00042',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '真塩 隆英',
                'code'       => '00043',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '廣政 大輔',
                'code'       => '00044',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '青山 政光',
                'code'       => '00045',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '横田 和宣',
                'code'       => '00046',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '森山 和江',
                'code'       => '00047',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '東 政義',
                'code'       => '00048',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '杉山 和也',
                'code'       => '00049',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '村山 貴志',
                'code'       => '00050',
                'group_code' => 'Y',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '岩下 聡',
                'code'       => '00051',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '藤原 亮一',
                'code'       => '00052',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '吉田 信一郎',
                'code'       => '00053',
                'group_code' => 'W',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        /**
         * create inspector process related table seeder
         */
        $table_name = 'inspector_inspection_group';

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
            ],
            //成型_検査_インナ_ライン１_黄
            [
                'inspection_g_id' => 1,
                'inspector_id'    => 8,
                'sort'            => 1
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 9,
                'sort'            => 2
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 10,
                'sort'            => 3
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 11,
                'sort'            => 4
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 12,
                'sort'            => 5
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 13,
                'sort'            => 6
            ],[
                'inspection_g_id' => 1,
                'inspector_id'    => 14,
                'sort'            => 7
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
            ],
            //成型_検査_インナ_ライン２_黄
            [
                'inspection_g_id' => 2,
                'inspector_id'    => 8,
                'sort'            => 1
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 9,
                'sort'            => 2
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 10,
                'sort'            => 3
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 11,
                'sort'            => 4
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 12,
                'sort'            => 5
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 13,
                'sort'            => 6
            ],[
                'inspection_g_id' => 2,
                'inspector_id'    => 14,
                'sort'            => 7
            ],

            //成型_検査_小部品_ライン1_白
            [
                'inspection_g_id' => 5,
                'inspector_id'    => 2,
                'sort'            => 1
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 3,
                'sort'            => 2
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 1,
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
            ],
            //成型_検査_小部品_ライン1_黄
            [
                'inspection_g_id' => 5,
                'inspector_id'    => 8,
                'sort'            => 1
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 9,
                'sort'            => 2
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 10,
                'sort'            => 3
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 11,
                'sort'            => 4
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 12,
                'sort'            => 5
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 13,
                'sort'            => 6
            ],[
                'inspection_g_id' => 5,
                'inspector_id'    => 14,
                'sort'            => 7
            ],

            //成型_検査_小部品_ライン2_白
            [
                'inspection_g_id' => 6,
                'inspector_id'    => 2,
                'sort'            => 1
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 3,
                'sort'            => 2
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 1,
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
            ],
            //成型_検査_小部品_ライン2_黄
            [
                'inspection_g_id' => 6,
                'inspector_id'    => 8,
                'sort'            => 1
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 9,
                'sort'            => 2
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 10,
                'sort'            => 3
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 11,
                'sort'            => 4
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 12,
                'sort'            => 5
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 13,
                'sort'            => 6
            ],[
                'inspection_g_id' => 6,
                'inspector_id'    => 14,
                'sort'            => 7
            ],

            //穴あけ_外観検査_インナ_白
            [
                'inspection_g_id' => 15,
                'inspector_id'    => 1,
                'sort'            => 1
            ],

            //穴あけ_外観検査_インナ_黄色
            [
                'inspection_g_id' => 15,
                'inspector_id'    => 8,
                'sort'            => 1
            ],

            //穴あけ_検査_インナ①_白
            [
                'inspection_g_id' => 4,
                'inspector_id'    => 15,
                'sort'            => 1
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 16,
                'sort'            => 2
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 17,
                'sort'            => 3
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 18,
                'sort'            => 4
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 19,
                'sort'            => 5
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 20,
                'sort'            => 6
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 21,
                'sort'            => 7
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 22,
                'sort'            => 8
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 23,
                'sort'            => 9
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 24,
                'sort'            => 10
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 25,
                'sort'            => 11
            ],
            //穴あけ_検査_インナ①_黄
            [
                'inspection_g_id' => 4,
                'inspector_id'    => 26,
                'sort'            => 1
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 27,
                'sort'            => 2
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 28,
                'sort'            => 3
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 29,
                'sort'            => 4
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 30,
                'sort'            => 5
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 31,
                'sort'            => 6
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 32,
                'sort'            => 7
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 33,
                'sort'            => 8
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 34,
                'sort'            => 9
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 35,
                'sort'            => 10
            ],[
                'inspection_g_id' => 4,
                'inspector_id'    => 36,
                'sort'            => 11
            ],


            // //穴あけ_検査_インナ②_白
            // [
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 16,
            //     'sort'            => 1
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 15,
            //     'sort'            => 2
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 17,
            //     'sort'            => 3
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 18,
            //     'sort'            => 4
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 19,
            //     'sort'            => 5
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 20,
            //     'sort'            => 6
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 21,
            //     'sort'            => 7
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 22,
            //     'sort'            => 8
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 23,
            //     'sort'            => 9
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 24,
            //     'sort'            => 10
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 25,
            //     'sort'            => 11
            // ],
            // //穴あけ_検査_インナ②_黄
            // [
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 26,
            //     'sort'            => 1
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 27,
            //     'sort'            => 2
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 28,
            //     'sort'            => 3
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 29,
            //     'sort'            => 4
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 30,
            //     'sort'            => 5
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 31,
            //     'sort'            => 6
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 32,
            //     'sort'            => 7
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 33,
            //     'sort'            => 8
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 34,
            //     'sort'            => 9
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 35,
            //     'sort'            => 10
            // ],[
            //     'inspection_g_id' => ?,
            //     'inspector_id'    => 36,
            //     'sort'            => 11
            // ],


            //穴あけ_検査_小部品_白
            [
                'inspection_g_id' => 8,
                'inspector_id'    => 17,
                'sort'            => 1
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 16,
                'sort'            => 2
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 15,
                'sort'            => 3
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 18,
                'sort'            => 4
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 19,
                'sort'            => 5
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 20,
                'sort'            => 6
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 21,
                'sort'            => 7
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 22,
                'sort'            => 8
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 23,
                'sort'            => 9
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 24,
                'sort'            => 10
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 25,
                'sort'            => 11
            ],
            //穴あけ_検査_小部品_黄
            [
                'inspection_g_id' => 8,
                'inspector_id'    => 28,
                'sort'            => 1
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 29,
                'sort'            => 2
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 30,
                'sort'            => 3
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 31,
                'sort'            => 4
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 26,
                'sort'            => 5
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 27,
                'sort'            => 6
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 32,
                'sort'            => 7
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 33,
                'sort'            => 8
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 34,
                'sort'            => 9
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 35,
                'sort'            => 10
            ],[
                'inspection_g_id' => 8,
                'inspector_id'    => 36,
                'sort'            => 11
            ],

            //接着_簡易CF_インナASSY_白
            [
                'inspection_g_id' => 16,
                'inspector_id'    => 1,
                'sort'            => 1
            ],
            //接着_簡易CF_インナASSY_黄
            [
                'inspection_g_id' => 16,
                'inspector_id'    => 8,
                'sort'            => 1
            ],

            //接着_止水_インナASSY_白
            [
                'inspection_g_id' => 10,
                'inspector_id'    => 37,
                'sort'            => 1
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 38,
                'sort'            => 2
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 39,
                'sort'            => 3
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 40,
                'sort'            => 4
            ],
            //接着_止水_インナASSY_黄
            [
                'inspection_g_id' => 10,
                'inspector_id'    => 42,
                'sort'            => 1
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 43,
                'sort'            => 2
            ],[
                'inspection_g_id' => 10,
                'inspector_id'    => 44,
                'sort'            => 3
            ],


            //接着_仕上_インナASSY_白
            [
                'inspection_g_id' => 11,
                'inspector_id'    => 38,
                'sort'            => 1
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 39,
                'sort'            => 2
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 40,
                'sort'            => 3
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 41,
                'sort'            => 4
            ],
            //接着_仕上_インナASSY_黄
            [
                'inspection_g_id' => 11,
                'inspector_id'    => 45,
                'sort'            => 1
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 46,
                'sort'            => 2
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 43,
                'sort'            => 3
            ],[
                'inspection_g_id' => 11,
                'inspector_id'    => 44,
                'sort'            => 3
            ],


            //接着_検査_インナASSY_白
            [
                'inspection_g_id' => 12,
                'inspector_id'    => 41,
                'sort'            => 1
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 39,
                'sort'            => 2
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 38,
                'sort'            => 3
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 40,
                'sort'            => 4
            ],
            //接着_検査_インナASSY_黄
            [
                'inspection_g_id' => 12,
                'inspector_id'    => 47,
                'sort'            => 1
            ],[
                'inspection_g_id' => 12,
                'inspector_id'    => 44,
                'sort'            => 2
            ],


            //接着_特検_インナASSY_白
            [
                'inspection_g_id' => 13,
                'inspector_id'    => 51,
                'sort'            => 1
            ],[
                'inspection_g_id' => 13,
                'inspector_id'    => 52,
                'sort'            => 2
            ],[
                'inspection_g_id' => 13,
                'inspector_id'    => 53,
                'sort'            => 3
            ],
            //接着_特検_インナASSY_黄
            [
                'inspection_g_id' => 13,
                'inspector_id'    => 48,
                'sort'            => 1
            ],[
                'inspection_g_id' => 13,
                'inspector_id'    => 49,
                'sort'            => 2
            ],


            //接着_手直し_インナASSY_白
            [
                'inspection_g_id' => 14,
                'inspector_id'    => 39,
                'sort'            => 1
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 40,
                'sort'            => 2
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 38,
                'sort'            => 3
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 41,
                'sort'            => 4
            ],
            //接着_手直し_インナASSY_黄
            [
                'inspection_g_id' => 14,
                'inspector_id'    => 43,
                'sort'            => 1
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 50,
                'sort'            => 2
            ],[
                'inspection_g_id' => 14,
                'inspector_id'    => 44,
                'sort'            => 3
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}