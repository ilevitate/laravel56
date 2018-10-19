<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOneWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('one_words', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hitokoto_id')->comment('一言的id');
            $table->string('hitokoto')->comment('一言正文');
            $table->string('type')->comment('一言的类型');
            $table->string('from')->comment('一言的出处');
            $table->string('creator')->comment('一言的添加者');
            $table->bigInteger('create_time')->comment('一言的添加时间');
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
        Schema::dropIfExists('one_words');
    }
}
