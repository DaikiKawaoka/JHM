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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('entry_id');
            $table->string('action'); // 例: 会社説明会,一次面接,etc.
            $table->string('state'); // 例: 合格,不合格,保留中
            $table->date('action_date'); //締切日
            $table->foreign('user_id')->references('id')->on('users'); //外部キー参照
            $table->foreign('entry_id')->references('id')->on('entries'); //外部キー参照
            $table->unique(['user_id', 'entry_id','action']);
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
