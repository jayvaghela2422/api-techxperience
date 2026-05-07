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
        Schema::create('customer_stories', function (Blueprint $table) {
            $table->id();
            $table->string('project_title');
            $table->string('category')->nullable();
            $table->string('client_name')->nullable();
            $table->text('short_description')->nullable();
            $table->text('challenge')->nullable();
            $table->text('solution')->nullable();
            $table->string('key_results')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_stories');
    }
};
