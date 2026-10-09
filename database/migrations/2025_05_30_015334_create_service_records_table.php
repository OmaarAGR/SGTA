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
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Appointment::class)->constrained()->onDelete('cascade');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->string('service_performed')->nullable();
            //$table->json('parts_used')->nullable();
            $table->decimal('labor_cost', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->foreignIdFor(\App\Models\Technician::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};
