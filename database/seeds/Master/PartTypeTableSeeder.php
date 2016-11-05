<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PartTypeTableSeeder
 */
class PartTypeTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create part_type table seeder
         */
        $table_name = 'part_types';

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
                'pn'          => 67149,
                'name'        => 'バックドアインナー',
                'vehicle_num' => '680A',
                'sort'        => 1,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67119,
                'name'        => 'アッパー',
                'vehicle_num' => '680A',
                'sort'        => 2,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67175,
                'name'        => 'サイドアッパーRH',
                'vehicle_num' => '680A',
                'sort'        => 4,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67176,
                'name'        => 'サイドアッパーLH',
                'vehicle_num' => '680A',
                'sort'        => 3,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67177,
                'name'        => 'サイドロアRH',
                'vehicle_num' => '680A',
                'sort'        => 6,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67178,
                'name'        => 'サイドロアLH',
                'vehicle_num' => '680A',
                'sort'        => 5,
                'created_at'  => $now,
                'updated_at'  => $now
            ],[
                'pn'          => 67007,
                'name'        => 'バックドアインナASSY',
                'vehicle_num' => '680A',
                'sort'        => 7,
                'created_at'  => $now,
                'updated_at'  => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        /**
         * create part_type part_page related table seeder
         */
        $table_name = 'part_type_page_type';

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
            //成型：検査：ライン１：インナ
            [
                'page_type_id' => 1,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 13
            ],
            //成型：検査：ライン２：インナ
            [
                'page_type_id' => 2,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 13
            ],
            //成型：インライン精度検査：インナ
            [
                'page_type_id' => 3,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 14
            ],
            //穴あけ：検査：インナ page1~4
            [
                'page_type_id' => 4,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 15
            ],[
                'page_type_id' => 5,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 16
            ],[
                'page_type_id' => 6,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 17
            ],[
                'page_type_id' => 7,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 18
            ],
            //成型：検査：ライン１：小部品 page1
            [
                'page_type_id' => 8,
                'part_type_id' => 4,
                'area'         => '0/0/870/900',
                'figure_id'    => 20
            ],[
                'page_type_id' => 8,
                'part_type_id' => 3,
                'area'         => '870/0/1740/900',
                'figure_id'    => 19
            ],
            //成型：検査：ライン１：小部品 page2
            [
                'page_type_id' => 9,
                'part_type_id' => 2,
                'area'         => '0/0/1740/450',
                'figure_id'    => 21
            ],[
                'page_type_id' => 9,
                'part_type_id' => 6,
                'area'         => '0/450/870/860',
                'figure_id'    => 23
            ],[
                'page_type_id' => 9,
                'part_type_id' => 5,
                'area'         => '870/450/1740/860',
                'figure_id'    => 22
            ],
            //成型：検査：ライン２：小部品 page1
            [
                'page_type_id' => 10,
                'part_type_id' => 4,
                'area'         => '0/0/870/900',
                'figure_id'    => 20
            ],[
                'page_type_id' => 10,
                'part_type_id' => 3,
                'area'         => '870/0/1740/900',
                'figure_id'    => 19
            ],
            //成型：検査：ライン２：小部品 page2
            [
                'page_type_id' => 11,
                'part_type_id' => 2,
                'area'         => '0/0/1740/450',
                'figure_id'    => 21
            ],[
                'page_type_id' => 11,
                'part_type_id' => 6,
                'area'         => '0/450/870/860',
                'figure_id'    => 23
            ],[
                'page_type_id' => 11,
                'part_type_id' => 5,
                'area'         => '870/450/1740/860',
                'figure_id'    => 22
            ],
            //成型：インライン精度検査：小部品
            [
                'page_type_id' => 12,
                'part_type_id' => 6,
                'area'         => '',
                'figure_id'    => null
            ],
            //穴あけ：検査：小部品 page1
            [
                'page_type_id' => 13,
                'part_type_id' => 2,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 24
            ],
            //穴あけ：検査：小部品 page2
            [
                'page_type_id' => 14,
                'part_type_id' => 4,
                'area'         => '0/0/350/820',
                'figure_id'    => 25
            ],[
                'page_type_id' => 14,
                'part_type_id' => 3,
                'area'         => '350/0/720/820',
                'figure_id'    => 26
            ],[
                'page_type_id' => 14,
                'part_type_id' => 6,
                'area'         => '720/0/1250/820',
                'figure_id'    => 27
            ],[
                'page_type_id' => 14,
                'part_type_id' => 5,
                'area'         => '1250/0/1740/820',
                'figure_id'    => 28
            ],
            //穴あけ：検査：アウター：page2 (サイドとロア分離後)
            [
                'page_type_id' => 27,
                'part_type_id' => 4,
                'area'         => '0/0/870/900',
                'figure_id'    => 25
            ],[
                'page_type_id' => 27,
                'part_type_id' => 3,
                'area'         => '870/0/1740/900',
                'figure_id'    => 26
            ],
            //穴あけ：検査：アウター：page3 (サイドとロア分離後)
            [
                'page_type_id' => 28,
                'part_type_id' => 6,
                'area'         => '0/0/870/900',
                'figure_id'    => 27
            ],[
                'page_type_id' => 28,
                'part_type_id' => 5,
                'area'         => '870/0/1740/900',
                'figure_id'    => 28
            ],
            //接着：インライン検査：インナASSY
            [
                'page_type_id' => 15,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],[
                'page_type_id' => 16,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],[
                'page_type_id' => 17,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],[
                'page_type_id' => 18,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],[
                'page_type_id' => 19,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],[
                'page_type_id' => 20,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],
            //穴あけ：外観検査：インナ
            [
                'page_type_id' => 21,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 13
            ],
            //接着：簡易CF：インナASSY
            [
                'page_type_id' => 22,
                'part_type_id' => 7,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 29
            ],
            //穴あけ：オフライン手直し検査：インナ page1~4
            [
                'page_type_id' => 23,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 15
            ],[
                'page_type_id' => 24,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 16
            ],[
                'page_type_id' => 25,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 17
            ],[
                'page_type_id' => 26,
                'part_type_id' => 1,
                'area'         => '0/0/1740/1030',
                'figure_id'    => 18
            ],

        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}