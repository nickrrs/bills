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
        Schema::create('goals_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade')->nullable();
            $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('cascade')->nullable();
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->timestamp('transaction_at')->index();
            $table->string('status')->default('active');
            $table->string('method')->default('manual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals_transactions');
    }
};
