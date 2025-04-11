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
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['serie_id']);
            $table->dropColumn('serie_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedBigInteger('serie_id')->nullable()->after('user_id');
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('set null');
        });
    }
};
