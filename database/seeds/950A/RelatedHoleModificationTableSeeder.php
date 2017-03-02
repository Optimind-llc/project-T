<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RelatedHoleModificationTableSeeder
 */
class RelatedHoleModificationTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        /**
         * create failure table seeder
         */
        $table_name = 'hole_modification_types';

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
            [
                'name'       => '削り',
                'label'      => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '除去',
                'label'      => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '樹脂盛り',
                'label'      => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => '伸縮防止',
                'label'      => 4,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'かしめ直し',
                'label'      => 5,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'hmt_related';

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

        //穴あけ_穴検査_ドアインナ
        $h_ana_doorInner_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_doorInner_failures), $h_ana_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_穴検査_リンフォース
        $h_ana_reinforce_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_reinforce_failures), $h_ana_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_穴検査_ラゲージインナ
        $h_ana_luggageInner_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_luggageInner_failures), $h_ana_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //穴あけ_穴検査_ラゲージアウタ
        $h_ana_luggageOuter_failures = [
            [1,  1], [2,  1], [3,  1], [4,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($h_ana_luggageOuter_failures), $h_ana_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ/接着_かしめ後検査_ドアインナ
        $j_kashimego_doorInner_failures = [
            [5,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'doorInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_doorInner_failures), $j_kashimego_doorInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ/接着_かしめ後検査_リンフォース
        $j_kashimego_reinforce_failures = [
            [5,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'reinforce',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_reinforce_failures), $j_kashimego_reinforce_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ/接着_かしめ後検査_ラゲージインナ
        $j_kashimego_luggageInner_failures = [
            [5,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'luggageInner',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_luggageInner_failures), $j_kashimego_luggageInner_failures);
        DB::connection('950A')->table($table_name)->insert($data);

        //かしめ/接着_かしめ後検査_ラゲージアウタ
        $j_kashimego_luggageOuter_failures = [
            [5,  1], [6,  1]
        ];

        $data = array_map(function($i, $f) {
            return [
                'process'       => 'jointing',
                'inspection'    => 'kashimego',
                'division'      => 'luggageOuter',
                'type_id'       => $f[0],
                'type'          => $f[1],
                'sort'          => $i+1
            ];
        }, array_keys($j_kashimego_luggageOuter_failures), $j_kashimego_luggageOuter_failures);
        DB::connection('950A')->table($table_name)->insert($data);
        
        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}