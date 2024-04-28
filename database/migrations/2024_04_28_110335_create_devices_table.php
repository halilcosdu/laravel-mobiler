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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_banned')->index()->default(false);
            $table->boolean('is_premium')->index()->default(false);
            $table->string('timezone')->default(config('app.timezone'));
            $table->string('os_type');
            $table->string('os_version');
            $table->string('device_name');
            $table->string('device_type');
            $table->string('app_version');
            $table->string('client_device_code')->index();
            $table->string('language_code');
            $table->string('country_code');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
