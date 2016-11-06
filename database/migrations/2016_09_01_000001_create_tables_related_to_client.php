<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesRelatedToClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('inspection_families', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->nullable()->unsigned();
            $table->string('inspector_group')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamp('inspected_at')->nullable();
            $table->integer('inspection_group_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('inspection_group_id')
                ->references('id')
                ->on('inspection_groups')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->default(1)->unsigned();
            $table->string('table')->nullable();
            $table->integer('family_id')->unsigned();
            $table->integer('page_type_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('family_id')
                ->references('id')
                ->on('inspection_families')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('page_type_id')
                ->references('id')
                ->on('page_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('part_families', function (Blueprint $table) {
            $table->increments('id');
            $table->string('associated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('panel_id');
            $table->integer('part_type_id')->unsigned();
            $table->integer('family_id')->unsigned()->nullable();
            $table->timestamps();

            /**
             * Add Index
             */
            $table->unique(['panel_id','part_type_id']);

            /**
             * Add Foreign
             */
            $table->foreign('part_type_id')
                ->references('id')
                ->on('part_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('family_id')
                ->references('id')
                ->on('part_families')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('part_page', function (Blueprint $table) {
            $table->integer('part_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->integer('status')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['part_id', 'page_id']);
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->integer('family_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('family_id')
                ->references('id')
                ->on('inspection_families')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('inline_page', function (Blueprint $table) {
            $table->increments('id');
            $table->double('status', 6, 3)->nullable();
            $table->integer('inline_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->timestamp('inspected_at')->nullable();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('inline_id')
                ->references('id')
                ->on('inlines')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('failure_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('point');
            $table->string('point_sub');
            $table->string('type');
            $table->integer('failure_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('failure_id')
                ->references('id')
                ->on('failures')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('modification_failure_position', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->integer('fp_id')->unsigned();
            $table->integer('m_id')->unsigned();
            $table->string('comment', 100);
            $table->timestamps();

            /**
             * Add Index
             */
            $table->unique(['page_id','fp_id','m_id']);

            /**
             * Add Foreign
             */
            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('fp_id')
                ->references('id')
                ->on('failure_positions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('m_id')
                ->references('id')
                ->on('modifications')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('hole_page', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hole_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();

            /**
             * Add Primary
             */
            $table->unique(['hole_id', 'page_id']);

            /**
             * Add Foreign
             */
            $table->foreign('hole_id')
                ->references('id')
                ->on('holes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('hole_page_hole_modification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->integer('hp_id')->unsigned();
            $table->integer('hm_id')->unsigned();
            $table->string('comment', 100);
            $table->timestamps();

            /**
             * Add Index
             */
            $table->unique(['page_id','hp_id','hm_id']);

            /**
             * Add Foreign
             */
            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('hp_id')
                ->references('id')
                ->on('hole_page')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('hm_id')
                ->references('id')
                ->on('hole_modifications')
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
        Schema::drop('hole_page_hole_modification');
        Schema::drop('hole_page');
        Schema::drop('modification_failure_position');
        Schema::drop('failure_positions');
        Schema::drop('inline_page');
        Schema::drop('photos');
        Schema::drop('part_page');
        Schema::drop('parts');
        Schema::drop('part_families');
        Schema::drop('pages');
        Schema::drop('inspection_families');
    }
}
