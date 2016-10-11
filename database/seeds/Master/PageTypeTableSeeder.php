<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PageTypeTableSeeder
 */
class PageTypeTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'page_types';
        $now = Carbon::now();

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
            //成型：検査：ライン１ インナ
            [
                'number'     => 1,
                'group_id'   => 1,
                'figure_id'  => 1,
                'pdf_path'   => 'molding-inner-line1.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型：検査：ライン２ インナ
            [
                'number'     => 1,
                'group_id'   => 2,
                'figure_id'  => 1,
                'pdf_path'   => 'molding-inner-line2.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型：インライン精度検査 インナ
            [
                'number'     => 1,
                'group_id'   => 3,
                'figure_id'  => 12,
                'pdf_path'   => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：検査：page1 インナ
            [
                'number'     => 1,
                'group_id'   => 4,
                'figure_id'  => 4,
                'pdf_path'   => 'holing-inner-page1.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：検査：page2 インナ
            [
                'number'     => 2,
                'group_id'   => 4,
                'figure_id'  => 5,
                'pdf_path'   => 'holing-inner-page2.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：検査：page3 インナ
            [
                'number'     => 3,
                'group_id'   => 4,
                'figure_id'  => 6,
                'pdf_path'   => 'holing-inner-page3.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：検査：page4 インナ
            [
                'number'     => 4,
                'group_id'   => 4,
                'figure_id'  => 7,
                'pdf_path'   => 'holing-inner-page4.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型：検査：ライン１：page1 小部品
            [
                'number'     => 1,
                'group_id'   => 5,
                'figure_id'  => 2,
                'pdf_path'   => 'molding-small-line1-page1.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型：検査：ライン１：page2 小部品
            [
                'number'     => 2,
                'group_id'   => 5,
                'figure_id'  => 3,
                'pdf_path'   => 'molding-small-line1-page2.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型：検査：ライン２：page1 小部品
            [
                'number'     => 1,
                'group_id'   => 6,
                'figure_id'  => 2,
                'pdf_path'   => 'molding-small-line2-page1.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型：検査：ライン２：page2 小部品
            [
                'number'     => 2,
                'group_id'   => 6,
                'figure_id'  => 3,
                'pdf_path'   => 'molding-small-line2-page2.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 成型：インライン検査：小部品
            [
                'number'     => 1,
                'group_id'   => 7,
                'figure_id'  => null,
                'pdf_path'   => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：検査：page1 小部品
            [
                'number'     => 1,
                'group_id'   => 8,
                'figure_id'  => 8,
                'pdf_path'   => 'holing-small-page1.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：検査：page2 小部品
            [
                'number'     => 2,
                'group_id'   => 8,
                'figure_id'  => 9,
                'pdf_path'   => 'holing-small-page2.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着：インライン検査：ASSY
            [
                'number'     => 1,
                'group_id'   => 9,
                'figure_id'  => 13,
                'pdf_path'   => null,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着：止水：ASSY
            [
                'number'     => 1,
                'group_id'   => 10,
                'figure_id'  => 10,
                'pdf_path'   => 'jointing-ws-assy.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 11,
                'figure_id'  => 10,
                'pdf_path'   => 'jointing-fn-assy.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 12,
                'figure_id'  => 10,
                'pdf_path'   => 'jointing-ch-assy.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 13,
                'figure_id'  => 10,
                'pdf_path'   => 'jointing-sc-assy.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'number'     => 1,
                'group_id'   => 14,
                'figure_id'  => 11,
                'pdf_path'   => 'jointing-ad-assy.pdf',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}