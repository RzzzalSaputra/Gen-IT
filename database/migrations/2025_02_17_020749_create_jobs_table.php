<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->text('title');
            $table->text('description');
            $table->text('requirement');
            $table->integer('salary_range');
            $table->integer('register_link');
            $table->binary('file');
            $table->text('link');
            $table->binary('img');
            $table->foreignId('type')->constrained('options');
            $table->foreignId('experience')->constrained('options');
            $table->integer('read_counter');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
