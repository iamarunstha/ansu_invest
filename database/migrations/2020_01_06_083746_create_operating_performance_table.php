<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatingPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operating_performance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('heading');
            $table->unsignedInteger('ordering');
            $table->integer('sector_id');
            $table->enum('show_in_summary', ['yes','no'])->nullable();
            $table->enum('style',['bold'])->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->enum('in_graph',['yes','no'])->nullable();

            $table->foreign('sector_id')->references('id')->on('company_sector');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operating_performance');
    }
}
