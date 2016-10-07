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
                'sort'               => 1,
                'point'              => '595,295',
                'label_point'        => '655,180',
                'face'               => null,
                'position'           => 'W0+30',
                'calibration'        => null,
                'standard_tolerance' => '-1.0~+1.2',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 2,
                'point'              => '1080,325',
                'label_point'        => '1170,270',
                'face'               => 'RH',
                'position'           => 'L37',
                'calibration'        => null,
                'standard_tolerance' => '-1.5~+0.5',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 10,
                'point'              => '1080,325',
                'label_point'        => '1170,330',
                'face'               => 'LH',
                'position'           => 'L37',
                'calibration'        => null,
                'standard_tolerance' => '-1.5~+0.5',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 3,
                'point'              => '1160,480',
                'label_point'        => '1230,420',
                'face'               => 'RH',
                'position'           => 'L42',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 9,
                'point'              => '1160,480',
                'label_point'        => '1230,480',
                'face'               => 'LH',
                'position'           => 'L42',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 4,
                'point'              => '1135,520',
                'label_point'        => '1230,570',
                'face'               => 'RH',
                'position'           => 'L43+85',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 8,
                'point'              => '1135,520',
                'label_point'        => '1230,630',
                'face'               => 'LH',
                'position'           => 'L43+85',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 5,
                'point'              => '1065,875',
                'label_point'        => '1190,800',
                'face'               => 'RH',
                'position'           => 'H14+50',
                'calibration'        => null,
                'standard_tolerance' => '-2.5~+0.5',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 7,
                'point'              => '1065,875',
                'label_point'        => '1190,860',
                'face'               => 'LH',
                'position'           => 'H14+50',
                'calibration'        => null,
                'standard_tolerance' => '-2.5~+0.5',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 11,
                'point'              => '595,505',
                'label_point'        => '645,355',
                'face'               => null,
                'position'           => 'W0',
                'calibration'        => null,
                'standard_tolerance' => '-0.5~+2.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 12,
                'point'              => '595,535',
                'label_point'        => '735,415',
                'face'               => null,
                'position'           => 'W0',
                'calibration'        => null,
                'standard_tolerance' => '-0.5~+2.4',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 15,
                'point'              => '595,625',
                'label_point'        => '685,570',
                'face'               => null,
                'position'           => 'W0',
                'calibration'        => null,
                'standard_tolerance' => '-0.7~+1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 18,
                'point'              => '595,890',
                'label_point'        => '660,950',
                'face'               => null,
                'position'           => 'W0',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 6,
                'point'              => '595,925',
                'label_point'        => '390,950',
                'face'               => null,
                'position'           => 'W0',
                'calibration'        => null,
                'standard_tolerance' => '-2.5~+0.5',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 16,
                'point'              => '120,415',
                'label_point'        => '240,160',
                'face'               => 'RH',
                'position'           => 'L38',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 20,
                'point'              => '120,415',
                'label_point'        => '240,220',
                'face'               => 'LH',
                'position'           => 'L38',
                'calibration'        => null,
                'standard_tolerance' => '±1.0',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 17,
                'point'              => '90,495',
                'label_point'        => '130,30',
                'face'               => 'RH',
                'position'           => 'L41',
                'calibration'        => null,
                'standard_tolerance' => '-1.0~+2.27',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 19,
                'point'              => '90,495',
                'label_point'        => '130,90',
                'face'               => 'LH',
                'position'           => 'L41',
                'calibration'        => null,
                'standard_tolerance' => '-1.0~+2.27',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 13,
                'point'              => '230,535',
                'label_point'        => '360,360',
                'face'               => 'RH',
                'position'           => 'W4R',
                'calibration'        => null,
                'standard_tolerance' => '±0.7',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 14,
                'point'              => '230,535',
                'label_point'        => '360,420',
                'face'               => 'LH',
                'position'           => 'W4L',
                'calibration'        => null,
                'standard_tolerance' => '±0.7',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 21,
                'point'              => '1530,285',
                'label_point'        => '1320,60',
                'face'               => 'RH',
                'position'           => 'L44*H18+30',
                'calibration'        => null,
                'standard_tolerance' => '-1.0~+3.62',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],[
                'sort'               => 22,
                'point'              => '1530,285',
                'label_point'        => '1320,120',
                'face'               => 'LH',
                'position'           => 'L44*H18+30',
                'calibration'        => null,
                'standard_tolerance' => '-1.0~+3.62',
                'input_tolerance'    => null,
                'part_type_id'       => 1,
                'figure_id'          => 12,
                'created_at'         => $now,
                'updated_at'         => $now
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}