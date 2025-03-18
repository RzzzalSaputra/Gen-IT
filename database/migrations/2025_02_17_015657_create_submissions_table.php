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
            $table->text('content')->nullable();
            $table->string('file')->nullable();
            $table->text('link')->nullable();
            $table->string('img')->nullable();
            $table->foreignId('type')->constrained('options');
            $table->foreignId('status')->constrained('options');
            $table->integer('read_counter')->default(0);
            $table->integer('download_counter')->default(0);
            $table->date('approve_at')->nullable();
            $table->foreignId('approve_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};