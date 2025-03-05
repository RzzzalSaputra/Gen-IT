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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('title', 255);
            $table->string('content');
            $table->string('summary', 255);
            $table->foreignId('status')->constrained('options');
            $table->foreignId('type')->constrained('options');
            $table->string('writer', 255);
            $table->timestamp('post_time');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps(); // This creates created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

