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
        Schema::create('welfare_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year'); // 2026 etc

            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('total_received', 12, 2)->default(0);
            $table->decimal('total_used', 12, 2)->default(0);
            $table->decimal('closing_balance', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welfare_months');
    }
};
