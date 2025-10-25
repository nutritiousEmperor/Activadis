<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inschrijvingen', function (Blueprint $table) {
            // Als ze nog niet bestaan
            if (!Schema::hasColumn('inschrijvingen', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('inschrijvingen', 'email')) {
                $table->string('email')->nullable()->after('guest_name');
            }

            // Unieke combinaties (MySQL laat meerdere NULLs toe, dus dit werkt voor gasten)
            $table->unique(['activity_id', 'user_id'], 'uniq_activity_user');
            $table->unique(['activity_id', 'email'],   'uniq_activity_email');
        });
    }

    public function down(): void
    {
        Schema::table('inschrijvingen', function (Blueprint $table) {
            $table->dropUnique('uniq_activity_user');
            $table->dropUnique('uniq_activity_email');

            // Alleen droppen als je echt terug moet
            $table->dropColumn(['guest_name', 'email']);
        });
    }
};
