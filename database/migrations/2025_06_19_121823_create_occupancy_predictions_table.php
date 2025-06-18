<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('occupancy_predictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->date('date');
            $table->decimal('predicted_occupancy', 5, 2);
            $table->decimal('actual_occupancy', 5, 2)->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->string('prediction_method')->default('weighted_multi_factor');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unique(['hotel_id', 'date']);
            $table->index(['date', 'hotel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('occupancy_predictions');
    }
};
