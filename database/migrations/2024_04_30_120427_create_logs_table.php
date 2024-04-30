<?php

use App\Models\Device;
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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Device::class)
                ->index()
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('disk')->index();
            $table->string('level')->index();
            $table->string('log_resource')->index();
            $table->string('description');
            $table->string('path');

            $table->index(['level', 'log_resource']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
