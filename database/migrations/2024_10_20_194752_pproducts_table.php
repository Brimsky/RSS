<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->string("name");
            $table->text("description")->nullable();
            $table->decimal("price", 10, 2);
            $table->string("category");
            $table->string("subcategory");
            $table->string("condition");
            $table->string("location");
            $table->json("photos")->nullable();
            $table->unsignedInteger("clicks")->default(0);
            $table->unsignedInteger("saves_count")->default(0); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists("products");
    }
};
