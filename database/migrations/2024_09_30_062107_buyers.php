<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("buyer", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("surname");
            $table->string("date_of_Birth");
            $table->date("created_at");
            $table->date("updated_at");
            $table->date("userPicture");
            $table->boolean("eender");
            $table->integer("phone_number");
            $table->string("email_address");
            $table->string("Country");
            $table->integer("Bought_Items");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop("buyer");
    }
};
