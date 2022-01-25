<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('student_company_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('student_company_id')->references('id')->on('student_companies');
            $table->string('create_year', 4);
            $table->string('create_month', 2);
            $table->string('create_day', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
