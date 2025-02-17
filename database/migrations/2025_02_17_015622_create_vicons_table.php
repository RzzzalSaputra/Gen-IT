<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViconsTable extends Migration
{
    public function up()
    {
        Schema::create('vicons', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('255');
            $table->text('desc');
            $table->binary('img');
            $table->timestamp('time');
            $table->text('link');
            $table->text('download');
            $table->foreignId('create_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vicons');
    }
};