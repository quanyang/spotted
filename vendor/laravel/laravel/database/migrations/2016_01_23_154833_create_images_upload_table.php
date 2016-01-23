<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('publicId')->unique();
            $table->string('fileName')->unique();
            $table->integer('report_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('images', function($table) {
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('images');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
