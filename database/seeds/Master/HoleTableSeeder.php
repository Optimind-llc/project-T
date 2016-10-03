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
        ];

        /***** HARD CODE *****/
        // $img = ["x" => 1280, "y" => 1024, "margin" => 100, "arrow" => 80];
        // $hasHoles = [4, 5, 6, 7, 8, 9];

        // $data = [];
        // foreach ($hasHoles as $id) {
        //     for ($i = 1; $i <= 100; $i++) { 
        //         $x = rand($img["margin"], $img["x"] - $img["margin"]);
        //         $y = rand($img["margin"], $img["y"] - $img["margin"]);

        //         array_push($data, [
        //             'point'      => $x . ',' . $y,
        //             'label'      => $i,
        //             'direction'  => $i,
        //             'color'      => $i,
        //             'border'     => $i,
        //             'shape'      => $i,
        //             'figure_id'  => $id,
        //             'created_at' => $now,
        //             'updated_at' => $now
        //         ]);
        //     }
        // }

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}