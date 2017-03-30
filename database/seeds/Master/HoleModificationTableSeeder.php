<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class HoleModificationTableSeeder
 */
class HoleModificationTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'hole_modifications';
        $now = Carbon::now();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            [
                'name'       => '穴径修正',
                'label'      => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'トリム部修正',
                'label'      => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name'       => 'その他',
                'label'      => 99,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        /**
         * create failure related table seeder
         */
        $table_name = 'hole_modification_inspection';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // if (env('DB_CONNECTION') == 'mysql') {
        //     DB::table($table_name)->truncate();
        // } elseif (env('DB_CONNECTION') == 'sqlite') {
        //     DB::statement('DELETE FROM ' . $table_name);
        // } else {
        //     //For PostgreSQL or anything else
        //     DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        // }

        $data = [
            //穴検査
            [
                'hole_m_id'     => 1,
                'inspection_id' => 3,
                'type'          => 1,
                'sort'          => 1
            ],[
                'hole_m_id'     => 2,
                'inspection_id' => 3,
                'type'          => 1,
                'sort'          => 1
            ],[
                'hole_m_id'     => 3,
                'inspection_id' => 3,
                'type'          => 1,
                'sort'          => 1
            ],
            //オフライン手直し検査
            [
                'hole_m_id'     => 1,
                'inspection_id' => 12,
                'type'          => 1,
                'sort'          => 1
            ],[
                'hole_m_id'     => 2,
                'inspection_id' => 12,
                'type'          => 1,
                'sort'          => 1
            ],[
                'hole_m_id'     => 3,
                'inspection_id' => 12,
                'type'          => 1,
                'sort'          => 1
            ],
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}