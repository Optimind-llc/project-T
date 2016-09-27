<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $this->call(UserTableSeeder::class);

        $this->call(ProcessTableSeeder::class);
        $this->call(InspectorGroupTableSeeder::class);
        $this->call(InspectorTableSeeder::class);
        $this->call(InspectionTableSeeder::class);
        $this->call(DivisionTableSeeder::class);
        $this->call(InspectionGroupTableSeeder::class);
        $this->call(FigureTableSeeder::class);
        $this->call(PageTypeTableSeeder::class);
        $this->call(VehicleTableSeeder::class);
        $this->call(PartTypeTableSeeder::class);
        $this->call(FailureTableSeeder::class);
        $this->call(HoleTableSeeder::class);
        $this->call(CommentTableSeeder::class);

        $this->call(DummyInspectionsSeeder::class);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        Model::reguard();
    }
}
