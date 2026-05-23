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
            $table->string('category', 32)->index();
            $table->string('subcategory')->nullable()->index();
            $table->string('brand')->nullable()->index();
            $table->string('engine_type', 32)->nullable()->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedInteger('price');
            $table->unsignedInteger('old_price')->nullable();
            $table->string('badge')->nullable();
            $table->string('image');
            $table->json('specs');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
