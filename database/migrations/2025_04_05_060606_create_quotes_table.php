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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->integer('number_of_people');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('special_requests')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'responded', 'expired'])->default('pending');
            $table->foreignId('responded_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
