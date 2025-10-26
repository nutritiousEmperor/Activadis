<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) bestaande NULLs -> 0
        DB::table('activities')->whereNull('min_participants')->update(['min_participants' => 0]);

        // 2) kolom aanpassen: nullable laten staan (mag), maar wel default 0
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('min_participants')->nullable()->default(0)->change();
        });
    }

    public function down(): void
    {
        // terugdraaien: default weg (laat weer null toe zonder default)
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('min_participants')->nullable()->default(null)->change();
        });
    }
};
