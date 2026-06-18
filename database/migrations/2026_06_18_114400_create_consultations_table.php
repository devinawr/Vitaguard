<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->text('summary')->nullable();
            $table->text('diagnosis')->nullable();
            $table->timestamps();
            // Tanpa softDeletes: riwayat konsultasi tidak boleh dihapus.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
