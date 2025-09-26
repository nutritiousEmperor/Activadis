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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
