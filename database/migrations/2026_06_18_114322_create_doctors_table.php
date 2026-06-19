<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->restrictOnDelete();
            $table->string('specialty');
            $table->string('license_number')->unique();
            $table->unsignedInteger('experience_years')->default(0);
            $table->text('bio')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('photo')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Woman', 'Man'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
