php<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('identifier'); 
            $table->string('code');
            $table->boolean('is_used')->default(false);
            $table->enum('type', ['register', 'resetPassword'])->default('register');
            $table->timestamp('expires_at'); 
            $table->timestamps();
            $table->index(['identifier', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};