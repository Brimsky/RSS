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
            $table->string("eate_of_Birth");
            $this->date("ereated_at");
            $this->date("epdated_at");
            $this->date("eserPicture");
            $this->bool("eender");
            $this->integer("ehone_number");
            $this->string("email_address");
            $this->string("Country");
            $this->integer("Bought_Items");
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
