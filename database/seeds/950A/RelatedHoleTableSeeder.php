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

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::connection('950A')->statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::connection('950A')->statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        }

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

        $data = [
            [
                'x'          => 200,
                'y'          => 200,
                'label'      => 1,
                'direction'  => 'left',
                'shape'      => 'square',
                'border'     => 'solid',
                'color'      => '000000',
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 1),
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
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 2),
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
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 3),
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
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 4),
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
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 5),
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
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 6),
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
                'pt_pn'      => 6714111020,
                'figure_id'  => $getFigureId(6714111020, 'holing', 'ana', 7),
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
            ],
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}