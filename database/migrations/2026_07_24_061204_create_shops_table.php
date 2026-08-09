php<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('shop_name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            $table->string('contact_number')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            
            $table->boolean('is_verified')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};