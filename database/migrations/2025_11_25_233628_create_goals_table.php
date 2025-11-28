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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('set null')->nullable();
            $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('set null')->nullable();
            $table->decimal('amount', 20, 2)->default(0);
            $table->decimal('final_amount', 20, 2);
            $table->timestamp('due_at')->index();
            $table->string('description')->nullable();
            $table->string('status')->default('active');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('auto_mode')->default(false);
            $table->decimal('contribution_amount', 20, 2)->default(0);
            $table->timestamp('start_contribution_at')->index()->nullable();
            $table->timestamp('next_auto_contribution_at')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
