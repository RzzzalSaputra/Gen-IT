<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classroom_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('classroom_assignments');
            $table->foreignId('user_id')->constrained('users');
            $table->text('content');
            $table->string('file');
            $table->timestamp('submitted_at');
            $table->boolean('graded')->default(false);
            $table->integer('grade')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_submissions');
    }
};