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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // what happened
            $table->string('event'); // created, updated, deleted, login, etc
            $table->string('module')->nullable(); // Payments, Items, Borrow, Fines etc
            $table->string('action')->nullable(); // e.g. "Fee Added", "Item Returned"

            // which record was affected
            $table->nullableMorphs('auditable'); // auditable_type, auditable_id

            // details
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // request info
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
