<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category', 80)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('price_cents');
            $table->unsignedInteger('stock')->default(0);
            $table->string('image_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('category');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
