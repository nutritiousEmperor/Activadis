<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inschrijvingen', function (Blueprint $table) {
            $table->id();
           $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_email')->nullable();
            $table->timestamps();

            $table->unique(['activity_id','user_id']);
            $table->unique(['activity_id','guest_email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inschrijvingen');
    }
};
