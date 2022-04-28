<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyValuationDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_valuation_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id');
            $table->integer('fiscal_year_id');            
            $table->unsignedBigInteger('heading_id');

            $table->integer('sub_year_id')->nullable();
            $table->string('value');

            $table->foreign('company_id')->references('id')->on('company');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->foreign('heading_id')->references('id')->on('company_valuation');
             $table->foreign('sub_year_id')->references('id')->on('sub_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_valuation_data');
    }
}
