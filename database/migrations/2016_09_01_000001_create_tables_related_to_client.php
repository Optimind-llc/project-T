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
            $table->string('line');
            $table->string('created_by');
            $table->string('updated_by');
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
            $table->string('table')->nullable();
            $table->tinyInteger('status')->default(1);
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

        Schema::create('inlines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data');
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

        Schema::create('hole_page', function (Blueprint $table) {
            $table->integer('hole_id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->integer('status')->unsigned();

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

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
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

        Schema::create('failure_page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('point');
            $table->string('point_sub');
            $table->integer('page_id')->unsigned();
            $table->integer('failure_id')->unsigned();
            $table->integer('comment_id')->unsigned()->nullable();
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

            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onUpdate('cascade')
                ->onDelete('restrict');
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
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('photos');
        Schema::drop('failure_page');
        Schema::drop('comments');
        Schema::drop('hole_page');
        Schema::drop('inlines');
        Schema::drop('part_page');
        Schema::drop('parts');
        Schema::drop('pages');
        Schema::drop('inspection_families');
    }
}
