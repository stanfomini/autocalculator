<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type');
            $table->text('body');
            $table->timestamps();

            $table->index(['sender_id', 'sender_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
