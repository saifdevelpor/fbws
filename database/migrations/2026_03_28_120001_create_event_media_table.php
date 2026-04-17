<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $events = DB::table('events')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->get(['id', 'image']);

        foreach ($events as $row) {
            DB::table('event_media')->insert([
                'event_id' => $row->id,
                'type' => 'image',
                'path' => $row->image,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_media');
    }
};
