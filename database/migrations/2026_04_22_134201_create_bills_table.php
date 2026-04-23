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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

    $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
    $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();

    $table->decimal('amount', 8, 2);

    $table->enum('status', ['paid', 'pending'])->default('pending');

    $table->dateTime('paid_at')->nullable();

    $table->softDeletes();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
