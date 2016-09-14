<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesConnectedWithPartTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('parts_families', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('division_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('part_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pn')->unique();            //品番:67119
            $table->string('name');                     //品名
            $table->integer('vehicle_id')->unsigned();
            $table->integer('parts_family_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('parts_family_id')
                ->references('id')
                ->on('parts_families')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('process_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('figures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->unique();
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('figure_part_types', function (Blueprint $table) {
            $table->integer('part_type_id')->unsigned();
            $table->integer('process_type_id')->unsigned();
            $table->integer('figure_id')->unsigned()->unique();

            /**
             * Add Foreign
             */
            $table->foreign('part_type_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('process_type_id')
                ->references('id')
                ->on('process_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['part_type_id', 'process_type_id']);
        });

        Schema::create('holes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('figure_id')->unsigned();
            $table->integer('number');      //順番
            $table->integer('horizontal');  //横軸
            $table->integer('vertical');    //縦軸
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('holes');
        Schema::drop('figure_part_types');
        Schema::drop('figures');
        Schema::drop('process_types');
        Schema::drop('part_types');
        Schema::drop('divisions');
        Schema::drop('vehicles');
    }
}
