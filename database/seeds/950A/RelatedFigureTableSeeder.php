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

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::connection('950A')->table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            //成型_外観検査
            [
                'path'       => 'm_gaikan_doorInnerR_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6714111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_doorInnerL_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6714211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_reinforceL_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6715211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_reinforceR_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6715111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_luggageInnerSTD_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_luggageInnerARW_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_luggageOuterSTD_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_gaikan_luggageOuterARW_1.png',
                'process'    => 'molding',
                'inspection' => 'gaikan',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //成型_精度検査
            [
                'path'       => 'm_inline_doorInnerL_1.png',
                'process'    => 'molding',
                'inspection' => 'inline',
                'pt_pn'      => 6714211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 660,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_inline_doorInnerR_1.png',
                'process'    => 'molding',
                'inspection' => 'inline',
                'pt_pn'      => 6714111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 660,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_inline_reinforceL_1.png',
                'process'    => 'molding',
                'inspection' => 'inline',
                'pt_pn'      => 6715211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 370,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_inline_reinforceR_1.png',
                'process'    => 'molding',
                'inspection' => 'inline',
                'pt_pn'      => 6715111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 370,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_inline_luggageInnerSTD_1.png',
                'process'    => 'molding',
                'inspection' => 'inline',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'm_inline_luggageInnerARW_1.png',
                'process'    => 'molding',
                'inspection' => 'inline',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ_洗浄前外観検査
            [
                'path'       => 'h_gaikan_doorInnerL_1.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6714211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerL_2.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6714211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerL_3.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6714211020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_reinforceL_1.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6715211020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_1.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6714111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_2.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6714111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_3.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6714111020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_reinforceR_1.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6715111020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'maegaikan',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ_洗浄後外観検査
            [
                'path'       => 'h_gaikan_doorInnerL_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6714211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerL_2.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6714211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerL_3.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6714211020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_reinforceL_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6715211020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6714111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_2.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6714111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_3.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6714111020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_reinforceR_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6715111020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageOuterSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageOuterARW_1.png',
                'process'    => 'holing',
                'inspection' => 'atogaikan',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ_穴検査
            [
                'path'       => 'h_ana_doorInnerL_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_2.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_3.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_4.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_5.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 5,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_6.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 6,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_7.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714211020,
                'page'       => 7,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceL_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6715211020,
                'page'       => 8,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceL_2.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6715211020,
                'page'       => 9,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_2.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_3.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_4.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_5.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 5,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_6.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 6,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_7.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6714111020,
                'page'       => 7,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceR_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6715111020,
                'page'       => 8,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceR_2.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6715111020,
                'page'       => 9,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211010,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211010,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211010,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441211020,
                'page'       => 4,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageOuterSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageOuterARW_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageOuterARW_1.png',
                'process'    => 'holing',
                'inspection' => 'ana',
                'pt_pn'      => 6441111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //穴あけ_手直検査
            [
                'path'       => 'h_ana_doorInnerL_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6714211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerL_2.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6714211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerL_3.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6714211020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6714111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_2.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6714111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_doorInnerR_3.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6714111020,
                'page'       => 3,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_tenaoshi_reinforceL_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6715211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_tenaoshi_reinforceR_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6715111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441211010,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerARW_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageOuterSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageOuterSTD_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441111010,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_gaikan_luggageOuterARW_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 980,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageOuterARW_1.png',
                'process'    => 'holing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6441111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //かしめ_かしめ後検査
            [
                'path'       => 'h_ana_doorInnerL_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6714211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerL_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6714211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceL_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6715211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceL_2.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6715211020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6714111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_doorInnerR_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6714111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceR_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6715111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_reinforceR_2.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6715111020,
                'page'       => 2,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerSTD_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6441211010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageInnerARW_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6441211020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'h_ana_luggageOuterSTD_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // [
            //     'path'       => 'h_ana_luggageOuterSTD_1.png',
            //     'process'    => 'jointing',
            //     'inspection' => 'kashimego',
            //     'pt_pn'      => 6441111010,
            //     'page'       => 2,
            //     'size_x'     => 1740,
            //     'size_y'     => 1030,
            //     'created_at' => $now,
            //     'updated_at' => $now
            // ],
            [
                'path'       => 'h_ana_luggageOuterARW_1.png',
                'process'    => 'jointing',
                'inspection' => 'kashimego',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着_外周仕上
            [
                'path'       => 'j_luggageOuterSTD.png',
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageOuterARW.png',
                'process'    => 'jointing',
                'inspection' => 'gaishushiage',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着_パテ修復後
            [
                'path'       => 'j_luggageOuterSTD.png',
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageOuterARW.png',
                'process'    => 'jointing',
                'inspection' => 'pateshufukugo',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着_水検後
            [
                'path'       => 'j_luggageOuterSTD.png',
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageOuterARW.png',
                'process'    => 'jointing',
                'inspection' => 'suikengo',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着_塗装受入後
            [
                'path'       => 'j_luggageOuterSTD.png',
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'pt_pn'      => 6441111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageOuterARW.png',
                'process'    => 'jointing',
                'inspection' => 'tosoukeirego',
                'pt_pn'      => 6441111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],

            //接着_精度検査
            [
                'path'       => 'j_inline_doorAssyL_1.png',
                'process'    => 'jointing',
                'inspection' => 'inline',
                'pt_pn'      => 6701611020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_inline_doorAssyR_1.png',
                'process'    => 'jointing',
                'inspection' => 'inline',
                'pt_pn'      => 6701511020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_inline_luggageAssySTD_1.png',
                'process'    => 'jointing',
                'inspection' => 'inline',
                'pt_pn'      => 6440111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_inline_luggageAssyARW_1.png',
                'process'    => 'jointing',
                'inspection' => 'inline',
                'pt_pn'      => 6440111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着_外観検査
            [
                'path'       => 'j_doorAssyR_1.png',
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'pt_pn'      => 6701511020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_doorAssyL_1.png',
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'pt_pn'      => 6701611020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageAssySTD_1.png',
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'pt_pn'      => 6440111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageAssyARW_1.png',
                'process'    => 'jointing',
                'inspection' => 'gaikan',
                'pt_pn'      => 6440111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],
            //接着_手直
            [
                'path'       => 'j_doorAssyR_1.png',
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6701511020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_doorAssyL_1.png',
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6701611020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageAssySTD_1.png',
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6440111010,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'j_luggageAssyARW_1.png',
                'process'    => 'jointing',
                'inspection' => 'tenaoshi',
                'pt_pn'      => 6440111020,
                'page'       => 1,
                'size_x'     => 1740,
                'size_y'     => 1030,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}