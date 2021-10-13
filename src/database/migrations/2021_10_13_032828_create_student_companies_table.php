<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('prefecture')->nullable();
            $table->unsignedBigInteger('create_student_id');
            $table->foreign('create_student_id')->references('id')->on('students'); //外部キー参照
            $table->softDeletes();
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
        Schema::dropIfExists('student_companies');
    }
}
