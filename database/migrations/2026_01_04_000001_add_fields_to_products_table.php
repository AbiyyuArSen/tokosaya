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
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom yang hilang agar insert tidak error
            $table->string('nama', 255)->nullable()->after('store_name');
            $table->decimal('price', 10, 2)->default(0)->after('nama');
            $table->text('description')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['nama', 'price', 'description']);
        });
    }
};
