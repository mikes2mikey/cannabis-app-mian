<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('growth_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('notes');
            $table->decimal('height', 5, 2)->nullable();
            $table->enum('phase', ['seedling', 'vegetative', 'flowering', 'harvest'])->default('seedling');
            $table->decimal('temperature', 4, 1)->nullable();
            $table->decimal('humidity', 4, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('growth_logs');
    }
}; 