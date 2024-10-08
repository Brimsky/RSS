<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("seller", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("surname")->nullable();
            $table->string("seller_store_name")->nullable();
            $table->string("date_of_birth")->nullable();
            $table->integer("store_picture")->nullable();
            $table->boolean("gender")->nullable();
            $table->integer("phone_number")->nullable();
            $table->string("country")->nullable();
            $table->integer("bught_Items")->nullable();
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create("seller_password_reset_tokens", function (
            Blueprint $table
        ) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("seller_sessions", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->foreignId("seller_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("seller_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("seller");
        Schema::dropIfExists("seller_password_reset_tokens");
        Schema::dropIfExists("seller_sessions");
    }
};
