<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classroom_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms');
            $table->foreignId('user_id')->constrained('users');
            $table->string('role', 50); // 'teacher', 'student', etc.
            $table->timestamp('joined_at');
            
            // Ensure a user can only be added once to a classroom
            $table->unique(['classroom_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_members');
    }
};