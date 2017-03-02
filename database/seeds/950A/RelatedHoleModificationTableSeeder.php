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

        $data = [
            //穴あけ_外観検査_ドアインナ
            //穴あけ_外観検査_ラゲージインナ
            //穴あけ_穴検査_ドアインナ
            [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => 1,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => 2,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => 3,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => 4,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'doorInner',
                'type_id'       => 5,
                'type'          => 2,
                'sort'          => 1
            ],

            //穴あけ_穴検査_ラゲージインナ
            [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => 1,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => 2,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => 3,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => 4,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageInner',
                'type_id'       => 5,
                'type'          => 2,
                'sort'          => 1
            ],

            //穴あけ_穴検査_ラゲージアウタ
            [
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => 1,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => 2,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => 3,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => 4,
                'type'          => 2,
                'sort'          => 1
            ],[
                'process'       => 'holing',
                'inspection'    => 'ana',
                'division'      => 'luggageOuter',
                'type_id'       => 5,
                'type'          => 2,
                'sort'          => 1
            ],

            //かしめ/接着_かしめ後検査_ドアインナ
            //かしめ/接着_かしめ後検査_ラゲージインナ
            //かしめ/接着_かしめ後検査_ラゲージアウタ

            //かしめ/接着_外周仕上_ラゲージインナ
            //かしめ/接着_パテ修復後_ラゲージインナ
            //かしめ/接着_水検後_ラゲージインナ
            //かしめ/接着_塗装受入後_ラゲージインナ

            //かしめ/接着_外観検査_ドアASSY
            //かしめ/接着_外観検査_ラゲージASSY
            //かしめ/接着_手直_ドアASSY
            //かしめ/接着_手直_ラゲージASSY
        ];

        DB::connection('950A')->table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::connection('950A')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}