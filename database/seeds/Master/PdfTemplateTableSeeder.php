<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PdfTemplateTableSeeder
 */
class PdfTemplateTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'pdf_templates';
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
                'path'       => 'molding-inner-line1.pdf',
                'area'       => '23.9/43.75/273.1/180.55/1740/980',
                'reference'  => '11/32/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'molding-inner-line2.pdf',
                'area'       => '23.9/43.75/273.1/180.55/1740/980',
                'reference'  => '11/32/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //
            [
                'path'       => 'holing-inner-page1.pdf',
                'area'       => '23.9/57.4/273.1/201.3/1740/1030',
                'reference'  => '11/32/11/32/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'holing-inner-page2.pdf',
                'area'       => '23.9/57.4/273.1/201.3/1740/1030',
                'reference'  => '11/32/11/32/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'holing-inner-page3.pdf',
                'area'       => '23.9/57.4/273.1/201.3/1740/1030',
                'reference'  => '11/32/11/32/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'holing-inner-page4.pdf',
                'area'       => '23.9/57.4/273.1/201.3/1740/1030',
                'reference'  => '11/32/11/32/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //
            [
                'path'       => 'molding-small-line1-page1.pdf',
                'area'       => '23.9/54.9/273.1/180.6/1740/900',
                'reference'  => '11/40/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'molding-small-line1-page2.pdf',
                'area'       => '23.9/60.4/273.1/180.55/1740/860',
                'reference'  => '11/49/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'molding-small-line2-page1.pdf',
                'area'       => '23.9/54.9/273.1/180.6/1740/900',
                'reference'  => '11/40/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'molding-small-line2-page2.pdf',
                'area'       => '23.9/60.4/273.1/180.55/1740/860',
                'reference'  => '11/49/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //
            [
                'path'       => 'holing-small-page1.pdf',
                'area'       => '23.9/57.4/273.1/201.3/1740/1030',
                'reference'  => '11/32/11/32/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'holing-small-page2.pdf',
                'area'       => '23.9/87/273.1/201.5/1740/820',
                'reference'  => '11/60/11/60/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],
            //
            [
                'path'       => 'jointing-ws-assy.pdf',
                'area'       => '23.9/45.2/273.1/170.9/1740/900',
                'reference'  => '11/32/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'jointing-fn-assy.pdf',
                'area'       => '23.9/45.2/273.1/170.9/1740/900',
                'reference'  => '11/179/11/189/7/29',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'jointing-ch-assy.pdf',
                'area'       => '23.9/45.2/273.1/170.9/1740/900',
                'reference'  => '11/32/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'jointing-sc-assy.pdf',
                'area'       => '23.9/45.2/273.1/170.9/1740/900',
                'reference'  => '11/32/11/186/0/0',
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'path'       => 'jointing-ad-assy.pdf',
                'area'       => '23.9/45.2/273.1/170.9/1740/900',
                'reference'  => '11/179/11/189/7/29',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}