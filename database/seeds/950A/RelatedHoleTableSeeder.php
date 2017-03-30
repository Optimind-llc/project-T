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
            [1, 5,  134,  473, 'top',   'square', 'solid', '95CDDC'],
            [1, 6,  127,  518, 'left',  'square', 'solid', '95CDDC'],
            [1, 7,  127,  595, 'left',  'square', 'solid', '95CDDC'],
            [1, 8,  133,  637, 'bottom','square', 'solid', '95CDDC'],
            [1, 9,  185,  852, 'bottom','square', 'solid', '95CDDC'],
            [1, 10, 453,  875, 'bottom','square', 'solid', '95CDDC'],
            [1, 11, 727,  869, 'bottom','square', 'solid', '95CDDC'],
            [1, 12, 1002, 865, 'bottom','square', 'solid', '95CDDC'],
            [1, 13, 1330, 795, 'bottom','square', 'solid', '95CDDC'],
            [1, 14, 1458, 679, 'right', 'square', 'solid', '95CDDC'],
            [1, 15, 1548, 532, 'right', 'square', 'solid', '95CDDC'],
            [1, 16, 1581, 361, 'right', 'square', 'solid', '95CDDC'],
            [1, 17, 1581, 247, 'right', 'square', 'solid', '95CDDC'],

            [2, 18, 217,  274, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 19, 208,  325, 'right', 'square', 'solid', 'B3A3C6'],
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
            [2, 37, 1212, 800, 'top',   'square', 'solid', '938957'],
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

            [4, 71, 246,  270, 'top',   'square', 'solid', '779242'],
            [4, 72, 1570, 209, 'top',   'square', 'solid', '779242'],
            [4, 73, 208,  646, 'left',  'square', 'solid', '779242'],

            [4, 74, 275,  351, 'right', 'square', 'solid', '933837'],
            [4, 75, 1208, 275, 'top',   'square', 'solid', '933837'],

            [4, 76, 239,  317, 'left',  'square', 'solid', 'FFFD38'],
            [4, 77, 226,  373, 'left',  'square', 'solid', 'FFFD38'],
            [4, 78, 217,  698, 'left',  'square', 'solid', 'FFFD38'],
            [4, 79, 378,  418, 'bottom','square', 'solid', 'FFFD38'],
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
            [5, 94, 1494, 500, 'bottom','square', 'solid', 'DB0B17'],
            [5, 95, 1519, 469, 'right', 'square', 'solid', 'DB0B17'],

            [5, 96, 1446, 519, 'left',  'square', 'solid', 'FFFFFF'],

            [5, 97, 1406, 379, 'top',   'square', 'solid', '90E941'],

            [5, 98, 464,  443, 'top',   'square', 'solid', 'B3A3C6'],

            [5, 99, 1122, 649, 'top',   'square', 'solid', 'FBD5B7'],

            [6, 100, 159,  527, 'left',  'square', 'solid', 'B3A3C6'],
            [6, 101, 159,  587, 'left',  'square', 'solid', 'B3A3C6'],

            [6, 102, 1178, 376, 'top',   'square', 'solid', '000000'],

            [6, 103, 413,  501, 'left',  'square', 'solid', 'F5964F'],
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
            [7, 120, 630,  542, 'right', 'square', 'solid', 'B3A3C6'],
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


        //穴検査_リンフォースR
        $reinforceR_holes = [
            [8, 1,  137,  247, 'top',   'square', 'solid', '95CDDC'],
            [8, 2,  269,  248, 'top',   'square', 'solid', '95CDDC'],
            [8, 3,  514,  233, 'top',   'square', 'solid', '95CDDC'],
            [8, 4,  772,  218, 'top',   'square', 'solid', '95CDDC'],
            [8, 5,  1010, 209, 'top',   'square', 'solid', '95CDDC'],
            [8, 6,  1233, 194, 'top',   'square', 'solid', '95CDDC'],
            [8, 7,  1451, 184, 'top',   'square', 'solid', '95CDDC'],
            [8, 8,  1590, 177, 'top',   'square', 'solid', '95CDDC'],
            [8, 9,  128,  446, 'bottom','square', 'solid', '95CDDC'],
            [8, 10, 327,  433, 'bottom','square', 'solid', '95CDDC'],
            [8, 11, 582,  414, 'bottom','square', 'solid', '95CDDC'],
            [8, 12, 743,  403, 'bottom','square', 'solid', '95CDDC'],
            [8, 13, 940,  390, 'bottom','square', 'solid', '95CDDC'],
            [8, 14, 1205, 376, 'bottom','square', 'solid', '95CDDC'],
            [8, 15, 1394, 368, 'bottom','square', 'solid', '95CDDC'],
            [8, 16, 1616, 364, 'bottom','square', 'solid', '95CDDC'],

            [8, 17, 1635, 188, 'bottom','square', 'solid', 'DB0B17'],
            [8, 18, 1677, 188, 'right', 'square', 'solid', '000000'],

            [9, 19, 801,  840, 'bottom','square', 'solid', 'FFFD38'],
            [9, 20, 878,  833, 'bottom','square', 'solid', 'FFFD38'],
            [9, 21, 920,  679, 'top',   'square', 'solid', 'FFFD38'],
            [9, 22, 324,  300, 'bottom','square', 'solid', 'FFFD38'],
            [9, 23, 391,  314, 'bottom','square', 'solid', 'FFFD38'],
            [9, 24, 1450, 238, 'bottom','square', 'solid', 'FFFD38'],
            [9, 25, 1515, 256, 'bottom','square', 'solid', 'FFFD38'],
            [9, 26, 1681, 262, 'right', 'square', 'solid', 'FFFD38'],
            [9, 27, 1681, 327, 'right', 'square', 'solid', 'FFFD38'],

            [9, 28, 880,  719, 'top',   'square', 'solid', 'C3D59E'],

            [9, 29, 824,  792, 'top',   'square', 'solid', 'B3A3C6'],

            [9, 30, 785,  775, 'left',  'square', 'solid', 'DB0B17'],
            [9, 31, 895,  760, 'right', 'square', 'solid', 'DB0B17'],

            [9, 32, 168,  349, 'bottom', 'square', 'solid', '36859A'],
            [9, 33, 922,  208, 'top',   'square', 'solid', '36859A'],
            [9, 34, 1085, 200, 'top',   'square', 'solid', '36859A'],
            [9, 35, 1636, 221, 'top',   'square', 'solid', '36859A'],
        ];

        //穴検査_リンフォースR
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6715111020,
                'figure_id'  => $getFigureId(6715111020, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$reinforceR_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴検査_リンフォースL
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
                'pt_pn'      => 6715211020,
                'figure_id'  => $getFigureId(6715211020, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$reinforceR_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴検査_ラゲージインナSTD 古いやつ
        // $luggageInnerSTD_holes = [
        //     [1, 1,  1005, 365, 'top',   'square', 'solid', 'F5964F'],
        //     [1, 2,  737,  365, 'top',   'square', 'solid', 'F5964F'],
        //     [1, 3,  1078, 713, 'top',   'square', 'solid', 'F5964F'],
        //     [1, 4,  1029, 714, 'top',   'square', 'solid', 'F5964F'],
        //     [1, 5,  1063, 773, 'bottom','square', 'solid', 'F5964F'],

        //     [1, 6,  1500, 254, 'right', 'square', 'solid', '95CDDC'],
        //     [1, 7,  1521, 383, 'right', 'square', 'solid', '95CDDC'],
        //     [1, 8,  239,  257, 'left',  'square', 'solid', '95CDDC'],
        //     [1, 9,  218,  386, 'left',  'square', 'solid', '95CDDC'],
        //     [1, 10, 955,  874, 'bottom','square', 'solid', '95CDDC'],
        //     [1, 11, 783,  861, 'bottom','square', 'solid', '95CDDC'],

        //     [1, 12, 1498, 299, 'right', 'square', 'solid', 'B3A3C'],
        //     [1, 13, 1528, 444, 'right', 'square', 'solid', 'B3A3C6'],
        //     [1, 14, 240,  303, 'left',  'square', 'solid', 'B3A3C6'],
        //     [1, 15, 211,  447, 'left',  'square', 'solid', 'B3A3C6'],

        //     [1, 16, 895,  874, 'top',   'square', 'solid', 'DB0B17'],
        //     [1, 17, 845,  874, 'top',   'square', 'solid', 'DB0B17'],

        //     [1, 18, 1401, 335, 'top',   'square', 'solid', 'C3D59E'],
        //     [1, 19, 1047, 357, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 20, 692,  359, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 21, 338,  338, 'top',   'square', 'solid', 'C3D59E'],
        //     [1, 22, 1416, 653, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 23, 1088, 819, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 24, 1015, 843, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 25, 725,  843, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 26, 654,  819, 'bottom','square', 'solid', 'C3D59E'],
        //     [1, 27, 325,  656, 'bottom','square', 'solid', 'C3D59E'],

        //     [2, 28, 1580, 624, 'bottom','square', 'solid', 'D89695'],
        //     [2, 29, 1089, 818, 'bottom','square', 'solid', 'D89695'],
        //     [2, 30, 652,  818, 'bottom','square', 'solid', 'D89695'],
        //     [2, 31, 161,  629, 'bottom','square', 'solid', 'D89695'],

        //     [2, 32, 913,  513, 'top',   'square', 'solid', 'C7DAF0'],
        //     [2, 33, 826,  513, 'top',   'square', 'solid', 'C7DAF0'],
        //     [2, 34, 913,  599, 'bottom','square', 'solid', 'C7DAF0'],
        //     [2, 35, 827,  599, 'bottom','square', 'solid', 'C7DAF0'],

        //     [2, 36, 1117, 639, 'right','square', 'solid', 'D9D9D9'],
        //     [2, 37, 1022, 666, 'right','square', 'solid', 'D9D9D9'],
        //     [2, 38, 1126, 768, 'right','square', 'solid', 'D9D9D9'],
        //     [2, 39, 999,  794, 'right','square', 'solid', 'D9D9D9'],
        //     [2, 40, 720,  666, 'left','square', 'solid', 'D9D9D9'],
        //     [2, 41, 626,  640, 'left','square', 'solid', 'D9D9D9'],
        //     [2, 42, 741,  794, 'left','square', 'solid', 'D9D9D9'],
        //     [2, 43, 615,  770, 'left','square', 'solid', 'D9D9D9'],

        //     [3, 44, 1374, 482, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 45, 1294, 538, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 46, 1090, 582, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 47, 983,  632, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 48, 913,  659, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 49, 823,  649, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 50, 651,  586, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 51, 447,  539, 'top',  'square', 'solid', 'E26C22'],
        //     [3, 52, 366,  484, 'top',  'square', 'solid', 'E26C22'],

        //     [3, 53, 1123, 899, 'bottom','square', 'solid', 'C7DAF0'],
        //     [3, 54, 619,  901, 'bottom','square', 'solid', 'C7DAF0'],

        //     [3, 55, 1160, 867, 'top',  'square', 'solid', 'FFFD38'],
        //     [3, 56, 581,  870, 'top',  'square', 'solid', 'FFFD38'],

        //     [3, 57, 1383, 675, 'bottom','square', 'solid', 'B3A3C6'],
        //     [3, 58, 358,  678, 'bottom','square', 'solid', 'B3A3C6']
        // ];

        //穴検査_ラゲージインナSTD 新しいやつ
        $luggageInnerSTD_holes = [
            [1, 1,  995,  350, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 2,  734,  351, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 3,  1091, 709, 'right', 'square', 'solid', 'FBD5B7'],
            [1, 4,  1000, 708, 'left',  'square', 'solid', 'FBD5B7'],
            [1, 5,  1415, 529, 'right', 'square', 'solid', 'FBD5B7'],
            [1, 6,  1349, 709, 'right', 'square', 'solid', 'FBD5B7'],
            [1, 7,  1231, 336, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 8,  1056, 621, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 9,  1055, 763, 'bottom','square', 'solid', 'FBD5B7'],
            [1, 10, 899,  356, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 11, 867,  649, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 12, 675,  621, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 13, 679,  764, 'bottom','square', 'solid', 'FBD5B7'],
            [1, 14, 503,  336, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 15, 388,  709, 'left',  'square', 'solid', 'FBD5B7'],
            [1, 16, 327,  528, 'left',  'square', 'solid', 'FBD5B7'],

            [2, 17, 1481, 249, 'right', 'square', 'solid', '95CDDC'],
            [2, 18, 1503, 379, 'right', 'square', 'solid', '95CDDC'],
            [2, 19, 255,  250, 'left',  'square', 'solid', '95CDDC'],
            [2, 20, 235,  379, 'left',  'square', 'solid', '95CDDC'],
            [2, 21, 949,  869, 'bottom','square', 'solid', '95CDDC'],
            [2, 22, 782,  853, 'bottom','square', 'solid', '95CDDC'],

            [2, 23, 1509, 441, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 24, 1480, 295, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 25, 257,  295, 'left',  'square', 'solid', 'B3A3C6'],
            [2, 26, 228,  442, 'left',  'square', 'solid', 'B3A3C6'],
            [2, 27, 890,  866, 'bottom','square', 'solid', 'B3A3C6'],
            [2, 28, 842,  866, 'bottom','square', 'solid', 'B3A3C6'],

            [2, 29, 1388, 337, 'top',   'square', 'solid', 'C3D59E'],
            [2, 30, 1038, 348, 'top',   'square', 'solid', 'C3D59E'],
            [2, 31, 733,  350, 'top',   'square', 'solid', 'C3D59E'],
            [2, 32, 503,  335, 'top',   'square', 'solid', 'C3D59E'],
            [2, 33, 1401, 652, 'top',   'square', 'solid', 'C3D59E'],
            [2, 34, 1011, 843, 'top',   'square', 'solid', 'C3D59E'],
            [2, 35, 728,  843, 'top',   'square', 'solid', 'C3D59E'],
            [2, 36, 339,  652, 'top',   'square', 'solid', 'C3D59E'],
            [2, 37, 1110, 790, 'top',   'square', 'solid', 'C3D59E'],
            [2, 38, 627,  790, 'top',   'square', 'solid', 'C3D59E'],

            [3, 39, 1559, 621, 'bottom','square', 'solid', 'D89695'],
            [3, 40, 1083, 818, 'bottom','square', 'solid', 'D89695'],
            [3, 41, 658,  817, 'bottom','square', 'solid', 'D89695'],
            [3, 42, 179,  622, 'bottom','square', 'solid', 'D89695'],

            [3, 43, 905,  496, 'top',   'square', 'solid', '1EB1ED'],
            [3, 44, 819,  496, 'top',   'square', 'solid', '1EB1ED'],
            [3, 45, 820,  580, 'bottom','square', 'solid', '1EB1ED'],
            [3, 46, 905,  580, 'bottom','square', 'solid', '1EB1ED'],

            [3, 47, 1301, 696, 'bottom','square', 'solid', '9B9B9B'],
            [3, 48, 1286, 594, 'top',   'square', 'solid', '9B9B9B'],
            [3, 49, 1113, 755, 'top',   'square', 'solid', '9B9B9B'],
            [3, 50, 1104, 630, 'top',   'square', 'solid', '9B9B9B'],
            [3, 51, 624,  630, 'top',   'square', 'solid', '9B9B9B'],
            [3, 52, 613,  756, 'top'   ,'square', 'solid', '9B9B9B'],
            [3, 53, 446,  595, 'top',   'square', 'solid', '9B9B9B'],
            [3, 54, 429,  697, 'bottom','square', 'solid', '9B9B9B'],

            [4, 55, 1356, 473, 'top',   'square', 'solid', 'E26C22'],
            [4, 56, 1276, 525, 'top',   'square', 'solid', 'E26C22'],
            [4, 57, 1076, 567, 'top',   'square', 'solid', 'E26C22'],
            [4, 58, 975,  618, 'top',   'square', 'solid', 'E26C22'],
            [4, 59, 910,  654, 'top',   'square', 'solid', 'E26C22'],
            [4, 60, 822,  642, 'top',   'square', 'solid', 'E26C22'],
            [4, 61, 648,  568, 'top',   'square', 'solid', 'E26C22'],
            [4, 62, 451,  526, 'top',   'square', 'solid', 'E26C22'],
            [4, 63, 327,  528, 'top',   'square', 'solid', 'E26C22'],

            [4, 64, 1106, 900, 'bottom','square', 'solid', '36859A'],
            [4, 65, 636,  900, 'bottom','square', 'solid', '36859A'],

            [4, 66, 1435, 246, 'top',   'square', 'solid', '604B7A'],
            [4, 67, 305,  246, 'top',   'square', 'solid', '604B7A'],

            [4, 68, 1144, 883, 'top',   'square', 'solid', '1AAF54'],
            [4, 69, 600,  883, 'top',   'square', 'solid', '1AAF54'],
        ];

        //穴検査_ラゲージインナSTD
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441211010,
                'figure_id'  => $getFigureId(6441211010, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageInnerSTD_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴検査_ラゲージインナARW
        $luggageInnerARW_holes = [
            [1, 1,  995,  350, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 2,  734,  351, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 3,  1091, 709, 'right', 'square', 'solid', 'FBD5B7'],
            [1, 4,  1000, 708, 'left',  'square', 'solid', 'FBD5B7'],
            [1, 5,  1415, 529, 'right', 'square', 'solid', 'FBD5B7'],
            [1, 6,  1349, 709, 'right', 'square', 'solid', 'FBD5B7'],
            [1, 7,  1231, 336, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 8,  1056, 621, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 9,  1055, 763, 'bottom','square', 'solid', 'FBD5B7'],
            [1, 10, 899,  356, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 11, 867,  649, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 12, 675,  621, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 13, 679,  764, 'bottom','square', 'solid', 'FBD5B7'],
            [1, 14, 503,  336, 'top',   'square', 'solid', 'FBD5B7'],
            [1, 15, 388,  709, 'left',  'square', 'solid', 'FBD5B7'],
            [1, 16, 327,  528, 'left',  'square', 'solid', 'FBD5B7'],

            [2, 17, 1481, 249, 'right', 'square', 'solid', '95CDDC'],
            [2, 18, 1503, 379, 'right', 'square', 'solid', '95CDDC'],
            [2, 19, 255,  250, 'left',  'square', 'solid', '95CDDC'],
            [2, 20, 235,  379, 'left',  'square', 'solid', '95CDDC'],
            [2, 21, 949,  869, 'bottom','square', 'solid', '95CDDC'],
            [2, 22, 782,  853, 'bottom','square', 'solid', '95CDDC'],

            [2, 23, 1509, 441, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 24, 1480, 295, 'right', 'square', 'solid', 'B3A3C6'],
            [2, 25, 257,  295, 'left',  'square', 'solid', 'B3A3C6'],
            [2, 26, 228,  442, 'left',  'square', 'solid', 'B3A3C6'],
            [2, 27, 890,  866, 'bottom','square', 'solid', 'B3A3C6'],
            [2, 28, 842,  866, 'bottom','square', 'solid', 'B3A3C6'],

            [2, 29, 1388, 337, 'top',   'square', 'solid', 'C3D59E'],
            [2, 30, 1038, 348, 'top',   'square', 'solid', 'C3D59E'],
            [2, 31, 733,  350, 'top',   'square', 'solid', 'C3D59E'],
            [2, 32, 503,  335, 'top',   'square', 'solid', 'C3D59E'],
            [2, 33, 1401, 652, 'top',   'square', 'solid', 'C3D59E'],
            [2, 34, 1011, 843, 'top',   'square', 'solid', 'C3D59E'],
            [2, 35, 728,  843, 'top',   'square', 'solid', 'C3D59E'],
            [2, 36, 339,  652, 'top',   'square', 'solid', 'C3D59E'],
            [2, 37, 1110, 790, 'top',   'square', 'solid', 'C3D59E'],
            [2, 38, 627,  790, 'top',   'square', 'solid', 'C3D59E'],

            [3, 39, 1559, 621, 'bottom','square', 'solid', 'D89695'],
            [3, 40, 1083, 818, 'bottom','square', 'solid', 'D89695'],
            [3, 41, 658,  817, 'bottom','square', 'solid', 'D89695'],
            [3, 42, 179,  622, 'bottom','square', 'solid', 'D89695'],

            [3, 43, 905,  496, 'top',   'square', 'solid', '1EB1ED'],
            [3, 44, 819,  496, 'top',   'square', 'solid', '1EB1ED'],
            [3, 45, 820,  580, 'bottom','square', 'solid', '1EB1ED'],
            [3, 46, 905,  580, 'bottom','square', 'solid', '1EB1ED'],

            [3, 47, 1301, 696, 'bottom','square', 'solid', '9B9B9B'],
            [3, 48, 1286, 594, 'top',   'square', 'solid', '9B9B9B'],
            [3, 49, 1113, 755, 'top',   'square', 'solid', '9B9B9B'],
            [3, 50, 1104, 630, 'top',   'square', 'solid', '9B9B9B'],
            [3, 51, 624,  630, 'top',   'square', 'solid', '9B9B9B'],
            [3, 52, 613,  756, 'top'   ,'square', 'solid', '9B9B9B'],
            [3, 53, 446,  595, 'top',   'square', 'solid', '9B9B9B'],
            [3, 54, 429,  697, 'bottom','square', 'solid', '9B9B9B'],

            [4, 55, 1356, 473, 'top',   'square', 'solid', 'E26C22'],
            [4, 56, 1276, 525, 'top',   'square', 'solid', 'E26C22'],
            [4, 57, 1076, 567, 'top',   'square', 'solid', 'E26C22'],
            [4, 58, 975,  618, 'top',   'square', 'solid', 'E26C22'],
            [4, 59, 910,  654, 'top',   'square', 'solid', 'E26C22'],
            [4, 60, 822,  642, 'top',   'square', 'solid', 'E26C22'],
            [4, 61, 648,  568, 'top',   'square', 'solid', 'E26C22'],
            [4, 62, 451,  526, 'top',   'square', 'solid', 'E26C22'],
            [4, 63, 327,  528, 'top',   'square', 'solid', 'E26C22'],

            [4, 64, 1106, 900, 'bottom','square', 'solid', '36859A'],
            [4, 65, 636,  900, 'bottom','square', 'solid', '36859A'],

            [4, 66, 1435, 246, 'top',   'square', 'solid', '604B7A'],
            [4, 67, 305,  246, 'top',   'square', 'solid', '604B7A'],

            [4, 68, 1144, 883, 'top',   'square', 'solid', '1AAF54'],
            [4, 69, 600,  883, 'top',   'square', 'solid', '1AAF54'],
        ];

        //穴検査_ラゲージインナARW
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441211020,
                'figure_id'  => $getFigureId(6441211020, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageInnerARW_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴検査_ラゲージアウタSTD
        $luggageOuterSTD_holes = [
            [1, 1,  403,  264, 'top',   'square', 'solid', 'C3D59E'],
            [1, 2,  871,  332, 'top',   'square', 'solid', 'C3D59E'],
            [1, 3,  1336, 259, 'top',   'square', 'solid', 'C3D59E'],

            [1, 4,  626,  343, 'top',   'square', 'solid', 'C7DAF0'],
            [1, 5,  1115, 340, 'top',   'square', 'solid', 'C7DAF0'],

            [1, 6,  742,  356, 'top',   'square', 'solid', 'FDBF2D'],
            [1, 7,  999,  354, 'top',   'square', 'solid', 'FDBF2D'],

            [1, 8,  871,  872, 'top',   'square', 'solid', 'FD7D82'],

            [1, 9,  821,  897, 'left',  'square', 'solid', 'D9D9D9'],
            [1, 10, 931,  901, 'right', 'square', 'solid', 'D9D9D9']
        ];

        //穴検査_ラゲージアウタSTD
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441111010,
                'figure_id'  => $getFigureId(6441111010, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageOuterSTD_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴検査_ラゲージインナARW
        $luggageOuterARW_holes = [
            [1, 1,  403,  264, 'top',   'square', 'solid', 'C3D59E'],
            [1, 2,  871,  332, 'top',   'square', 'solid', 'C3D59E'],
            [1, 3,  1336, 259, 'top',   'square', 'solid', 'C3D59E'],

            [1, 4,  626,  343, 'top',   'square', 'solid', 'C7DAF0'],
            [1, 5,  1115, 340, 'top',   'square', 'solid', 'C7DAF0'],

            [1, 6,  742,  356, 'top',   'square', 'solid', 'FDBF2D'],
            [1, 7,  999,  354, 'top',   'square', 'solid', 'FDBF2D'],

            [1, 8,  871,  872, 'top',   'square', 'solid', 'FD7D82'],

            [1, 9,  821,  897, 'left',  'square', 'solid', 'D9D9D9'],
            [1, 10, 931,  901, 'right', 'square', 'solid', 'D9D9D9'],

            [2, 11, 336,  598, 'left',  'square', 'solid', 'D9D9D9'],
            [2, 12, 727,  651, 'top',   'square', 'solid', 'D9D9D9'],
            [2, 13, 750,  772, 'bottom','square', 'solid', 'D9D9D9'],
            [2, 14, 1017, 651, 'top',   'square', 'solid', 'D9D9D9'],
            [2, 15, 996,  772, 'bottom','square', 'solid', 'D9D9D9'],
            [2, 16, 1407, 597, 'right', 'square', 'solid', 'D9D9D9'],

            [2, 17, 459,  595, 'top',   'square', 'solid', '1EB1ED'],
            [2, 18, 444,  695, 'bottom','square', 'solid', '1EB1ED'],
            [2, 19, 636,  629, 'top',   'square', 'solid', '1EB1ED'],
            [2, 20, 627,  752, 'bottom','square', 'solid', '1EB1ED'],
            [2, 21, 1108, 628, 'top',   'square', 'solid', '1EB1ED'],
            [2, 22, 1118, 750, 'bottom','square', 'solid', '1EB1ED'],
            [2, 23, 1285, 594, 'top',   'square', 'solid', '1EB1ED'],
            [2, 24, 1300, 694, 'bottom','square', 'solid', '1EB1ED']
        ];

        //穴検査_ラゲージアウタARW
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441111020,
                'figure_id'  => $getFigureId(6441111020, 'holing', 'ana', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageOuterARW_holes);
        DB::connection('950A')->table($table_name)->insert($data);

        /*
         * かしめ/接着工程　かしめ検査
         */

        //かしめ後検査_ドアインナR
        $doorinnerR_kashimeHoles = [
            [1, 1,  181,  479, 'right', 'square', 'solid', '95CDDC'],
            [1, 2,  183,  539, 'right', 'square', 'solid', '95CDDC'],
            [1, 3,  273,  268, 'right', 'square', 'solid', '95CDDC'],
            [1, 4,  246,  279, 'top',   'square', 'solid', '95CDDC'],
            [1, 5,  138,  299, 'left',  'square', 'solid', '95CDDC'],
            [1, 6,  135,  483, 'left',  'square', 'solid', '95CDDC'],
            [1, 7,  128,  518, 'left',  'square', 'solid', '95CDDC'],
            [1, 8,  128,  595, 'left',  'square', 'solid', '95CDDC'],
            [1, 9,  135,  627, 'left',  'square', 'solid', '95CDDC'],
            [1, 10, 210,  646, 'right', 'square', 'solid', '95CDDC'],
            [1, 11, 186,  852, 'bottom','square', 'solid', '95CDDC'],
            [1, 12, 454,  875, 'bottom','square', 'solid', '95CDDC'],
            [1, 13, 728,  870, 'bottom','square', 'solid', '95CDDC'],
            [1, 14, 1003, 864, 'bottom','square', 'solid', '95CDDC'],
            [1, 15, 1331, 795, 'bottom','square', 'solid', '95CDDC'],
            [1, 16, 1459, 679, 'right', 'square', 'solid', '95CDDC'],
            [1, 17, 1548, 531, 'right', 'square', 'solid', '95CDDC'],
            [1, 18, 1581, 362, 'right', 'square', 'solid', '95CDDC'],
            [1, 19, 1582, 249, 'right', 'square', 'solid', '95CDDC'],
            [1, 20, 1572, 209, 'top',   'square', 'solid', '95CDDC'],
            [1, 21, 757,  756, 'top',   'square', 'solid', '95CDDC'],
            [1, 22, 946,  756, 'top',   'square', 'solid', '95CDDC'],
            [1, 23, 459,  510, 'top',   'square', 'solid', '95CDDC'],
            [1, 24, 556,  481, 'top',   'square', 'solid', '95CDDC'],
            [1, 25, 561,  557, 'bottom','square', 'solid', '95CDDC'],

            [2, 26, 251,  243, 'top',   'square', 'solid', 'FFFD38'],
            [2, 27, 360,  244, 'top',   'square', 'solid', 'FFFD38'],
            [2, 28, 558,  233, 'top',   'square', 'solid', 'FFFD38'],
            [2, 29, 767,  221, 'top',   'square', 'solid', 'FFFD38'],
            [2, 30, 960,  212, 'top',   'square', 'solid', 'FFFD38'],
            [2, 31, 1141, 202, 'top',   'square', 'solid', 'FFFD38'],
            [2, 32, 1318, 192, 'top',   'square', 'solid', 'FFFD38'],
            [2, 33, 1447, 188, 'top',   'square', 'solid', 'FFFD38'],
            [2, 34, 1535, 251, 'right', 'square', 'solid', 'FFFD38'],
            [2, 35, 1415, 427, 'right', 'square', 'solid', 'FFFD38'],
            [2, 36, 1394, 474, 'right', 'square', 'solid', 'FFFD38'],
            [2, 37, 1452, 337, 'top',   'square', 'solid', 'FFFD38'],
            [2, 38, 1271, 342, 'top',   'square', 'solid', 'FFFD38'],
            [2, 39, 1118, 348, 'top',   'square', 'solid', 'FFFD38'],
            [2, 40, 903,  359, 'top',   'square', 'solid', 'FFFD38'],
            [2, 41, 743,  369, 'top',   'square', 'solid', 'FFFD38'],
            [2, 42, 613,  378, 'top',   'square', 'solid', 'FFFD38'],
            [2, 43, 405,  393, 'top',   'square', 'solid', 'FFFD38'],
            [2, 44, 244,  404, 'left',  'square', 'solid', 'FFFD38'],
            [2, 45, 217,  436, 'left',  'square', 'solid', 'FFFD38'],
            [2, 46, 217,  527, 'left',  'square', 'solid', 'FFFD38'],
        ];

        //かしめ後検査_ドアインナR
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
                'figure_id'  => $getFigureId(6714111020, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$doorinnerR_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ後検査_ドアインナL
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
                'figure_id'  => $getFigureId(6714211020, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$doorinnerR_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);


        //かしめ後検査_ドアインナR
        $reinforceR_kashimeHoles = [
            [3, 1,  137,  247, 'top',   'square', 'solid', 'FFFD38'],
            [3, 2,  269,  248, 'top',   'square', 'solid', 'FFFD38'],
            [3, 3,  514,  233, 'top',   'square', 'solid', 'FFFD38'],
            [3, 4,  772,  218, 'top',   'square', 'solid', 'FFFD38'],
            [3, 5,  1010, 209, 'top',   'square', 'solid', 'FFFD38'],
            [3, 6,  1233, 194, 'top',   'square', 'solid', 'FFFD38'],
            [3, 7,  1451, 184, 'top',   'square', 'solid', 'FFFD38'],
            [3, 8,  1590, 177, 'top',   'square', 'solid', 'FFFD38'],
            [3, 9,  128,  446, 'bottom','square', 'solid', 'FFFD38'],
            [3, 10, 327,  433, 'bottom','square', 'solid', 'FFFD38'],
            [3, 11, 582,  414, 'bottom','square', 'solid', 'FFFD38'],
            [3, 12, 743,  403, 'bottom','square', 'solid', 'FFFD38'],
            [3, 13, 940,  390, 'bottom','square', 'solid', 'FFFD38'],
            [3, 14, 1205, 376, 'bottom','square', 'solid', 'FFFD38'],
            [3, 15, 1394, 368, 'bottom','square', 'solid', 'FFFD38'],
            [3, 16, 1616, 364, 'bottom','square', 'solid', 'FFFD38'],

            [4, 17, 801,  840, 'bottom','square', 'solid', '95CDDC'],
            [4, 18, 878,  833, 'bottom','square', 'solid', '95CDDC'],
            [4, 19, 920,  679, 'top',   'square', 'solid', '95CDDC'],
            [4, 20, 324,  300, 'top',   'square', 'solid', '95CDDC'],
            [4, 21, 391,  314, 'top',   'square', 'solid', '95CDDC'],
            [4, 22, 1450, 238, 'top',   'square', 'solid', '95CDDC'],
            [4, 23, 1515, 256, 'top',   'square', 'solid', '95CDDC'],
            [4, 24, 1681, 262, 'right', 'square', 'solid', '95CDDC'],
            [4, 25, 1681, 327, 'right', 'square', 'solid', '95CDDC'],
            [4, 26, 168,  349, 'top',   'square', 'solid', '95CDDC'],
            [4, 27, 922,  208, 'top',   'square', 'solid', '95CDDC'],
            [4, 28, 1085, 200, 'top',   'square', 'solid', '95CDDC'],
            [4, 29, 1636, 221, 'top',   'square', 'solid', '95CDDC'],
            [4, 30, 880,  720, 'left',  'square', 'solid', '95CDDC'],
        ];

        //かしめ後検査_リンフォースR
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6715111020,
                'figure_id'  => $getFigureId(6715111020, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$reinforceR_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ後検査_リンフォースL
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
                'pt_pn'      => 6715211020,
                'figure_id'  => $getFigureId(6715211020, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$reinforceR_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);


        //かしめ後検査_ラゲージインナSTD
        $luggageInnerSTD_kashimeHoles = [
            [1, 1,  735,  366, 'top',   'square', 'solid', 'F5964F'],
            [1, 2,  1003, 366, 'top',   'square', 'solid', 'F5964F'],
            [1, 3,  711,  714, 'top',   'square', 'solid', 'F5964F'],
            [1, 4,  665,  714, 'top',   'square', 'solid', 'F5964F'],
            [1, 5,  678,  772, 'bottom','square', 'solid', 'F5964F'],
            [1, 6,  439,  603, 'top',   'square', 'solid', 'F5964F'],
            [1, 7,  624,  639, 'top',   'square', 'solid', 'F5964F'],
            [1, 8,  1117, 640, 'top',   'square', 'solid', 'F5964F'],
            [1, 9,  1302, 601, 'top',   'square', 'solid', 'F5964F'],
            [1, 10, 423,  709, 'bottom','square', 'solid', 'F5964F'],
            [1, 11, 614,  768, 'bottom','square', 'solid', 'F5964F'],
            [1, 12, 1127, 768, 'bottom','square', 'solid', 'F5964F'],
            [1, 13, 1318, 706, 'bottom','square', 'solid', 'F5964F'],
        ];

        //かしめ後検査_ラゲージインナSTD
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441211010,
                'figure_id'  => $getFigureId(6441211010, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageInnerSTD_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ後検査_ラゲージインナARW
        $luggageInnerARW_kashimeHoles = [
            [1, 1,  735,  366, 'top',   'square', 'solid', 'F5964F'],
            [1, 2,  1003, 366, 'top',   'square', 'solid', 'F5964F'],
            [1, 3,  711,  714, 'top',   'square', 'solid', 'F5964F'],
            [1, 4,  665,  714, 'top',   'square', 'solid', 'F5964F'],
            [1, 5,  678,  772, 'bottom','square', 'solid', 'F5964F'],
            [1, 6,  439,  603, 'top',   'square', 'solid', 'F5964F'],
        ];

        //かしめ後検査_ラゲージインナARW
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441211020,
                'figure_id'  => $getFigureId(6441211020, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageInnerARW_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ後検査_ラゲージアウタSTD
        $luggageOuterSTD_kashimeHoles = [
            [1, 1,  626,  343, 'top',   'square', 'solid', '36859A'],
            [1, 2,  1115, 340, 'top',   'square', 'solid', '36859A'],
        ];

        //かしめ後検査_ラゲージアウタSTD
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441111010,
                'figure_id'  => $getFigureId(6441111010, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageOuterSTD_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ後検査_ラゲージインナARW
        $luggageOuterARW_kashimeHoles = [
            [1, 1, 626,  343, 'top',   'square', 'solid', '36859A'],
            [1, 2, 1115, 340, 'top',   'square', 'solid', '36859A'],
            [1, 3, 336,  598, 'left',  'square', 'solid', '36859A'],
            [1, 4, 727,  651, 'top',   'square', 'solid', '36859A'],
            [1, 5, 1017, 651, 'top',   'square', 'solid', '36859A'],
            [1, 6, 1407, 597, 'right', 'square', 'solid', '36859A'],
            [1, 7, 750,  772, 'bottom','square', 'solid', '36859A'],
            [1, 8, 996,  772, 'bottom','square', 'solid', '36859A'],
        ];

        //かしめ後検査_ラゲージアウタARW
        $data = array_map(function($h) use($getFigureId, $now) {
            return [
                'label'      => $h[1],
                'x'          => $h[2],
                'y'          => $h[3],
                'direction'  => $h[4],
                'shape'      => $h[5],
                'border'     => $h[6],
                'color'      => $h[7],
                'pt_pn'      => 6441111020,
                'figure_id'  => $getFigureId(6441111020, 'jointing', 'kashimego', $h[0]),
                'created_at' => $now,
                'updated_at' => $now
            ];
        },$luggageOuterARW_kashimeHoles);
        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}