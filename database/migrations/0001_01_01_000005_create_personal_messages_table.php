<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_id');
            $table->unsignedBigInteger('to_id');
            $table->text('message');
            $table->timestamps();

            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_messages');
    }
}
