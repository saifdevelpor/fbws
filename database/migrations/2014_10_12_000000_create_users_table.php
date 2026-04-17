<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('profile_photo')->nullable(); // Optional profile photo
            $table->string('id_card')->nullable();
            $table->longText('address')->nullable();
            $table->string('phone_number')->nullable(); // Optional phone number
            $table->string('role')->default('user'); // User role (Admin, User)
            $table->string('position')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Track who created the user
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
