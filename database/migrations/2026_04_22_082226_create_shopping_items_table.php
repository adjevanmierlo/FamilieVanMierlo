<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('shopping_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('shopping_list_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('category')->nullable();
      $table->boolean('completed')->default(false);
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('shopping_items');
  }
};
