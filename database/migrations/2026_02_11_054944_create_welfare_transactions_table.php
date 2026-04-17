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
        Schema::create('welfare_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welfare_month_id')->constrained('welfare_months')->cascadeOnDelete();

            $table->enum('type', ['income', 'expense']); // income = add, expense = used
            $table->decimal('amount', 12, 2);

            $table->longText('purpose')->nullable();     // kis kaam me used (expense) / note (income)
            $table->date('tx_date')->nullable();

            $table->string('bill_image')->nullable();  // nullable bill image

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welfare_transactions');
    }
};
