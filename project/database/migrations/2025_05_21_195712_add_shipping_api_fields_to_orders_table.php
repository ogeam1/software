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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_service_name')->nullable();
            $table->string('shipping_label_url')->nullable();
            $table->decimal('shipping_rate_cost', 10, 2)->nullable();
            $table->boolean('is_split_shipment')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_service_name');
            $table->dropColumn('shipping_label_url');
            $table->dropColumn('shipping_rate_cost');
            $table->dropColumn('is_split_shipment');
        });
    }
};
