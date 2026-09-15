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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('whatsapp')->nullable();
            
            $table->string('password')->nullable();
            $table->string('profile_picture')->nullable();
            $table->boolean('is_seller')->default(false);
            $table->boolean('is_shop_profile_complete')->default(false);
            $table->boolean('is_personal_profile_complete')->default(false);
            $table->enum('status', ['active', 'banned'])->default('active');
            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
