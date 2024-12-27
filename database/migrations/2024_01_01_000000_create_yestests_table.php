<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('yestests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 30);
            $table->dateTime('scheduled_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('yestests');
    }
};