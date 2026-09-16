<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('establishment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('number');
            $table->string('status')->default('available');

            $table->timestamps();

            $table->unique(['establishment_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};