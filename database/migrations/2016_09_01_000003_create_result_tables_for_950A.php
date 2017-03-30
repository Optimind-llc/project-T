<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResultTablesFor950A extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::connection('950A')->create('part_families', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 16);
            $table->string('associated_by')->nullable();
            $table->timestamps();
        });

        Schema::connection('950A')->create('parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('panel_id');
            $table->bigInteger('pn')->unsigned();
            $table->integer('family_id')->unsigned()->nullable();
            $table->timestamps();

            /*
             * Add Index
             */
            $table->unique(['panel_id', 'pn']);

            /*
             * Add Foreign
             */
            $table->foreign('pn')
                ->references('pn')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('family_id')
                ->references('id')
                ->on('part_families')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('950A')->create('inspection_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('part_id')->unsigned();
            $table->string('process', 16);
            $table->string('inspection', 16);
            $table->tinyInteger('line')->unsigned()->default(1);
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->string('comment')->nullable();
            $table->string('table')->nullable();
            $table->string('created_choku', 8);
            $table->string('updated_choku', 8)->nullable();
            $table->string('created_by', 8);
            $table->string('updated_by', 8)->nullable();
            $table->string('ft_ids', 512)->nullable();
            $table->string('mt_ids', 512)->nullable();
            $table->string('hmt_ids', 512)->nullable();
            $table->tinyInteger('latest')->unsigned()->default(1);
            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();
            $table->timestamp('exported_at')->nullable();

            /*
             * Add Foreign
             */
            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('process')
                ->references('en')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inspection')
                ->references('en')
                ->on('inspections')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::connection('950A')->create('failures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('x')->unsigned()->default(0);
            $table->integer('y')->unsigned()->default(0);
            $table->integer('sub_x')->unsigned()->default(0);
            $table->integer('sub_y')->unsigned()->default(0);
            $table->tinyInteger('face')->unsigned()->default(1);
            $table->integer('type_id')->unsigned();
            $table->integer('ir_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('failure_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ir_id')
                ->references('id')
                ->on('inspection_results')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::connection('950A')->create('modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('failure_id')->unsigned();
            $table->integer('ir_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->string('comment', 100)->nullable();
            $table->timestamps();

            /*
             * Add Index
             */
            $table->unique(['type_id','failure_id','figure_id']);

            /*
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('modification_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('failure_id')
                ->references('id')
                ->on('failures')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ir_id')
                ->references('id')
                ->on('inspection_results')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::connection('950A')->create('holes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('ir_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->unique(['type_id', 'part_id']);

            /**
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('hole_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ir_id')
                ->references('id')
                ->on('inspection_results')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::connection('950A')->create('hole_modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('hole_id')->unsigned();
            $table->integer('ir_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->string('comment', 100)->nullable();
            $table->timestamps();

            /**
             * Add Index
             */
            $table->unique(['type_id','hole_id','part_id']);

            /**
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('hole_modification_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('hole_id')
                ->references('id')
                ->on('holes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ir_id')
                ->references('id')
                ->on('inspection_results')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::connection('950A')->create('inlines', function (Blueprint $table) {
            $table->increments('id');
            $table->double('status', 6, 3)->nullable();
            $table->integer('type_id')->unsigned();
            $table->integer('ir_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->integer('figure_id')->unsigned();
            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('type_id')
                ->references('id')
                ->on('inline_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ir_id')
                ->references('id')
                ->on('inspection_results')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('figure_id')
                ->references('id')
                ->on('figures')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::connection('950A')->drop('inlines');
        Schema::connection('950A')->drop('hole_modifications');
        Schema::connection('950A')->drop('holes');
        Schema::connection('950A')->drop('modifications');
        Schema::connection('950A')->drop('failures');
        Schema::connection('950A')->drop('inspection_results');
        Schema::connection('950A')->drop('parts');
        Schema::connection('950A')->drop('part_families');
    }
}





