<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->text('content');
            $table->binary('file')->nullable();
            $table->text('link')->nullable();
            $table->binary('img')->nullable();
            $table->foreignId('layout')->constrained('options');
            $table->string('type');
            $table->integer('read_counter');
            $table->integer('download_counter');
            $table->foreignId('create_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};