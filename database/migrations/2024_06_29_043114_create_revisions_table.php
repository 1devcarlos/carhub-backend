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
    Schema::create('revisions', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('car_id');
      $table->text('description');
      $table->date('start_date');
      $table->date('end_date')->nullable();
      $table->timestamps();

      $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('revisions');
  }
};
