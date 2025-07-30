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
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['digital', 'physical']);
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->integer('stock')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->foreignId('category_id')->nullable()->constrained('shop_categories')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('shop_product_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('shop_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_tag');
        Schema::dropIfExists('shop_products');
    }
};
