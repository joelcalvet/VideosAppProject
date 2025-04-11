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
        Schema::create('serie_video', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('serie_id');
            $table->unsignedBigInteger('video_id');
            $table->timestamps();

            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

            $table->unique(['serie_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serie_video');
    }
};
