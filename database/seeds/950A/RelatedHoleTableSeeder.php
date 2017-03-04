<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RelatedHoleTableSeeder
 */
class RelatedHoleTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create failure table seeder
         */
        $table_name = 'hole_types';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::connection('950A')->table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $partTypes = 

        $figures = collect(DB::connection('950A')
            ->table('figures')
            ->whereIn('inspection', ['ana', 'kashimego'])
            ->select(['id', 'pt_pn', 'process', 'inspection', 'page'])
            ->get());

        $getFigureId = function($pn, $p, $i, $pg) use($figures) {
            return $figures->first(function($key, $v) use ($pn, $p, $i, $pg) {
                return $v->pt_pn === $pn && $v->process === $p && $v->inspection === $i && $v->page === $pg;
            })->id;
        };


        //穴検査_ドアインナR
        $doorInnerR_holes = [
            [1, 1,  180,  473, 'right', 'square', 'solid', '95CDDC'],
            [1, 2,  183,  542, 'right', 'square', 'solid', '95CDDC'],
            [1, 3,  273,  267, 'top',   'square', 'solid', '95CDDC'],
            [1, 4,  137,  307, 'left',  'square', 'solid', '95CDDC'],
            [1, 5,  134,  483, 'left',  'square', 'solid', '95CDDC'],
            [1, 6,  127,  518, 'left',  'square', 'solid', '95CDDC'],
            [1, 7,  127,  595, 'left',  'square', 'solid', '95CDDC'],
            [1, 8,  133,  627, 'left',  'square', 'solid', '95CDDC'],
            [1, 9,  185,  852, 'bottom','square', 'solid', '95CDDC'],
            [1, 10, 453,  875, 'bottom','square', 'solid', '95CDDC'],
            [1, 11, 727,  869, 'bottom','square', 'solid', '95CDDC'],
            [1, 12, 1002, 865, 'bottom','square', 'solid', '95CDDC'],
            [1, 13, 1330, 795, 'bottom','square', 'solid', '95CDDC'],
            [1, 14, 1458, 679, 'right', 'square', 'solid', '95CDDC'],
            [1, 15, 1548, 532, 'right', 'square', 'solid', '95CDDC'],
            [1, 16, 1581, 361, 'right', 'square', 'solid', '95CDDC'],
            [1, 17, 1581, 247, 'right', 'square', 'solid', '95CDDC'],

            [2, 18, 217,  297, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 19, 208,  315, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 20, 1418, 239, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 21, 1590, 218, 'right', 'square', 'solid', 'B3A3C6'],

            [2, 22, 166,  262, 'right', 'square', 'solid', 'C3D59E'],

            [2, 23, 149,  305, 'right', 'square', 'solid', 'D89695'],
            [2, 24, 164,  372, 'right', 'square', 'solid', 'D89695'],
            [2, 25, 151,  452, 'right', 'square', 'solid', 'D89695'],
            [2, 26, 168,  560, 'right', 'square', 'solid', 'D89695'],
            [2, 27, 168,  700, 'right', 'square', 'solid', 'D89695'],
            [2, 28, 205,  796, 'right', 'square', 'solid', 'D89695'],

            [2, 29, 240,  773, 'right', 'square', 'solid', '938957'],
            [2, 30, 283,  791, 'right', 'square', 'solid', '938957'],
            [2, 31, 386,  799, 'right', 'square', 'solid', '938957'],
            [2, 32, 536,  800, 'right', 'square', 'solid', '938957'],
            [2, 33, 685,  800, 'right', 'square', 'solid', '938957'],
            [2, 34, 835,  800, 'right', 'square', 'solid', '938957'],
            [2, 35, 984,  800, 'right', 'square', 'solid', '938957'],
            [2, 36, 1134, 800, 'right', 'square', 'solid', '938957'],
            [2, 37, 1202, 800, 'right', 'square', 'solid', '938957'],
            [2, 38, 272,  840, 'right', 'square', 'solid', '938957'],
            [2, 39, 460,  840, 'right', 'square', 'solid', '938957'],
            [2, 40, 591,  833, 'right', 'square', 'solid', '938957'],
            [2, 41, 751,  833, 'right', 'square', 'solid', '938957'],
            [2, 42, 908,  833, 'right', 'square', 'solid', '938957'],
            [2, 43, 1024, 833, 'right', 'square', 'solid', '938957'],
            [2, 44, 1179, 823, 'right', 'square', 'solid', '938957'],
            [2, 45, 1328, 745, 'right', 'square', 'solid', '938957'],
            [2, 46, 1411, 663, 'right', 'square', 'solid', '938957'],
            [2, 47, 1501, 531, 'right', 'square', 'solid', '938957'],
            [2, 48, 1533, 385, 'right', 'square', 'solid', '938957'],
            [2, 49, 1536, 291, 'right', 'square', 'solid', '938957'],

            [3, 50, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 51, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 52, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 53, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 54, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 55, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 56, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 57, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 58, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 59, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 60, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 61, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 62, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 63, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 64, 1740, 900, 'right', 'square', 'solid', 'E26C22'],
            // [3, 65, 1740, 900, 'right', 'square', 'solid', 'E26C22'],

            // [3, 66, 1740, 900, 'right', 'square', 'solid', '36859A'],
            // [3, 67, 1740, 900, 'right', 'square', 'solid', '36859A'],
            // [3, 68, 1740, 900, 'right', 'square', 'solid', '36859A'],

            // [3, 69, 1740, 900, 'right', 'square', 'solid', '604B7A'],
            // [3, 70, 1740, 900, 'right', 'square', 'solid', '604B7A'],

            [4, 71, 1740, 900, 'right', 'square', 'solid', '779242'],
            // [4, 72, 1740, 900, 'right', 'square', 'solid', '779242'],
            // [4, 73, 1740, 900, 'right', 'square', 'solid', '779242'],

            // [4, 74, 1740, 900, 'right', 'square', 'solid', '933837'],
            // [4, 75, 1740, 900, 'right', 'square', 'solid', '933837'],

            // [4, 76, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 77, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 78, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 79, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 80, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 81, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 82, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 83, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 84, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 85, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [4, 86, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],

            [5, 87, 1740, 900, 'right', 'square', 'solid', 'FDBF2D'],
            // [5, 88, 1740, 900, 'right', 'square', 'solid', 'FDBF2D'],

            // [5, 89, 1740, 900, 'right', 'square', 'solid', '964814'],
            // [5, 90, 1740, 900, 'right', 'square', 'solid', '964814'],

            // [5, 91, 1740, 900, 'right', 'square', 'solid', '1AAF54'],
            // [5, 92, 1740, 900, 'right', 'square', 'solid', '1AAF54'],

            // [5, 93, 1740, 900, 'right', 'square', 'solid', 'DB0B17'],
            // [5, 94, 1740, 900, 'right', 'square', 'solid', 'DB0B17'],
            // [5, 95, 1740, 900, 'right', 'square', 'solid', 'DB0B17'],

            // [5, 96, 1740, 900, 'right', 'square', 'solid', 'FFFFFF'],

            // [5, 97, 1740, 900, 'right', 'square', 'solid', '90E941'],

            // [5, 98, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],

            // [5, 99, 1740, 900, 'right', 'square', 'solid', 'FBD5B7'],

            [6, 100, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],
            // [6, 101, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],

            // [6, 102, 1740, 900, 'right', 'square', 'solid', '000000'],

            // [6, 103, 1740, 900, 'right', 'square', 'solid', 'F5964F'],
            // [6, 104, 1740, 900, 'right', 'square', 'solid', 'F5964F'],

            // [6, 105, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [6, 106, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [6, 107, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],
            // [6, 108, 1740, 900, 'right', 'square', 'solid', 'FFFD38'],

            // [6, 109, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [6, 110, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [6, 111, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [6, 112, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [6, 113, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [6, 114, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [6, 115, 1740, 900, 'right', 'square', 'solid', 'D89695'],

            // [6, 116, 1740, 900, 'right', 'square', 'solid', '29FD2F'],

            [7, 117, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],
            // [7, 118, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],
            // [7, 119, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],
            // [7, 120, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],
            // [7, 121, 1740, 900, 'right', 'square', 'solid', 'B3A3C6'],

            // [7, 122, 1740, 900, 'right', 'square', 'solid', 'C7DAF0'],
            // [7, 123, 1740, 900, 'right', 'square', 'solid', 'C7DAF0'],
            // [7, 124, 1740, 900, 'right', 'square', 'solid', 'C7DAF0'],
            // [7, 125, 1740, 900, 'right', 'square', 'solid', 'C7DAF0'],

            // [7, 126, 1740, 900, 'right', 'square', 'solid', 'C3D59E'],
            // [7, 127, 1740, 900, 'right', 'square', 'solid', 'C3D59E'],

            // [7, 128, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [7, 129, 1740, 900, 'right', 'square', 'solid', 'D89695'],
            // [7, 130, 1740, 900, 'right', 'square', 'solid', 'D89695'],

            // [7, 131, 1740, 900, 'right', 'square', 'solid', 'FDBF2D']
        ];

        //穴検査_ドアインナR
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$doorInnerR_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴検査_ドアインナL
        $data = array_map(function($h) use($getFigureId, $now) {
            if ($h[4] === 'left') {
                $direction = 'right';
            } elseif ($h[4] === 'right') {
                $direction = 'left';
            } else {
                $direction = $h[4];
            }

            return [
                'label'      => $h[1],
                'x'          => 1740 - $h[2],
                'y'          => $h[3],
                'direction'  => $direction,
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6714211020,
                'figure_id'  => $getFigureId(6714211020, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$doorInnerR_holes);
        DB::connection('950A')->table($table_name)->insert($data);



        $data = [
            [
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6715111020,
                'figure_id'  => $getFigureId(6715111020, 'holing', 'ana', 1),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6715111020,
                'figure_id'  => $getFigureId(6715111020, 'holing', 'ana', 2),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6715211020,
                'figure_id'  => $getFigureId(6715211020, 'holing', 'ana', 1),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6715211020,
                'figure_id'  => $getFigureId(6715211020, 'holing', 'ana', 2),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441211010,
                'figure_id'  => $getFigureId(6441211010, 'holing', 'ana', 1),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441211010,
                'figure_id'  => $getFigureId(6441211010, 'holing', 'ana', 2),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441211010,
                'figure_id'  => $getFigureId(6441211010, 'holing', 'ana', 3),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441211020,
                'figure_id'  => $getFigureId(6441211020, 'holing', 'ana', 1),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441211020,
                'figure_id'  => $getFigureId(6441211020, 'holing', 'ana', 2),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441211020,
                'figure_id'  => $getFigureId(6441211020, 'holing', 'ana', 3),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441111010,
                'figure_id'  => $getFigureId(6441111010, 'holing', 'ana', 1),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441111020,
                'figure_id'  => $getFigureId(6441111020, 'holing', 'ana', 1),
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6441111020,
                'figure_id'  => $getFigureId(6441111020, 'holing', 'ana', 2),
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}