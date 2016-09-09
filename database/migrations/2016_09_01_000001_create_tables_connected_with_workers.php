<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesConnectedWithWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {

        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('abbreviation')->unique();
            $table->timestamps();
        });

        Schema::create('workers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name');
            $table->integer('process_type_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->timestamps();

            /**
             * Add Foreign
             */
            $table->foreign('process_type_id')
                ->references('id')
                ->on('process_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            /**
             * Add Foreign
             */
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
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
        Schema::drop('workers');
        Schema::drop('groups');
    }
}
