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
            [2, 20, 1418, 239, 'top',   'square', 'solid', 'B3A3C6'],
            [2, 21, 1590, 218, 'top',   'square', 'solid', 'B3A3C6'],

            [2, 22, 166,  262, 'top',   'square', 'solid', 'C3D59E'],

            [2, 23, 149,  305, 'left',  'square', 'solid', 'D89695'],
            [2, 24, 164,  372, 'left',  'square', 'solid', 'D89695'],
            [2, 25, 151,  452, 'left',  'square', 'solid', 'D89695'],
            [2, 26, 168,  560, 'left',  'square', 'solid', 'D89695'],
            [2, 27, 168,  700, 'left',  'square', 'solid', 'D89695'],
            [2, 28, 205,  796, 'left',  'square', 'solid', 'D89695'],

            [2, 29, 240,  773, 'top',   'square', 'solid', '938957'],
            [2, 30, 283,  791, 'top',   'square', 'solid', '938957'],
            [2, 31, 386,  799, 'top',   'square', 'solid', '938957'],
            [2, 32, 536,  800, 'top',   'square', 'solid', '938957'],
            [2, 33, 685,  800, 'top',   'square', 'solid', '938957'],
            [2, 34, 835,  800, 'top',   'square', 'solid', '938957'],
            [2, 35, 984,  800, 'top',   'square', 'solid', '938957'],
            [2, 36, 1134, 800, 'top',   'square', 'solid', '938957'],
            [2, 37, 1202, 800, 'top',   'square', 'solid', '938957'],
            [2, 38, 272,  840, 'bottom','square', 'solid', '938957'],
            [2, 39, 460,  840, 'bottom','square', 'solid', '938957'],
            [2, 40, 591,  833, 'bottom','square', 'solid', '938957'],
            [2, 41, 751,  833, 'bottom','square', 'solid', '938957'],
            [2, 42, 908,  833, 'bottom','square', 'solid', '938957'],
            [2, 43, 1024, 833, 'bottom','square', 'solid', '938957'],
            [2, 44, 1179, 823, 'bottom','square', 'solid', '938957'],
            [2, 45, 1328, 745, 'right', 'square', 'solid', '938957'],
            [2, 46, 1411, 663, 'right', 'square', 'solid', '938957'],
            [2, 47, 1501, 531, 'right', 'square', 'solid', '938957'],
            [2, 48, 1533, 385, 'right', 'square', 'solid', '938957'],
            [2, 49, 1536, 291, 'right', 'square', 'solid', '938957'],

            [3, 50, 250,  243, 'top',   'square', 'solid', 'E26C22'],
            [3, 51, 358,  245, 'top',   'square', 'solid', 'E26C22'],
            [3, 52, 556,  233, 'top',   'square', 'solid', 'E26C22'],
            [3, 53, 766,  220, 'top',   'square', 'solid', 'E26C22'],
            [3, 54, 958,  212, 'top',   'square', 'solid', 'E26C22'],
            [3, 55, 1140, 202, 'top',   'square', 'solid', 'E26C22'],
            [3, 56, 1317, 193, 'top',   'square', 'solid', 'E26C22'],
            [3, 57, 1445, 187, 'top',   'square', 'solid', 'E26C22'],
            [3, 58, 243,  404, 'top',   'square', 'solid', 'E26C22'],
            [3, 59, 404,  393, 'top',   'square', 'solid', 'E26C22'],
            [3, 60, 612,  379, 'top',   'square', 'solid', 'E26C22'],
            [3, 61, 742,  369, 'top',   'square', 'solid', 'E26C22'],
            [3, 62, 902,  360, 'top',   'square', 'solid', 'E26C22'],
            [3, 63, 1117, 348, 'top',   'square', 'solid', 'E26C22'],
            [3, 64, 1270, 341, 'top',   'square', 'solid', 'E26C22'],
            [3, 65, 1450, 337, 'top',   'square', 'solid', 'E26C22'],

            [3, 66, 1533, 249, 'right', 'square', 'solid', '36859A'],
            [3, 67, 1414, 427, 'right', 'square', 'solid', '36859A'],
            [3, 68, 1394, 473, 'right', 'square', 'solid', '36859A'],

            [3, 69, 216,  436, 'left',  'square', 'solid', '604B7A'],
            [3, 70, 216,  526, 'left',  'square', 'solid', '604B7A'],

            [4, 71, 246,  279, 'top',   'square', 'solid', '779242'],
            [4, 72, 1570, 209, 'top',   'square', 'solid', '779242'],
            [4, 73, 208,  646, 'left',  'square', 'solid', '779242'],

            [4, 74, 265,  351, 'right', 'square', 'solid', '933837'],
            [4, 75, 1208, 275, 'top',   'square', 'solid', '933837'],

            [4, 76, 239,  317, 'left',  'square', 'solid', 'FFFD38'],
            [4, 77, 226,  373, 'left',  'square', 'solid', 'FFFD38'],
            [4, 78, 217,  698, 'left',  'square', 'solid', 'FFFD38'],
            [4, 79, 378,  418, 'top',   'square', 'solid', 'FFFD38'],
            [4, 80, 522,  497, 'top',   'square', 'solid', 'FFFD38'],
            [4, 81, 541,  755, 'bottom','square', 'solid', 'FFFD38'],
            [4, 82, 802,  757, 'bottom','square', 'solid', 'FFFD38'],
            [4, 83, 1051, 749, 'bottom','square', 'solid', 'FFFD38'],
            [4, 84, 1305, 691, 'right', 'square', 'solid', 'FFFD38'],
            [4, 85, 1368, 498, 'right', 'square', 'solid', 'FFFD38'],
            [4, 86, 1415, 304, 'right', 'square', 'solid', 'FFFD38'],

            [5, 87, 458,  510, 'left',  'square', 'solid', 'FDBF2D'],
            [5, 88, 1093, 400, 'top',   'square', 'solid', 'FDBF2D'],

            [5, 89, 554,  482, 'top',   'square', 'solid', '964814'],
            [5, 90, 561,  557, 'bottom','square', 'solid', '964814'],

            [5, 91, 755,  756, 'bottom','square', 'solid', '1AAF54'],
            [5, 92, 945,  756, 'bottom','square', 'solid', '1AAF54'],

            [5, 93, 1510, 424, 'right', 'square', 'solid', 'DB0B17'],
            [5, 94, 1494, 489, 'right', 'square', 'solid', 'DB0B17'],
            [5, 95, 1519, 489, 'right', 'square', 'solid', 'DB0B17'],

            [5, 96, 1446, 519, 'bottom','square', 'solid', 'FFFFFF'],

            [5, 97, 1406, 379, 'top',   'square', 'solid', '90E941'],

            [5, 98, 464,  443, 'top',   'square', 'solid', 'B3A3C6'],

            [5, 99, 1122, 649, 'top',   'square', 'solid', 'FBD5B7'],

            [6, 100, 159,  527, 'left',  'square', 'solid', 'B3A3C6'],
            [6, 101, 159,  587, 'left',  'square', 'solid', 'B3A3C6'],

            [6, 102, 1178, 376, 'top',   'square', 'solid', '000000'],

            [6, 103, 413,  501, 'right', 'square', 'solid', 'F5964F'],
            [6, 104, 583,  466, 'right', 'square', 'solid', 'F5964F'],

            [6, 105, 518,  308, 'top',   'square', 'solid', 'FFFD38'],
            [6, 106, 614,  422, 'top',   'square', 'solid', 'FFFD38'],
            [6, 107, 531,  537, 'bottom','square', 'solid', 'FFFD38'],
            [6, 108, 1048, 541, 'bottom','square', 'solid', 'FFFD38'],

            [6, 109, 481,  341, 'left',  'square', 'solid', 'D89695'],
            [6, 110, 754,  511, 'top',   'square', 'solid', 'D89695'],
            [6, 111, 727,  635, 'bottom','square', 'solid', 'D89695'],
            [6, 112, 856,  608, 'bottom','square', 'solid', 'D89695'],
            [6, 113, 1159, 601, 'bottom','square', 'solid', 'D89695'],
            [6, 114, 1313, 508, 'right', 'square', 'solid', 'D89695'],
            [6, 115, 1308, 382, 'top',   'square', 'solid', 'D89695'],

            [6, 116, 1088, 382, 'top',   'square', 'solid', '29FD2F'],

            [7, 117, 459,  396, 'top',   'square', 'solid', 'B3A3C6'],
            [7, 118, 497,  511, 'top',   'square', 'solid', 'B3A3C6'],
            [7, 119, 596,  523, 'top',   'square', 'solid', 'B3A3C6'],
            [7, 120, 630,  542, 'top',   'square', 'solid', 'B3A3C6'],
            [7, 121, 787,  543, 'top',   'square', 'solid', 'B3A3C6'],

            [7, 122, 249,  526, 'left',  'square', 'solid', 'C7DAF0'],
            [7, 123, 265,  740, 'left',  'square', 'solid', 'C7DAF0'],
            [7, 124, 498,  583, 'left',  'square', 'solid', 'C7DAF0'],
            [7, 125, 488,  760, 'bottom','square', 'solid', 'C7DAF0'],

            [7, 126, 540,  661, 'bottom','square', 'solid', 'C3D59E'],
            [7, 127, 694,  605, 'bottom','square', 'solid', 'C3D59E'],

            [7, 128, 1143, 401, 'top',   'square', 'solid', 'D89695'],
            [7, 129, 1156, 541, 'bottom','square', 'solid', 'D89695'],
            [7, 130, 1282, 488, 'right', 'square', 'solid', 'D89695'],

            [7, 131, 980,  511, 'top',   'square', 'solid', 'FDBF2D']
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