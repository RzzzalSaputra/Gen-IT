<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->text('file')->nullable();
            $table->text('img')->nullable();
            $table->text('video_url')->nullable();
            $table->foreignId('layout')->constrained('options');
            $table->foreignId('created_by')->constrained('users');
            $table->unsignedInteger('counter')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};  