<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('category', 50);
            $table->string('support_type', 50);
            $table->string('priority', 20)->default('medium');
            $table->decimal('amount_needed', 12, 2)->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->text('details');
            $table->string('status', 30)->default('new');
            $table->text('admin_note')->nullable();
            $table->boolean('is_seen_admin')->default(false);
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index(['category', 'support_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
