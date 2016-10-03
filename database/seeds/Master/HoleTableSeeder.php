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
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '1371,106',
                'label'      => 1,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '144,356',
                'label'      => 28,
                'direction'  => 'left',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '151,444',
                'label'      => 29,
                'direction'  => 'left',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '1602,353',
                'label'      => 17,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '1595,438',
                'label'      => 18,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '491,450',
                'label'      => 42,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '1236,447',
                'label'      => 33,
                'direction'  => 'left',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '644,856',
                'label'      => 119,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '786,851',
                'label'      => 115,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '868,860',
                'label'      => 113,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '951,852',
                'label'      => 109,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 4,
                'point'      => '1094,855',
                'label'      => 108,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'dotted',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：インナ：page2
            [
                'figure_id'  => 5,
                'point'      => '102,195',
                'label'      => 44,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '220,325',
                'label'      => 45,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '304,294',
                'label'      => 46,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '326,362',
                'label'      => 49,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '311,458',
                'label'      => 47,
                'direction'  => 'left',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '556,447',
                'label'      => 102,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '484,468',
                'label'      => 103,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '547,508',
                'label'      => 104,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1600,173',
                'label'      => 35,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1496,297',
                'label'      => 36,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1402,326',
                'label'      => 40,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1415,430',
                'label'      => 38,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1175,426',
                'label'      => 76,
                'direction'  => 'left',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1183,491',
                'label'      => 77,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '583,791',
                'label'      => 99,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '711,796',
                'label'      => 91,
                'direction'  => 'top',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '585,856',
                'label'      => 100,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '709,837',
                'label'      => 90,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '846,845',
                'label'      => 88,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1029,836',
                'label'      => 83,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 5,
                'point'      => '1156,787',
                'label'      => 79,
                'direction'  => 'bottom',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：インナ：page3
            [
                'figure_id'  => 6,
                'point'      => ',',
                'label'      => 86,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '226,887',
                'label'      => 89,
                'direction'  => 'left',
                'color'      => '155,155,155',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '299,886',
                'label'      => 87,
                'direction'  => 'right',
                'color'      => '155,155,155',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '87,981',
                'label'      => 105,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '189,981',
                'label'      => 94,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '337,980',
                'label'      => 93,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '439,980',
                'label'      => 84,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '433,492',
                'label'      => 34,
                'direction'  => 'top',
                'color'      => '126,211,33',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '330,627',
                'label'      => 41,
                'direction'  => 'left',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '51,486',
                'label'      => 78,
                'direction'  => 'right',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '198,331',
                'label'      => 51,
                'direction'  => 'right',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '480,181',
                'label'      => 95,
                'direction'  => 'left',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '91,202',
                'label'      => 43,
                'direction'  => 'top',
                'color'      => '126,211,33',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '864,70',
                'label'      => 27,
                'direction'  => 'left',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '840,385',
                'label'      => 30,
                'direction'  => 'left',
                'color'      => '0,0,0',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '854,404',
                'label'      => 31,
                'direction'  => 'right',
                'color'      => '0,0,0',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '831,417',
                'label'      => 32,
                'direction'  => 'left',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1378,70',
                'label'      => 16,
                'direction'  => 'right',
                'color'      => '255,255,255',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1402,285',
                'label'      => 19,
                'direction'  => 'right',
                'color'      => '0,0,0',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1388,404',
                'label'      => 20,
                'direction'  => 'left',
                'color'      => '0,0,0',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1411,417',
                'label'      => 21,
                'direction'  => 'right',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '572,285',
                'label'      => 22,
                'direction'  => 'top',
                'color'      => '189,16,224',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '571,354',
                'label'      => 25,
                'direction'  => 'left',
                'color'      => '2,31,87',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '581,452',
                'label'      => 26,
                'direction'  => 'left',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '739,535',
                'label'      => 72,
                'direction'  => 'top',
                'color'      => '253,106,203',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1497,570',
                'label'      => 52,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1447,579',
                'label'      => 53,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1399,538',
                'label'      => 54,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1383,601',
                'label'      => 55,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1331,600',
                'label'      => 56,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1292,610',
                'label'      => 57,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1243,562',
                'label'      => 58,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1216,634',
                'label'      => 59,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1181,631',
                'label'      => 60,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1113,637',
                'label'      => 61,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1044,631',
                'label'      => 63,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1009,634',
                'label'      => 64,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '982,564',
                'label'      => 65,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '932,612',
                'label'      => 66,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '893,602',
                'label'      => 67,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '842,603',
                'label'      => 68,
                'direction'  => 'bottom',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '826,540',
                'label'      => 69,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '777,581',
                'label'      => 66,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '728,572',
                'label'      => 67,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1655,283',
                'label'      => 11,
                'direction'  => 'top',
                'color'      => '189,16,224',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1654,352',
                'label'      => 14,
                'direction'  => 'right',
                'color'      => '2,31,87',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1645,450',
                'label'      => 15,
                'direction'  => 'right',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '736,817',
                'label'      => 106,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '659,855',
                'label'      => 107,
                'direction'  => 'top',
                'color'      => '245,166,35',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1492,815',
                'label'      => 81,
                'direction'  => 'top',
                'color'      => '248,231,28',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1569,853',
                'label'      => 82,
                'direction'  => 'top',
                'color'      => '245,166,35',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1569,853',
                'label'      => 117,
                'direction'  => 'bottom',
                'color'      => '208,2,27',
                'border'     => 'solid',
                'shape'      => 'circle',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：インナ：page4
            [
                'figure_id'  => 6,
                'point'      => '392,468',
                'label'      => 8,
                'direction'  => 'top',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '482,468',
                'label'      => 7,
                'direction'  => 'top',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1246,465',
                'label'      => 4,
                'direction'  => 'top',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'figure_id'  => 6,
                'point'      => '1336,466',
                'label'      => 3,
                'direction'  => 'top',
                'color'      => '74,144,226',
                'border'     => 'solid',
                'shape'      => 'square',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ：小部品：page1
        ];



        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}