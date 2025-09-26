<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inschrijvingen', function (Blueprint $table) {
            // Verwijder de FK die je foutmelding noemt:
            $table->dropForeign('inschrijvingen_activiteit_id_foreign');
        });
    }

    public function down(): void
    {
        Schema::table('inschrijvingen', function (Blueprint $table) {
            // Zet de FK terug (naar de oude tabel 'activiteiten') als je ooit wilt rollbacken
            $table->foreign('activiteit_id')
                  ->references('id')->on('activiteiten')
                  ->cascadeOnDelete();
        });
    }
};
