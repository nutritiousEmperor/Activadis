<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('activiteiten');
    }

    public function down(): void
    {
        // alleen voor rollback; hoeft niet gebruikt te worden
        Schema::create('activiteiten', function ($table) {
            $table->id();
            $table->string('titel');
            $table->text('omschrijving')->nullable();
            $table->date('datum');
            $table->time('tijd');
            $table->string('locatie');
            $table->integer('max_deelnemers')->nullable();
            $table->timestamps();
        });
    }
};
