<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Initial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });
        
        Schema::create('poll_option', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('poll_id')->unsigned();
            $table->string('description');
            $table->foreign('poll_id')
                ->references('id')
                ->on('poll')
                ->onDelete('cascade');
        });
        
        Schema::create('poll_vote', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('option_id')->unsigned();
            $table->foreign('option_id')
                ->references('id')
                ->on('poll_option')
                ->onDelete('cascade');
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
        Schema::dropIfExists('poll_vote');
        Schema::dropIfExists('poll_option');
        Schema::dropIfExists('poll');
    }
}
