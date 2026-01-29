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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->date('join_date');
            $table->integer('duration_days')->default(365);
            $table->date('expiry_date')->nullable();
            $table->decimal('fee', 8,2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
