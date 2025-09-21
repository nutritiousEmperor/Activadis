<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->date('date')->nullable();
        $table->time('time')->nullable();
        $table->string('location')->nullable();
        $table->integer('max_participants')->nullable();
    });
}

public function down()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->dropColumn(['date', 'time', 'location', 'max_participants']);
    });
}

};
