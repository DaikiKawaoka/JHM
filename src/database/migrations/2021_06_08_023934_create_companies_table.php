<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('prefecture')->nullable();
            $table->string('url')->nullable();
            $table->text('remarks')->nullable(); //備考
            $table->dateTime('deadline')->nullable(); //締切日
            $table->unsignedBigInteger('create_user_id');
            $table->foreign('create_user_id')->references('id')->on('users'); //外部キー参照
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
        Schema::dropIfExists('companies');
    }
}
