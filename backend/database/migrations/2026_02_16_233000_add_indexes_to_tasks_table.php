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
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
            // Full-text index للبحث السريع في العناوين (اختياري وحسب نوع الداتابيس)
            // $table->fullText(['title', 'description']); // MySQL supports this
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['due_date']);
            // $table->dropFullText(['title', 'description']);
        });
    }
};
