<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->binary('file');
            $table->text('link');
            $table->binary('img');
            $table->foreignId('type')->constrained('options');
            $table->text('gmap');
            $table->string('province');
            $table->string('city');
            $table->text('address');
            $table->text('website');
            $table->text('instagram');
            $table->text('facebook');
            $table->text('x');
            $table->integer('read_counter');
            $table->integer('download_counter');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
