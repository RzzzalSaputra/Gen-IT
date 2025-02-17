<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->binary('file');
            $table->text('link');
            $table->binary('img');
            $table->foreignId('type')->constrained('options');
            $table->foreignId('status')->constrained('options');
            $table->integer('read_counter');
            $table->integer('download_counter');
            $table->date('approve_at');
            $table->foreignId('approve_by')->constrained('users');
            $table->foreignId('create_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};