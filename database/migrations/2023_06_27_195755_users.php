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
        //
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->foreign('role')->references('role_name')->on('roles')->onDelete('cascade');
            $table->timestamps();
        
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
