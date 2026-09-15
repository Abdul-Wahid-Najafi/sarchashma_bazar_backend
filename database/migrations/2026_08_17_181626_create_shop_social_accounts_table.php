<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            
            $table->foreignId('social_icon_id')->constrained('social_icons')->cascadeOnDelete();
            
            $table->string('link'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_social_accounts');
    }
};
