<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class HoleTableSeeder
 */
class HoleTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'holes';
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
            //穴あけ：インナ：page1
            [
                'figure_id'  => 4,
                'point'      => '368,108',
                'label'      => 10,
                'direction'  => 'left',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '371,106',
                'label'      => 1,
                'direction'  => 'right',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '144,356',
                'label'      => 28,
                'direction'  => 'left',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '151,444',
                'label'      => 29,
                'direction'  => 'left',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '602,353',
                'label'      => 17,
                'direction'  => 'right',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '595,438',
                'label'      => 18,
                'direction'  => 'right',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '498,526',
                'label'      => 42,
                'direction'  => 'right',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '231,526',
                'label'      => 33,
                'direction'  => 'left',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '644,856',
                'label'      => 119,
                'direction'  => 'bottom',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '786,851',
                'label'      => 115,
                'direction'  => 'top',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '868,860',
                'label'      => 113,
                'direction'  => 'bottom',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '951,852',
                'label'      => 109,
                'direction'  => 'top',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '1094,855',
                'label'      => 108,
                'direction'  => 'bottom',
                'color'      => '0,0,0',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：インナ：page2
            // [
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],[
            //     'figure_id'  => 5,
            //     'point'      => ',',
            //     'label'      => ,
            //     'direction'  => '',
            //     'color'      => '0,0,0',
            //     'border'     => 'dotted',
            //     'shape'      => 'square',
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],
        ];



        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}