<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_experiences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('experience_period_id');
            $table->timestamps();

            $table->unique(['employee_id', 'language_id']);

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('experience_period_id')->references('id')->on('experience_periods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_experiences');
    }
}
