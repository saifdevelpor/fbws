<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'whatsapp_url')) {
            DB::statement('ALTER TABLE `orders` MODIFY `whatsapp_url` TEXT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'whatsapp_url')) {
            DB::statement('ALTER TABLE `orders` MODIFY `whatsapp_url` VARCHAR(255) NULL');
        }
    }
};
