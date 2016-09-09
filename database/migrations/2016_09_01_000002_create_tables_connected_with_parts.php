<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesConnectedWithParts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->increments('id');
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

        Schema::create('processes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('part_id')->unsigned();
            $table->integer('process_type_id')->unsigned();
            $table->string('worker_name');
            $table->string('worker_group_name');
            $table->string('worker_group_abbreviation');
            $table->tinyInteger('status');
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('process_type_id')
                ->references('id')
                ->on('process_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('hole_part', function (Blueprint $table) {
            $table->integer('process_id')->unsigned();
            $table->integer('hole_id')->unsigned();
            $table->string('type');

            /**
             * Add Foreign
             */
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('hole_id')
                ->references('id')
                ->on('holes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['process_id', 'hole_id']);
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->unique();
            $table->timestamps();
        });

        Schema::create('part_photo', function (Blueprint $table) {
            $table->integer('process_id')->unsigned();
            $table->integer('photo_id')->unsigned();
            $table->string('type');

            /**
             * Add Foreign
             */
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('photo_id')
                ->references('id')
                ->on('photos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['process_id', 'photo_id']);
        });

        Schema::create('failure_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('failures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('failure_type_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('failure_type_id')
                ->references('id')
                ->on('failure_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('failure_part', function (Blueprint $table) {
            $table->integer('process_id')->unsigned();
            $table->integer('failure_id')->unsigned();
            $table->string('type');

            /**
             * Add Foreign
             */
            $table->foreign('process_id')
                ->references('id')
                ->on('processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('failure_id')
                ->references('id')
                ->on('failures')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /**
             * Add Primary
             */
            $table->primary(['process_id', 'failure_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('failure_part');
        Schema::drop('failures');
        Schema::drop('failure_types');
        Schema::drop('part_photo');
        Schema::drop('photos');
        Schema::drop('hole_part');
        Schema::drop('processes');
        Schema::drop('parts');
    }
}
