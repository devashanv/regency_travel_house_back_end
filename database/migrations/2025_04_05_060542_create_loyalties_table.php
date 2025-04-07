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
        Schema::create('loyalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('points_earned')->default(0);
            $table->integer('points_redeemed')->default(0);
            $table->enum('membership_tier', ['Bronze', 'Silver', 'Gold'])->default('Bronze');
            $table->timestamp('last_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalties');
    }
};
