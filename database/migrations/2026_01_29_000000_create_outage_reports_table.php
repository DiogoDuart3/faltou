<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outage_reports', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 20);
            $table->decimal('lat', 8, 5);
            $table->decimal('lng', 8, 5);
            $table->string('locality', 120)->nullable();
            $table->string('note', 160)->nullable();
            $table->string('impact', 40)->nullable();
            $table->string('method', 20)->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outage_reports');
    }
};
