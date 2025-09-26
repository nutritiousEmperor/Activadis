<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inschrijvingen', function (Blueprint $table) {
           
            if (!Schema::hasColumn('inschrijvingen', 'activity_id')) {
                $table->foreignId('activity_id')
                      ->nullable()
                      ->constrained('activities')
                      ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('inschrijvingen', function (Blueprint $table) {
            if (Schema::hasColumn('inschrijvingen', 'activity_id')) {
                $table->dropForeign(['activity_id']);
                $table->dropColumn('activity_id');
            }
        });
    }
};