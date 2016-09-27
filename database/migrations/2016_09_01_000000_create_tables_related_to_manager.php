<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesRelatedToManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('en')->unique();
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('inspector_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('inspectors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('code')->unsigned()->unique();
            $table->integer('group_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('group_id')
                ->references('id')
                ->on('inspector_groups')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('inspector_process', function (Blueprint $table) {
            $table->integer('inspector_id')->unsigned();
            $table->integer('process_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('inspector_id')
                ->references('id')
                ->on('inspectors')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['inspector_id', 'process_id']);
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('en');
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('process_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->string('name')->unique();
            $table->string('en');
            $table->timestamps();

            $table->primary('en');
        });

        Schema::create('inspection_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division_en');
            $table->integer('inspection_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('division_en')
                ->references('en')
                ->on('divisions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('figures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('path')->unique();
            $table->timestamps();
        });

        Schema::create('page_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number')->unsigned()->defaul(1);
            $table->integer('group_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('group_id')
                ->references('id')
                ->on('inspection_groups')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->string('name')->unique()->nullable();
            $table->integer('sort')->unsigned()->defaul(1);
            $table->timestamps();
        });

        Schema::create('part_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pn')->unique()->unsigned();
            $table->string('name')->unique();
            $table->integer('vehicle_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('part_type_page_type', function (Blueprint $table) {
            $table->integer('part_type_id')->unsigned();
            $table->integer('page_type_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('part_type_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('page_type_id')
                ->references('id')
                ->on('page_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['part_type_id', 'page_type_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->integer('inspection_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('failures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('failure_process', function (Blueprint $table) {
            $table->integer('failure_id')->unsigned();
            $table->integer('process_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('failure_id')
                ->references('id')
                ->on('failures')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['failure_id', 'process_id']);
        });

        Schema::create('holes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('point');
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('figure_id')->unsigned();
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
        Schema::drop('failure_process');
        Schema::drop('failures');
        Schema::drop('comments');
        Schema::drop('part_type_page_type');
        Schema::drop('part_types');
        Schema::drop('vehicles');
        Schema::drop('page_types');
        Schema::drop('figures');
        Schema::drop('inspection_groups');
        Schema::drop('divisions');
        Schema::drop('inspections');
        Schema::drop('inspector_process');
        Schema::drop('inspectors');
        Schema::drop('inspector_groups');
        Schema::drop('processes');
    }
}
