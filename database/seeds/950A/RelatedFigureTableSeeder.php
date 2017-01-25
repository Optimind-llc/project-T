<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Class FigureTableSeeder
 */
class RelatedFigureTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'figures';
        $now = Carbon::now();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        }

        $data = [
            //成型_外観検査
            [
                'name'       => 'm_gaikan_door_innerR',
                'path'       => 'm_gaikan_door_innerR.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 1,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_door_innerL',
                'path'       => 'm_gaikan_door_innerL.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 2,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_door_reinforceL',
                'path'       => 'm_gaikan_door_reinforceL.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 4,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_door_reinforceR',
                'path'       => 'm_gaikan_door_reinforceR.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 3,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_luggage_innerSTD',
                'path'       => 'm_gaikan_luggage_innerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 5,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_luggage_innerARW',
                'path'       => 'm_gaikan_luggage_innerARW.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 6,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_luggage_outerSTD',
                'path'       => 'm_gaikan_luggage_outerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 7,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_gaikan_luggage_outerARW',
                'path'       => 'm_gaikan_luggage_outerARW.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 8,
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型_精度検査
            [
                'name'       => 'm_inline_door_innerR',
                'path'       => 'm_inline_door_innerR.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 1,
                'process'    => 'molding',
                'inspection' => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_inline_door_innerL',
                'path'       => 'm_inline_door_innerL.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 2,
                'process'    => 'molding',
                'inspection' => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_inline_luggage_innerSTD',
                'path'       => 'm_inline_luggage_innerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 5,
                'process'    => 'molding',
                'inspection' => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'm_inline_luggage_innerARW',
                'path'       => 'm_inline_luggage_innerARW.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 6,
                'process'    => 'molding',
                'inspection' => 'inline',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ_外観検査
            [
                'name'       => 'h_gaikan_door_innerR_page1',
                'path'       => 'h_gaikan_door_innerR_page1.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerR_page2',
                'path'       => 'h_gaikan_door_innerR_page2.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerR_page3',
                'path'       => 'h_gaikan_door_innerR_page3.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerR_page4',
                'path'       => 'h_gaikan_door_innerR_page4.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerL_page1',
                'path'       => 'h_gaikan_door_innerL_page1.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerL_page2',
                'path'       => 'h_gaikan_door_innerL_page2.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerL_page3',
                'path'       => 'h_gaikan_door_innerL_page3.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_door_innerL_page4',
                'path'       => 'h_gaikan_door_innerL_page4.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_luggage_innerSTD',
                'path'       => 'm_gaikan_luggage_innerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 5,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_gaikan_luggage_innerARW',
                'path'       => 'm_gaikan_luggage_innerARW.png',
                'size_x'     => 1740,
                'size_y'     => 980,
                'pt_id'      => 6,
                'process'    => 'holing',
                'inspection' => 'gaikan',
                'created_at' => $now,
                'updated_at' => $now
            ],

            //穴あけ_穴検査
            [
                'name'       => 'h_ana_door_innerR_page1',
                'path'       => 'h_ana_door_innerR_page1.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerR_page2',
                'path'       => 'h_ana_door_innerR_page2.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerR_page3',
                'path'       => 'h_ana_door_innerR_page3.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerR_page4',
                'path'       => 'h_ana_door_innerR_page4.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerR_page5',
                'path'       => 'h_ana_door_innerR_page5.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerL_page1',
                'path'       => 'h_ana_door_innerL_page1.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerL_page2',
                'path'       => 'h_ana_door_innerL_page2.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerL_page3',
                'path'       => 'h_ana_door_innerL_page3.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerL_page4',
                'path'       => 'h_ana_door_innerL_page4.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_door_innerL_page5',
                'path'       => 'h_ana_door_innerL_page5.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_luggage_innerSTD',
                'path'       => 'h_ana_luggage_innerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 5,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_luggage_innerARW',
                'path'       => 'h_ana_luggage_innerARW.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 6,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_luggage_outerSTD',
                'path'       => 'h_ana_luggage_outerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 7,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'h_ana_luggage_outerARW',
                'path'       => 'h_ana_luggage_outerARW.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 8,
                'process'    => 'holing',
                'inspection' => 'ana',
                'created_at' => $now,
                'updated_at' => $now
            ],

            //かしめ/接着_かしめ後検査
            [
                'name'       => 'j_kashimego_door_innerR_page1',
                'path'       => 'h_ana_door_innerR_page1.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerR_page2',
                'path'       => 'h_ana_door_innerR_page2.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerR_page3',
                'path'       => 'h_ana_door_innerR_page3.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerR_page4',
                'path'       => 'h_ana_door_innerR_page4.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerR_page5',
                'path'       => 'h_ana_door_innerR_page5.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 1,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerL_page1',
                'path'       => 'h_ana_door_innerL_page1.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerL_page2',
                'path'       => 'h_ana_door_innerL_page2.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerL_page3',
                'path'       => 'h_ana_door_innerL_page3.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerL_page4',
                'path'       => 'h_ana_door_innerL_page4.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_door_innerL_page5',
                'path'       => 'h_ana_door_innerL_page5.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 2,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_luggage_innerSTD',
                'path'       => 'h_ana_luggage_innerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 5,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_luggage_innerARW',
                'path'       => 'h_ana_luggage_innerARW.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 6,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_luggage_outerSTD',
                'path'       => 'h_ana_luggage_outerSTD.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 7,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'j_kashimego_luggage_outerARW',
                'path'       => 'h_ana_luggage_outerARW.png',
                'size_x'     => 1740,
                'size_y'     => 1030,
                'pt_id'      => 8,
                'process'    => 'jointing',
                'inspection' => 'kashimego',
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