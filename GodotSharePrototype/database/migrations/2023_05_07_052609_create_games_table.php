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
        Schema::create('games', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->timestamps();
            $table->string('name');
            $table->integer('create_user_id');
            $table->string('outline');
            $table->text('detail');
            $table->string('thumbnail_url');
            $table->string('build_url');
            $table->integer('gameview_width');
            $table->integer('gameview_height');
            $table->boolean('is_mobile_friendly');
            $table->boolean('is_able_fullscreen');
            $table->tinyInteger('publish_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
