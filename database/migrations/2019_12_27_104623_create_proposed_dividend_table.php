<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposedDividendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposed_dividend', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol');
            $table->integer('bonus');
            $table->integer('cash');
            $table->integer('fiscal_year_id');
            $table->integer('sector_id');
            $table->string('company_name');
            $table->date('distribution_date')->nullable();
            $table->date('book_closure_date')->nullable();

            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
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
        Schema::dropIfExists('proposed_dividend');
    }
}
