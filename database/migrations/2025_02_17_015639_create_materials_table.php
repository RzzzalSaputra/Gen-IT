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
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('content');
            $table->string('file')->nullable();
            $table->string('img')->nullable();
            $table->text('link')->nullable();
            $table->foreignId('layout')->constrained('options');
            $table->foreignId('type')->constrained('options');
            $table->integer('read_counter')->default(0);
            $table->integer('download_counter')->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
}