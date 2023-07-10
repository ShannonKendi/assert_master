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

        Schema::create('volunteers', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->integer('phone_number');
            $table->binary('national_id')->nullable();
            $table->binary('conduct_certificate')->nullable();
            $table->boolean('is_validated');
            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
