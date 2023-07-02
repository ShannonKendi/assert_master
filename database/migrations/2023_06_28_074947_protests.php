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
        Schema::create('protests', function (Blueprint $table) {
            $table->string('protest_id')->unique();
            $table->string('title');
            $table->longText('description');
            $table->string('venue');
            $table->date('event_date');
            $table->boolean('is_validated');
            $table->string('creator_token');
            $table->foreign('creator_token')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
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
