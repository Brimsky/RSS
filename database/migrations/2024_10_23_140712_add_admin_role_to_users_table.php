<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminRoleToUsersTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL DEFAULT 'buyer'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('buyer', 'seller') NOT NULL DEFAULT 'buyer'");
    }
}