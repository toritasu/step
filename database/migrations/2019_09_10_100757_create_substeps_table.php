<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubstepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('substeps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('step_id')->unsigned();
            $table->integer('order');
            $table->string('title', 255);
            $table->string('description', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->boolean('delete_flg')->default(false);
            $table->timestamps();

            // 外部キーを設定する
            $table->foreign('step_id')->references('id')->on('steps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('substeps');
    }
}
