<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class HoleTableSeeder
 */
class HoleTableSeeder extends Seeder
{
    public function run()
    {
        $table_name = 'holes';
        $now = Carbon::now();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table($table_name)->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . $table_name);
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . $table_name . ' CASCADE');
        }

        /***** HARD CODE *****/
        $img = ["x" => 1280, "y" => 1024, "margin" => 100, "arrow" => 80];
        $hasHoles = [3, 4, 5, 6, 10, 11];

        $data = [];
        foreach ($hasHoles as $id) {
            for ($i = 1; $i <= 100; $i++) { 
                $x = rand($img["margin"], $img["x"] - $img["margin"]);
                $y = rand($img["margin"], $img["y"] - $img["margin"]);

                array_push($data, [
                    'point'      => $x . ',' . $y,
                    'sort'       => $i,
                    'figure_id'  => $id,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }

        DB::table($table_name)->insert($data);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}