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
                'pn'         => 6714111020,
                'pn2'        => 6,
                'name'       => 'ドアインナR',
                'en'         => 'doorInnerR',
                'short_name' => 'DIR',
                'division1'  => 'door',
                'division2'  => 'doorInner',
                'sort'       => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6714211020,
                'pn2'        => 6,
                'name'       => 'ドアインナL',
                'en'         => 'doorInnerL',
                'short_name' => 'DIL',
                'division1'  => 'door',
                'division2'  => 'doorInner',
                'sort'       => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6715111020,
                'pn2'        => 6,
                'name'       => 'リンフォースR',
                'en'         => 'reinforceR',
                'short_name' => 'RR',
                'division1'  => 'door',
                'division2'  => 'reinforce',
                'sort'       => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6715211020,
                'pn2'        => 6,
                'name'       => 'リンフォースL',
                'en'         => 'reinforceL',
                'short_name' => 'RL',
                'division1'  => 'door',
                'division2'  => 'reinforce',
                'sort'       => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6441211010,
                'pn2'        => 2,
                'name'       => 'ラゲージインナSTD',
                'en'         => 'luggageInnerSTD',
                'short_name' => 'RIS',
                'division1'  => 'luggage',
                'division2'  => 'luggageInner',
                'sort'       => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6441211020,
                'pn2'        => 4,
                'name'       => 'ラゲージインナARW',
                'en'         => 'luggageInnerARW',
                'short_name' => 'RIA',
                'division1'  => 'luggage',
                'division2'  => 'luggageInner',
                'sort'       => 6,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6441111010,
                'pn2'        => 3,
                'name'       => 'ラゲージアウタSTD',
                'en'         => 'luggageOuterSTD',
                'short_name' => 'ROS',
                'division1'  => 'luggage',
                'division2'  => 'luggageOuter',
                'sort'       => 7,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 6441111020,
                'pn2'        => 5,
                'name'       => 'ラゲージアウタARW',
                'en'         => 'luggageOuterARW',
                'short_name' => 'ROA',
                'division1'  => 'luggage',
                'division2'  => 'luggageOuter',
                'sort'       => 8,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 1,
                'pn2'        => 0,
                'name'       => 'ドアAssyR',
                'en'         => 'doorAssyR',
                'short_name' => 'DAR',
                'division1'  => 'door',
                'division2'  => 'doorAssy',
                'sort'       => 10,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 2,
                'pn2'        => 0,
                'name'       => 'ドアAssyL',
                'en'         => 'doorAssyL',
                'short_name' => 'DAL',
                'division1'  => 'door',
                'division2'  => 'doorAssy',
                'sort'       => 9,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 3,
                'pn2'        => 0,
                'name'       => 'ラゲージAssySTD',
                'en'         => 'luggageAssySTD',
                'short_name' => 'RAS',
                'division1'  => 'luggage',
                'division2'  => 'luggageAssy',
                'sort'       => 11,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'pn'         => 4,
                'pn2'        => 0,
                'name'       => 'ラゲージAssyARW',
                'en'         => 'luggageAssyARW',
                'short_name' => 'RAA',
                'division1'  => 'luggage',
                'division2'  => 'luggageAssy',
                'sort'       => 12,
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