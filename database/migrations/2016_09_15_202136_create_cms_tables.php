<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
			$table->integer('order');
			$table->enum('visible', ['Yes','No'])->default('Yes');
			$table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
			$table->softDeletes();

            $table->engine = 'InnoDB';
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('folder_id')->unsigned();
			$table->foreign('folder_id')->references('id')->on('folders');
            $table->string('name');
            $table->string('url');
			$table->text('content');
			$table->integer('order');
			$table->enum('visible', ['Yes','No'])->default('Yes');
			$table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
			$table->softDeletes();

            $table->engine = 'InnoDB';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('folders');
        Schema::drop('pages');
    }
}
