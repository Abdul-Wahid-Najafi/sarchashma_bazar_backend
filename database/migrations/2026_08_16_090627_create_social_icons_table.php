<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_icons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // مثلا: Facebook
            $table->string('icon_url'); // مسیر ذخیره شده آیکون در سرور
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_icons');
    }
};
