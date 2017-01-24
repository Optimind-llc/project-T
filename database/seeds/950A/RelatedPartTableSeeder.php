<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/*
 * Class FigureTableSeeder
 */
class RelatedPartTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'part_types';
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
            [
                'pn'         => '6714111020',
                'pn2'        => '6',
                'name'       => 'ドアインナR',
                'short_name' => 'DIR',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6714211020',
                'pn2'        => '6',
                'name'       => 'ドアインナL',
                'short_name' => 'DIL',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6715111020',
                'pn2'        => '6',
                'name'       => 'リンフォースR',
                'short_name' => 'RR',
                'sort'       => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6715211020',
                'pn2'        => '6',
                'name'       => 'リンフォースL',
                'short_name' => 'RL',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6441211010',
                'pn2'        => '2',
                'name'       => 'ラゲージインナSTD',
                'short_name' => 'RIS',
                'sort'       => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6441211020',
                'pn2'        => '4',
                'name'       => 'ラゲージインナARW',
                'short_name' => 'RIA',
                'sort'       => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6441111010',
                'pn2'        => '3',
                'name'       => 'ラゲージアウタSTD',
                'short_name' => 'RAS',
                'sort'       => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => '6441111020',
                'pn2'        => '5',
                'name'       => 'ラゲージアウタARW',
                'short_name' => 'RAA',
                'sort'       => 8,
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