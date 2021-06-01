<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateModelHasPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_pages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('pagable_id');
            $table->string('pagable_type');

            $table->string('name');
            $table->unsignedBigInteger('page_id')->nullable();

            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_pages');
    }
}
