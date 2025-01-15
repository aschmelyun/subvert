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
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['video', 'audio']);
            $table->string('file_path')->nullable(); // For local files
            $table->string('external_url')->nullable(); // For external files
            $table->string('mime_type')->nullable();
            $table->bigInteger('file_size')->nullable(); // In bytes
            $table->integer('duration')->nullable(); // In seconds
            $table->string('thumbnail_path')->nullable();
            $table->longText('transcript')->nullable(); // In vtt subtitles format
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamp('transcribed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
