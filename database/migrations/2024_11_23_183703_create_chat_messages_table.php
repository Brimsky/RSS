<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('recipient_id');
                $table->text('message');
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

                $table->foreign('recipient_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};