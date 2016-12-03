<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class InlineTableSeeder
 */
class InlineTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'inlines';
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
            //成型工程　精度検査　インナー
            [
                'sort'          => 1,
                'point'         => '595,295',
                'label_point'   => '655,180',
                'side'          => 'left',
                'face'          => null,
                'position'      => 'W0+30',
                'calibration'   => null,
                'max_tolerance' => 1.3,
                'min_tolerance' => -1.2,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 2,
                'point'         => '1080,325',
                'label_point'   => '1170,270',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'L37',
                'calibration'   => null,
                'max_tolerance' => 1.7,
                'min_tolerance' => -0.7,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 10,
                'point'         => '1080,325',
                'label_point'   => '1170,330',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'L37',
                'calibration'   => null,
                'max_tolerance' => 1.7,
                'min_tolerance' => -0.7,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 3,
                'point'         => '1160,480',
                'label_point'   => '1230,420',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'L42',
                'calibration'   => null,
                'max_tolerance' => 1.5,
                'min_tolerance' => -1.7,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 9,
                'point'         => '1160,480',
                'label_point'   => '1230,480',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'L42',
                'calibration'   => null,
                'max_tolerance' => 1.5,
                'min_tolerance' => -1.7,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 4,
                'point'         => '1135,520',
                'label_point'   => '1230,570',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'L43+85',
                'calibration'   => null,
                'max_tolerance' => 1.5,
                'min_tolerance' => -1.5,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 8,
                'point'         => '1135,520',
                'label_point'   => '1230,630',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'L43+85',
                'calibration'   => null,
                'max_tolerance' => 1.5,
                'min_tolerance' => -1.5,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 5,
                'point'         => '1065,875',
                'label_point'   => '1190,800',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'H14+50',
                'calibration'   => null,
                'max_tolerance' => 2.5,
                'min_tolerance' => -0.7,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 7,
                'point'         => '1065,875',
                'label_point'   => '1190,860',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'H14+50',
                'calibration'   => null,
                'max_tolerance' => 2.5,
                'min_tolerance' => -0.7,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 11,
                'point'         => '595,505',
                'label_point'   => '645,355',
                'side'          => 'left',
                'face'          => null,
                'position'      => 'W0',
                'calibration'   => null,
                'max_tolerance' => 0,
                'min_tolerance' => -3.2,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 12,
                'point'         => '595,535',
                'label_point'   => '735,415',
                'side'          => 'left',
                'face'          => null,
                'position'      => 'W0',
                'calibration'   => null,
                'max_tolerance' => 0.5,
                'min_tolerance' => -3.44,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 15,
                'point'         => '595,625',
                'label_point'   => '685,570',
                'side'          => 'left',
                'face'          => null,
                'position'      => 'W0',
                'calibration'   => null,
                'max_tolerance' => 1.5,
                'min_tolerance' => -1.5,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 18,
                'point'         => '595,890',
                'label_point'   => '660,950',
                'side'          => 'left',
                'face'          => null,
                'position'      => 'W0',
                'calibration'   => null,
                'max_tolerance' => 1.2,
                'min_tolerance' => -0.8,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 6,
                'point'         => '595,925',
                'label_point'   => '320,950',
                'side'          => 'right',
                'face'          => null,
                'position'      => 'W0',
                'calibration'   => null,
                'max_tolerance' => 2.5,
                'min_tolerance' => -1.2,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 16,
                'point'         => '120,415',
                'label_point'   => '240,160',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'L38',
                'calibration'   => null,
                'max_tolerance' => 2.0,
                'min_tolerance' => -2.0,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 20,
                'point'         => '120,415',
                'label_point'   => '240,220',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'L38',
                'calibration'   => null,
                'max_tolerance' => 2.0,
                'min_tolerance' => -2.0,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 17,
                'point'         => '90,495',
                'label_point'   => '170,30',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'L41',
                'calibration'   => null,
                'max_tolerance' => 1.0,
                'min_tolerance' => -2.27,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 19,
                'point'         => '90,495',
                'label_point'   => '170,90',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'L41',
                'calibration'   => null,
                'max_tolerance' => 1.0,
                'min_tolerance' => -2.27,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 13,
                'point'         => '230,535',
                'label_point'   => '360,360',
                'side'          => 'left',
                'face'          => 'RH',
                'position'      => 'W4R',
                'calibration'   => null,
                'max_tolerance' => 1.0,
                'min_tolerance' => -1.0,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 14,
                'point'         => '230,535',
                'label_point'   => '360,420',
                'side'          => 'left',
                'face'          => 'LH',
                'position'      => 'W4L',
                'calibration'   => null,
                'max_tolerance' => 1.0,
                'min_tolerance' => -1.0,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 21,
                'point'         => '1530,285',
                'label_point'   => '1260,60',
                'side'          => 'right',
                'face'          => 'RH',
                'position'      => 'L44*H18+30',
                'calibration'   => null,
                'max_tolerance' => 1.0,
                'min_tolerance' => -3.62,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 22,
                'point'         => '1530,285',
                'label_point'   => '1260,120',
                'side'          => 'right',
                'face'          => 'LH',
                'position'      => 'L44*H18+30',
                'calibration'   => null,
                'max_tolerance' => 1.0,
                'min_tolerance' => -3.62,
                'part_type_id'  => 1,
                'figure_id'     => 11,
                'created_at'    => $now,
                'updated_at'    => $now
            ],

            //接着工程　精度検査　インナーASSY
            [
                'sort'          => 1,
                'point'         => '860,165',
                'label_point'   => '920,50',
                'side'          => 'left',
                'face'          => '上中央面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.5,
                'min_tolerance' => -1.3,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 2,
                'point'         => '1470,260',
                'label_point'   => '1540,50',
                'side'          => 'left',
                'face'          => 'Rh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 0.8,
                'min_tolerance' => -2.2,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 10,
                'point'         => '1470,260',
                'label_point'   => '1540,110',
                'side'          => 'left',
                'face'          => 'Lh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 0.8,
                'min_tolerance' => -2.2,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 3,
                'point'         => '1500,330',
                'label_point'   => '1570,280',
                'side'          => 'left',
                'face'          => 'Rh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 0.8,
                'min_tolerance' => -1.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 9,
                'point'         => '1500,330',
                'label_point'   => '1570,340',
                'side'          => 'left',
                'face'          => 'Lh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 0.8,
                'min_tolerance' => -1.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 4,
                'point'         => '1480,590',
                'label_point'   => '1530,540',
                'side'          => 'left',
                'face'          => 'Rh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1,
                'min_tolerance' => -1,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 12,
                'point'         => '1480,590',
                'label_point'   => '1530,600',
                'side'          => 'left',
                'face'          => 'Rh 下エッジ',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 5,
                'point'         => '1420,830',
                'label_point'   => '1530,750',
                'side'          => 'left',
                'face'          => 'Rh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 13,
                'point'         => '1420,830',
                'label_point'   => '1530,810',
                'side'          => 'left',
                'face'          => 'Rh 下エッジ',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 19,
                'point'         => '1320,850',
                'label_point'   => '1440,890',
                'side'          => 'left',
                'face'          => 'Rh シール面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.3,
                'min_tolerance' => -1.2,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 22,
                'point'         => '1320,850',
                'label_point'   => '1440,950',
                'side'          => 'left',
                'face'          => 'Lh シール面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.3,
                'min_tolerance' => -1.2,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 7,
                'point'         => '290,830',
                'label_point'   => '50,830',
                'side'          => 'right',
                'face'          => 'Lh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 15,
                'point'         => '290,830',
                'label_point'   => '50,890',
                'side'          => 'right',
                'face'          => 'Lh 下エッジ',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 8,
                'point'         => '250,590',
                'label_point'   => '20,590',
                'side'          => 'right',
                'face'          => 'Lh面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1,
                'min_tolerance' => -1,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 16,
                'point'         => '250,590',
                'label_point'   => '20,650',
                'side'          => 'right',
                'face'          => 'Lh 下エッジ',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 17,
                'point'         => '280,280',
                'label_point'   => '40,50',
                'side'          => 'right',
                'face'          => 'Rh シール面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -0.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 20,
                'point'         => '280,280',
                'label_point'   => '40,110',
                'side'          => 'right',
                'face'          => 'Lh シール面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -0.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 18,
                'point'         => '270,370',
                'label_point'   => '20,290',
                'side'          => 'right',
                'face'          => 'Rh シール面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -1,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 21,
                'point'         => '270,370',
                'label_point'   => '20,350',
                'side'          => 'right',
                'face'          => 'Lh シール面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -1,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 11,
                'point'         => '860,475',
                'label_point'   => '930,320',
                'side'          => 'left',
                'face'          => 'スポイラー面',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.6,
                'min_tolerance' => -2.3,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 6,
                'point'         => '860,915',
                'label_point'   => '940,950',
                'side'          => 'left',
                'face'          => '下面中央',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 2.8,
                'min_tolerance' => -1.2,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ],[
                'sort'          => 14,
                'point'         => '860,915',
                'label_point'   => '640,940',
                'side'          => 'right',
                'face'          => '下中央エッジ',
                'position'      => '',
                'calibration'   => null,
                'max_tolerance' => 1.8,
                'min_tolerance' => -2.8,
                'part_type_id'  => 7,
                'figure_id'     => 12,
                'created_at'    => $now,
                'updated_at'    => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}