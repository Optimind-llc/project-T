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
            [1, 5,  134,  483, 'top',   'square', 'solid', '95CDDC'],
            [1, 6,  127,  518, 'left',  'square', 'solid', '95CDDC'],
            [1, 7,  127,  595, 'left',  'square', 'solid', '95CDDC'],
            [1, 8,  133,  627, 'bottom','square', 'solid', '95CDDC'],
            [1, 9,  185,  852, 'bottom','square', 'solid', '95CDDC'],
            [1, 10, 453,  875, 'bottom','square', 'solid', '95CDDC'],
            [1, 11, 727,  869, 'bottom','square', 'solid', '95CDDC'],
            [1, 12, 1002, 865, 'bottom','square', 'solid', '95CDDC'],
            [1, 13, 1330, 795, 'bottom','square', 'solid', '95CDDC'],
            [1, 14, 1458, 679, 'right', 'square', 'solid', '95CDDC'],
            [1, 15, 1548, 532, 'right', 'square', 'solid', '95CDDC'],
            [1, 16, 1581, 361, 'right', 'square', 'solid', '95CDDC'],
            [1, 17, 1581, 247, 'right', 'square', 'solid', '95CDDC'],

            [2, 18, 217,  297, 'top',   'square', 'solid', 'B3A3C6'],
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
            [5, 94, 1494, 489, 'bottom','square', 'solid', 'DB0B17'],
            [5, 95, 1519, 469, 'right', 'square', 'solid', 'DB0B17'],

            [5, 96, 1446, 519, 'right','square', 'solid', 'FFFFFF'],

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


        //穴検査_リンフォースR
        $reinforceR_holes = [
            [1, 1,  137,  500, 'top',   'square', 'solid', '95CDDC'],
            [1, 2,  269,  501, 'top',   'square', 'solid', '95CDDC'],
            [1, 3,  514,  486, 'top',   'square', 'solid', '95CDDC'],
            [1, 4,  772,  471, 'top',   'square', 'solid', '95CDDC'],
            [1, 5,  1010, 462, 'top',   'square', 'solid', '95CDDC'],
            [1, 6,  1233, 447, 'top',   'square', 'solid', '95CDDC'],
            [1, 7,  1451, 437, 'top',   'square', 'solid', '95CDDC'],
            [1, 8,  1590, 430, 'top',   'square', 'solid', '95CDDC'],
            [1, 9,  128,  699, 'bottom','square', 'solid', '95CDDC'],
            [1, 10, 327,  686, 'bottom','square', 'solid', '95CDDC'],
            [1, 11, 582,  667, 'bottom','square', 'solid', '95CDDC'],
            [1, 12, 743,  656, 'bottom','square', 'solid', '95CDDC'],
            [1, 13, 940,  643, 'bottom','square', 'solid', '95CDDC'],
            [1, 14, 1205, 629, 'bottom','square', 'solid', '95CDDC'],
            [1, 15, 1394, 621, 'bottom','square', 'solid', '95CDDC'],
            [1, 16, 1616, 617, 'bottom','square', 'solid', '95CDDC'],

            [1, 17, 1635, 441, 'bottom','square', 'solid', 'DB0B17'],
            [1, 18, 1677, 441, 'right', 'square', 'solid', '000000'],

            [2, 19, 662,  346, 'bottom','square', 'solid', 'FFFD38'],
            [2, 20, 739,  339, 'bottom','square', 'solid', 'FFFD38'],
            [2, 21, 781,  185, 'left',   'square', 'solid', 'FFFD38'],
            [2, 22, 324,  718, 'bottom','square', 'solid', 'FFFD38'],
            [2, 23, 391,  732, 'bottom','square', 'solid', 'FFFD38'],
            [2, 24, 1450, 656, 'bottom','square', 'solid', 'FFFD38'],
            [2, 25, 1515, 674, 'bottom','square', 'solid', 'FFFD38'],
            [2, 26, 1681, 680, 'right', 'square', 'solid', 'FFFD38'],
            [2, 27, 1681, 745, 'right', 'square', 'solid', 'FFFD38'],

            [2, 28, 741,  225, 'top',   'square', 'solid', 'C3D59E'],

            [2, 29, 685,  298, 'top',   'square', 'solid', 'B3A3C6'],

            [2, 30, 646,  281, 'left',  'square', 'solid', 'DB0B17'],
            [2, 31, 756,  266, 'right', 'square', 'solid', 'DB0B17'],

            [2, 32, 168,  767, 'bottom', 'square', 'solid', '36859A'],
            [2, 33, 922,  626, 'top',   'square', 'solid', '36859A'],
            [2, 34, 1085, 618, 'top',   'square', 'solid', '36859A'],
            [2, 35, 1636, 639, 'top',   'square', 'solid', '36859A'],
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

        //穴検査_ラゲージインナSTD
        $luggageInnerSTD_holes = [
            [1, 1,  1005, 365, 'top',   'square', 'solid', 'F5964F'],
            [1, 2,  737,  365, 'top',   'square', 'solid', 'F5964F'],
            [1, 3,  1078, 713, 'top',   'square', 'solid', 'F5964F'],
            [1, 4,  1029, 714, 'top',   'square', 'solid', 'F5964F'],
            [1, 5,  1063, 773, 'bottom','square', 'solid', 'F5964F'],

            [1, 6,  1500, 254, 'right', 'square', 'solid', '95CDDC'],
            [1, 7,  1521, 383, 'right', 'square', 'solid', '95CDDC'],
            [1, 8,  239,  257, 'left',  'square', 'solid', '95CDDC'],
            [1, 9,  218,  386, 'left',  'square', 'solid', '95CDDC'],
            [1, 10, 955,  874, 'bottom','square', 'solid', '95CDDC'],
            [1, 11, 783,  861, 'bottom','square', 'solid', '95CDDC'],

            [1, 12, 1498, 299, 'right', 'square', 'solid', 'B3A3C'],
            [1, 13, 1528, 444, 'right', 'square', 'solid', 'B3A3C6'],
            [1, 14, 240,  303, 'left',  'square', 'solid', 'B3A3C6'],
            [1, 15, 211,  447, 'left',  'square', 'solid', 'B3A3C6'],

            [1, 16, 895,  874, 'top',   'square', 'solid', 'DB0B17'],
            [1, 17, 845,  874, 'top',   'square', 'solid', 'DB0B17'],

            [1, 18, 1401, 335, 'top',   'square', 'solid', 'C3D59E'],
            [1, 19, 1047, 357, 'bottom','square', 'solid', 'C3D59E'],
            [1, 20, 692,  359, 'bottom','square', 'solid', 'C3D59E'],
            [1, 21, 338,  338, 'top',   'square', 'solid', 'C3D59E'],
            [1, 22, 1416, 653, 'bottom','square', 'solid', 'C3D59E'],
            [1, 23, 1088, 819, 'bottom','square', 'solid', 'C3D59E'],
            [1, 24, 1015, 843, 'bottom','square', 'solid', 'C3D59E'],
            [1, 25, 725,  843, 'bottom','square', 'solid', 'C3D59E'],
            [1, 26, 654,  819, 'bottom','square', 'solid', 'C3D59E'],
            [1, 27, 325,  656, 'bottom','square', 'solid', 'C3D59E'],

            [2, 28, 1580, 624, 'bottom','square', 'solid', 'D89695'],
            [2, 29, 1089, 818, 'bottom','square', 'solid', 'D89695'],
            [2, 30, 652,  818, 'bottom','square', 'solid', 'D89695'],
            [2, 31, 161,  629, 'bottom','square', 'solid', 'D89695'],

            [2, 32, 913,  513, 'top',   'square', 'solid', 'C7DAF0'],
            [2, 33, 826,  513, 'top',   'square', 'solid', 'C7DAF0'],
            [2, 34, 913,  599, 'bottom','square', 'solid', 'C7DAF0'],
            [2, 35, 827,  599, 'bottom','square', 'solid', 'C7DAF0'],

            [2, 36, 1117, 639, 'right','square', 'solid', 'D9D9D9'],
            [2, 37, 1022, 666, 'right','square', 'solid', 'D9D9D9'],
            [2, 38, 1126, 768, 'right','square', 'solid', 'D9D9D9'],
            [2, 39, 999,  794, 'right','square', 'solid', 'D9D9D9'],
            [2, 40, 720,  666, 'left','square', 'solid', 'D9D9D9'],
            [2, 41, 626,  640, 'left','square', 'solid', 'D9D9D9'],
            [2, 42, 741,  794, 'left','square', 'solid', 'D9D9D9'],
            [2, 43, 615,  770, 'left','square', 'solid', 'D9D9D9'],

            [3, 44, 1374, 482, 'top',  'square', 'solid', 'E26C22'],
            [3, 45, 1294, 538, 'top',  'square', 'solid', 'E26C22'],
            [3, 46, 1090, 582, 'top',  'square', 'solid', 'E26C22'],
            [3, 47, 983,  632, 'top',  'square', 'solid', 'E26C22'],
            [3, 48, 913,  659, 'top',  'square', 'solid', 'E26C22'],
            [3, 49, 823,  649, 'top',  'square', 'solid', 'E26C22'],
            [3, 50, 651,  586, 'top',  'square', 'solid', 'E26C22'],
            [3, 51, 447,  539, 'top',  'square', 'solid', 'E26C22'],
            [3, 52, 366,  484, 'top',  'square', 'solid', 'E26C22'],

            [3, 53, 1123, 899, 'bottom','square', 'solid', 'C7DAF0'],
            [3, 54, 619,  901, 'bottom','square', 'solid', 'C7DAF0'],

            [3, 55, 1160, 867, 'top',  'square', 'solid', 'FFFD38'],
            [3, 56, 581,  870, 'top',  'square', 'solid', 'FFFD38'],

            [3, 57, 1383, 675, 'bottom','square', 'solid', 'B3A3C6'],
            [3, 58, 358,  678, 'bottom','square', 'solid', 'B3A3C6']
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
        },$luggageInnerSTD_holes);
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

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}