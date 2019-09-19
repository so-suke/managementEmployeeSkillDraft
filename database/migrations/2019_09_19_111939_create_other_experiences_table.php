<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_experiences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('other_id');
            $table->unsignedBigInteger('experience_period_id');
            $table->timestamps();

            $table->unique(['employee_id', 'other_id']);

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('other_id')->references('id')->on('others');
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
        Schema::dropIfExists('other_experiences');
    }
}
