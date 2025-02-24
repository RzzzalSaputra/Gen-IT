<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respond_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->text('message');
            $table->text('respond_message')->nullable();
            $table->foreignId('status')->constrained('options');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};