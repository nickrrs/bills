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
        Schema::create('scheduled_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained('goals')->onDelete('cascade');
            $table->string('job_uuid')->unique()->index();
            $table->string('job_class')->index();
            $table->string('queue_name')->index();
            $table->timestamp('scheduled_at')->index();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->index();
            $table->timestamps();

            $table->index(['goal_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_jobs');
    }
};
