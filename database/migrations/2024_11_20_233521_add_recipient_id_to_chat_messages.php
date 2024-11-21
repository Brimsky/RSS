<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_messages', 'recipient_id')) {
                $table->unsignedBigInteger('recipient_id')->nullable()->after('user_id');
                $table->foreign('recipient_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropColumn('recipient_id');
        });
    }
};
