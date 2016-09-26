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
            $table->tinyInteger('line')->nullable()->unsigned();
            $table->string('inspector_group')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
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

        Schema::create('parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('panel_id');
            $table->integer('part_type_id')->unsigned();
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
        });

        Schema::create('part_page', function (Blueprint $table) {
            $table->integer('part_id')->unsigned();
            $table->integer('page_id')->unsigned();
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

        Schema::create('inlines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data');
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->integer('page_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('failure_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('point');
            $table->string('point_sub');
            $table->integer('page_id')->unsigned();
            $table->integer('failure_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('failure_id')
                ->references('id')
                ->on('failures')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('comment_failure_position', function (Blueprint $table) {
            $table->integer('page_id')->unsigned();
            $table->integer('failure_position_id')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('failure_position_id')
                ->references('id')
                ->on('failure_positions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Primary
             */
            $table->primary(['page_id', 'failure_position_id']);
        });

        Schema::create('hole_page', function (Blueprint $table) {
            $table->integer('hole_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->timestamps();

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

            /**
             * Add Primary
             */
            $table->primary(['hole_id', 'page_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('hole_page');
        Schema::drop('comment_failure_position');
        Schema::drop('failure_positions');
        Schema::drop('inlines');
        Schema::drop('photos');
        Schema::drop('part_page');
        Schema::drop('parts');
        Schema::drop('pages');
        Schema::drop('inspection_families');
    }
}
