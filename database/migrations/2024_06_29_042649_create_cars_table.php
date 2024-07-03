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
    Schema::create('cars', function (Blueprint $table) {
      $table->id();
      $table->string('brand');
      $table->string('model');
      $table->string('color');
      $table->integer('year');
      $table->decimal('daily_price', 8, 2);
      $table->binary('photo')->nullable()->after('address')->comment('Photo of the car');
      $table->enum('status', ['available', 'rented', 'reserved', 'in revision']);
      $table->foreignId('company_id')->constrained('companies');
      $table->unsignedBigInteger('reserved_by')->nullable();
      $table->foreign('reserved_by')->references('id')->on('users')->onDelete('cascade');
      $table->timestamps(0);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cars');
  }
};
