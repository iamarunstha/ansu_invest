<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentExistingIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_existing_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tab_id');
            $table->string('symbol');
            $table->string('company_name');
            $table->bigInteger('units');
            $table->date('opening_date');
            $table->date('closing_date');
            $table->date('last_closing_date')->nullable();
            $table->date('book_closure_date')->nullable();
            $table->string('ratio')->nullable();
            $table->integer('price')->nullable();
            $table->string('issue_manager');
            $table->enum('status', ['open', 'closed']);
            $table->text('view');
            $table->enum('eligibility_check', ['closed', 'unavailable'])->nullable();

            $table->foreign('tab_id')->references('id')->on('investment_existing_issues_tabs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_existing_issues');
    }
}
