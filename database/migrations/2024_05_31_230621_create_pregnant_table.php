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
        Schema::create('pregnant', function (Blueprint $table) {
            $table->id();
            $table->date('first_day_of_last_period')->nullable();
            $table->date('estimated_due_date')->nullable();
            $table->date('date_of_conception')->nullable();
            $table->unsignedInteger('age_by_week')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnant');
    }
};
