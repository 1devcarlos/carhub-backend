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
    Schema::create('rentals', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('car_id');
      $table->unsignedBigInteger('user_id');
      $table->date('start_date');
      $table->date('end_date');
      $table->decimal('total_price', 8, 2);
      $table->timestamps();

      $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('locations');
  }
};
