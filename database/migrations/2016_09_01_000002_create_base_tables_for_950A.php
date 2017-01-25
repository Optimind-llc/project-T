<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBaseTablesFor950A extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::connection('950A')->create('processes', function (Blueprint $table) {
            $table->string('en', 16);
            $table->string('name', 16)->unique();
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('en');
        });

        Schema::connection('950A')->create('inspections', function (Blueprint $table) {
            $table->string('en', 16);
            $table->string('name', 16)->unique();
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('en');
        });

        Schema::connection('950A')->create('inspection_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division', 16);
            $table->string('process_en', 16);
            $table->string('inspection_en', 16);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('process_en')
                ->references('en')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection_en')
                ->references('en')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });


        Schema::connection('950A')->create('chokus', function (Blueprint $table) {
            $table->string('code', 16);
            $table->string('name', 16)->unique();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->primary('code');
        });

        Schema::connection('950A')->create('workers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16)->unique();
            $table->string('yomi', 16);
            $table->string('code', 16)->unique();
            $table->tinyInteger('status')->default(1);
            $table->string('choku_code', 16);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('choku_code')
                ->references('code')
                ->on('chokus')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('950A')->create('worker_inspection_group', function (Blueprint $table) {
            $table->integer('worker_id')->unsigned();
            $table->integer('ig_id')->unsigned();
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('worker_id')
                ->references('id')
                ->on('workers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ig_id')
                ->references('id')
                ->on('inspection_groups')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['worker_id', 'ig_id']);
        });


        Schema::connection('950A')->create('part_types', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('pn')->unique();
            $table->bigInteger('pn2');
            $table->string('name', 16)->unique();
            $table->string('en', 16)->unique();
            $table->string('short_name', 16);
            $table->integer('sort')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::connection('950A')->create('figures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->string('path', 32);
            $table->integer('size_x')->unsigned();
            $table->integer('size_y')->unsigned();
            $table->integer('pt_id')->unsigned();
            $table->integer('ig_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('pt_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ig_id')
                ->references('id')
                ->on('inspection_groups')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });


        Schema::connection('950A')->create('failure_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16)->unique();
            $table->integer('label')->unsigned()->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::connection('950A')->create('ft_related', function (Blueprint $table) {
            $table->string('process_en', 16);
            $table->string('inspection_en', 16);
            $table->string('division', 16);
            $table->integer('type_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('process_en')
                ->references('en')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('inspection_en')
                ->references('en')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('type_id')
                ->references('id')
                ->on('failure_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['process_en', 'inspection_en', 'division', 'type_id']);
        });

        Schema::connection('950A')->create('modification_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->integer('label')->unsigned()->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::connection('950A')->create('mt_related', function (Blueprint $table) {
            $table->string('process_en', 16);
            $table->string('inspection_en', 16);
            $table->string('division', 16);
            $table->integer('type_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('process_en')
                ->references('en')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('inspection_en')
                ->references('en')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('type_id')
                ->references('id')
                ->on('modification_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['process_en', 'inspection_en', 'division', 'type_id']);
        });

        Schema::connection('950A')->create('hole_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('x')->unsigned()->default(0);
            $table->integer('y')->unsigned()->default(0);
            $table->integer('label')->unsigned()->default(1);
            $table->string('direction', 16);                    //ラベルの位置 top bottom left right
            $table->string('color', 16);                        //FFFFFF RGBの16進数
            $table->string('border', 16);                       //dotted or solid
            $table->string('shape', 16);                        //square or circle
            $table->integer('pt_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add Foreign
             */
            $table->foreign('pt_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('950A')->create('hole_modification_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 16);
            $table->integer('label')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::connection('950A')->create('hmt_related', function (Blueprint $table) {
            $table->string('process_en', 16);
            $table->string('inspection_en', 16);
            $table->string('division', 16);
            $table->integer('type_id')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->tinyInteger('sort')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('process_en')
                ->references('en')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('inspection_en')
                ->references('en')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('type_id')
                ->references('id')
                ->on('hole_modification_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['process_en', 'inspection_en', 'division', 'type_id']);
        });

        Schema::connection('950A')->create('inline_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('x')->unsigned()->default(0);
            $table->integer('y')->unsigned()->default(0);
            $table->integer('lx')->unsigned()->default(0);
            $table->integer('ly')->unsigned()->default(0);
            $table->string('side', 16);
            $table->string('face', 16)->nullable();
            $table->string('position', 16)->nullable();
            $table->string('calibration', 16)->nullable();
            $table->double('max_tolerance', 6, 3);
            $table->double('min_tolerance', 6, 3)->nullable();
            $table->integer('sort')->unsigned()->default(1);
            $table->integer('pt_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('pt_id')
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
        Schema::connection('950A')->drop('inline_types');
        Schema::connection('950A')->drop('hmt_related');
        Schema::connection('950A')->drop('hole_modification_types');
        Schema::connection('950A')->drop('hole_types');
        Schema::connection('950A')->drop('mt_related');
        Schema::connection('950A')->drop('modification_types');
        Schema::connection('950A')->drop('ft_related');
        Schema::connection('950A')->drop('failure_types');

        Schema::connection('950A')->drop('figures');
        Schema::connection('950A')->drop('part_types');

        Schema::connection('950A')->drop('worker_inspection_group');
        Schema::connection('950A')->drop('workers');
        Schema::connection('950A')->drop('chokus');

        Schema::connection('950A')->drop('inspection_groups');
        Schema::connection('950A')->drop('inspections');
        Schema::connection('950A')->drop('processes');
    }
}
