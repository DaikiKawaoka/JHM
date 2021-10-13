<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('entry_id');
            $table->string('action'); // 例: 説明会,一次面接,etc.
            $table->string('state'); // 例: 合格,不合格,保留中
            $table->date('action_date'); //締切日
            $table->foreign('student_id')->references('id')->on('students'); //外部キー参照
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade'); //外部キー参照
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
        Schema::dropIfExists('progress');
    }
}
