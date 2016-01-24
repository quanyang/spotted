<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('is_lost')->default(0);
            $table->string('characteristics');
            $table->string('frequency');
            $table->string('category');
            $table->string('full_name');
            $table->integer('number');
            $table->string('email');
            $table->string('pet_name');
            $table->integer('status')->unsigned();
            $table->timestamps();
        });

        Schema::table('reports',function($table) {
            DB::statement('ALTER TABLE reports ADD location POINT' );
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
        Schema::drop('reports');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
