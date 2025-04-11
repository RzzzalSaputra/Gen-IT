<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 10)->unique();
            $table->text('description');
            $table->foreignId('create_by')->constrained('users');
            $table->timestamp('create_at');
            $table->timestamp('update_at');
            $table->timestamp('delete_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
};