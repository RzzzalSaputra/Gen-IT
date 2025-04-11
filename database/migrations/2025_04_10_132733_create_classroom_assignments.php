<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classroom_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms');
            $table->string('title', 255);
            $table->text('description');
            $table->timestamp('due_date');
            $table->string('file')->nullable();
            $table->foreignId('create_by')->constrained('users');
            $table->timestamp('create_at');
            $table->timestamp('update_at');
            $table->timestamp('delete_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_assignments');
    }
};