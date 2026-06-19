<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('doctor_id')->constrained('doctors');
            $table->foreignId('schedule_id')->nullable()
                ->constrained('doctor_schedules')->restrictOnDelete();
            $table->dateTime('consultation_date')->nullable();
            $table->text('complaint')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'])
                ->default('pending');
            $table->timestamps();
            // Tanpa softDeletes: riwayat booking tidak boleh dihapus.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
