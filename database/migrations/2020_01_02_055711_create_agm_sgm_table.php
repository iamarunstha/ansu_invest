<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgmSgmTable extends Migration
{

    protected $casts = [
        'time' => 'hh:mm'
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agm_sgm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol');
            $table->string('company_name');
            $table->integer('agm');
            $table->string('venue');
            $table->time('time');
            $table->integer('fiscal_year_id');
            $table->integer('sector_id');
            $table->date('book_closure_date');
            $table->date('agm_date');
            $table->text('agenda');

            $table->foreign('sector_id')->references('id')->on('company_sector');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agm_sgm');
    }
}
