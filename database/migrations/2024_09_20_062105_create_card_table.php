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
        Schema::create('cards', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('card_title'); 
            $table->string('color_theme'); 
            $table->string('pronoun')->nullable(); 
            $table->string('preferred_name')->nullable(); 
            $table->string('image')->nullable(); 
            $table->string('cover_photo')->nullable(); 
            $table->string('qr_code_url')->nullable(); 
            $table->tinyInteger('status')->default(1); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};