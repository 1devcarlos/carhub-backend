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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('username');
      $table->string('email')->unique();
      $table->string('password');
      $table->string('phone')->nullable();
      $table->string('address');
      $table->binary('photo')->nullable();
      $table->enum('role', ['client', 'employee', 'admin'])->default('client');
      $table->timestamps(0);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
