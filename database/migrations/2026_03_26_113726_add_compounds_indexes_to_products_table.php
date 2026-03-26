<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['name', 'total', 'amount'], 'idx_name_total_amount');
            $table->index(['customer_name', 'total'], 'idx_customer_total');
            $table->index(['created_at', 'total', 'amount'], 'idx_created_total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_name_total_amount');
            $table->dropIndex('idx_customer_total');
            $table->dropIndex('idx_created_total_amount');
        });
    }
};
