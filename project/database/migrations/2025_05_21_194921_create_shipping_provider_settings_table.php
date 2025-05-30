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
        Schema::create('shipping_provider_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name')->unique();
            $table->string('api_key')->nullable();
            $table->string('account_number')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('default_service_type_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('other_settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_provider_settings');
    }
};
