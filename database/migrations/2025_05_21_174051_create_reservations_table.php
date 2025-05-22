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
        Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
    $table->date('check_in_date');
    $table->date('check_out_date');
    $table->enum('status', ['confirmed', 'cancelled', 'checked_in', 'checked_out', 'no_show'])->default('confirmed');
    $table->integer('number_of_guests');
    $table->string('cancellation_reason')->nullable();
    $table->date('cancellation_date')->nullable();
    $table->string('confirmation_number')->unique();
    $table->boolean('auto_cancelled')->default(false);
    $table->boolean('no_show_billed')->default(false);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
