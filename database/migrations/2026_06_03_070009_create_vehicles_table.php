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
        Schema::create('vehicles', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->string('code')->unique()->nullable();
            $blueprint->enum('type', ['jeepney', 'bus', 'van']);
            $blueprint->text('description')->nullable();
            $blueprint->enum('status', ['active', 'inactive'])->default('active');
            $blueprint->foreignId('created_by')->constrained('users');
            $blueprint->foreignId('updated_by')->constrained('users');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
