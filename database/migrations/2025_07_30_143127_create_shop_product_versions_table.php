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
        Schema::create('shop_product_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->string('version');
            $table->string('changelog')->nullable();
            $table->string('file_path');
            $table->string('file_hash');
            $table->integer('ttl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_product_versions');
    }
};
