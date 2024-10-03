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
        Schema::create('admin', function (Blueprint $table) {
            $table->id(); 
            $table->string('username')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('user_type')->nullable();
            $table->string('role');
            $table->longText('verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp_expires_at')->nullable();
            $table->string('password');
            $table->string('user_image')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
