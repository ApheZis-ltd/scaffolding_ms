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
        Schema::create('in_flight_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('contacts')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->string('site_details')->nullable();
            $table->integer('qty');
            $table->string('status')->default('pending'); // pending, shipped, delivered
            $table->decimal('pricing', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_flight_orders');
    }
};
