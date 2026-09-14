<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_found_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_id')->constrained('fans')->cascadeOnDelete();
            $table->string('item_description');
            $table->string('location')->nullable();
            $table->date('date_lost')->nullable();
            $table->string('status')->default('reported');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_reports');
    }
};
