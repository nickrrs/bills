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
        Schema::create('limits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('cascade')->nullable();
            $table->decimal('amount_spent', 20, 2);
            $table->decimal('amount_limit', 20, 2);
            $table->string('description')->nullable();
            $table->string('status')->default('active');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('recurrence')->default('monthly');
            $table->timestamp('start_date')->index();
            $table->timestamp('current_period_start')->index();
            $table->timestamp('current_period_end')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('limits');
    }
};
