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
            $table->string('name', 16)->unique();
            $table->string('id', 16);
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('id');
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->string('en', 16);
            $table->integer('sort')->unsigned()->default(1);
            $table->string('process_id');
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
            $table->string('name', 16)->unique();
            $table->string('en', 16);
            $table->timestamps();

            $table->primary('en');
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->string('number', 16);
            $table->string('name', 16)->unique()->nullable();
            $table->integer('sort')->unsigned()->defaul(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('number');
        });

        Schema::create('inspection_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division_en', 16);
            $table->string('vehicle_num', 16);
            $table->string('line', 16)->nullable();
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

            $table->foreign('vehicle_num')
                ->references('number')
                ->on('vehicles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('inspector_groups', function (Blueprint $table) {
            $table->string('code', 16);
            $table->string('name', 16)->unique();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('code');
        });

        Schema::create('inspectors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16)->unique();
            $table->string('code', 16)->unique();
            $table->string('group_code');
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('group_code')
                ->references('code')
                ->on('inspector_groups')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('inspector_inspection_group', function (Blueprint $table) {
            $table->integer('inspector_id')->unsigned();
            $table->integer('inspection_g_id')->unsigned();
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('inspector_id')
                ->references('id')
                ->on('inspectors')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_g_id')
                ->references('id')
                ->on('inspection_groups')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['inspector_id', 'inspection_g_id']);
        });

        Schema::create('figures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 32);
            $table->timestamps();
        });

        Schema::create('pdf_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 64)->unique();
            $table->string('area', 64);
            $table->string('reference');

            $table->timestamps();
        });

        Schema::create('page_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number')->unsigned()->defaul(1);
            $table->integer('group_id')->unsigned();
            $table->integer('figure_id')->unsigned()->nullable();
            $table->integer('pdf_id')->unsigned()->nullable();
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

            $table->foreign('pdf_id')
                ->references('id')
                ->on('pdf_templates')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('part_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pn')->unique()->unsigned();
            $table->string('name', 16)->unique();
            $table->string('short_name', 16);
            $table->integer('sort')->unsigned()->default(1);
            $table->string('vehicle_num');
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('vehicle_num')
                ->references('number')
                ->on('vehicles')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('part_type_page_type', function (Blueprint $table) {
            $table->integer('part_type_id')->unsigned();
            $table->integer('page_type_id')->unsigned();
            $table->integer('figure_id')->unsigned()->nullable()->default(null);
            $table->string('area', 64)->nullable()->default('null');
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

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['part_type_id', 'page_type_id']);
        });

        Schema::create('failures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16)->unique();
            $table->integer('label')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('failure_inspection', function (Blueprint $table) {
            $table->integer('failure_id')->unsigned();
            $table->integer('inspection_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('failure_id')
                ->references('id')
                ->on('failures')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['failure_id', 'inspection_id']);
        });

        Schema::create('holes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('point', 16);
            $table->integer('label')->unsigned()->default(1);
            $table->string('direction', 16); //ラベルの位置 top bottom left right
            $table->string('color', 16); //0-0-0 RGBの-区切り
            $table->string('border', 16); //dotted or solid
            $table->string('shape', 16); //square or circle
            $table->integer('part_type_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('part_type_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->string('label', 16);
            $table->timestamps();
        });

        Schema::create('modification_inspection', function (Blueprint $table) {
            $table->integer('modification_id')->unsigned();
            $table->integer('inspection_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('modification_id')
                ->references('id')
                ->on('modifications')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['modification_id', 'inspection_id']);
        });

        Schema::create('hole_modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->integer('label')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('hole_modification_inspection', function (Blueprint $table) {
            $table->integer('hole_m_id')->unsigned();
            $table->integer('inspection_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('hole_m_id')
                ->references('id')
                ->on('hole_modifications')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['hole_m_id', 'inspection_id']);
        });

        Schema::create('inlines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('point', 16);
            $table->string('label_point', 16);
            $table->string('side', 16);
            $table->string('face', 16)->nullable();
            $table->string('position', 16)->nullable();
            $table->string('calibration', 16)->nullable();
            $table->double('max_tolerance', 6, 3);
            $table->double('min_tolerance', 6, 3)->nullable();
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('part_type_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('part_type_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

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
        Schema::drop('inlines');
        Schema::drop('hole_modification_inspection');
        Schema::drop('hole_modifications');
        Schema::drop('modification_inspection');
        Schema::drop('modifications');
        Schema::drop('holes');
        Schema::drop('failure_inspection');
        Schema::drop('failures');
        Schema::drop('part_type_page_type');
        Schema::drop('part_types');
        Schema::drop('page_types');
        Schema::drop('pdf_templates');
        Schema::drop('figures');
        Schema::drop('inspector_inspection_group');
        Schema::drop('inspectors');
        Schema::drop('inspector_groups');
        Schema::drop('inspection_groups');
        Schema::drop('vehicles');
        Schema::drop('divisions');
        Schema::drop('inspections');
        Schema::drop('processes');
    }
}
