<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('img')->nullable();
            $table->foreignId('type')->constrained('options');
            $table->text('gmap')->nullable();
            $table->string('province');
            $table->string('city');
            $table->text('address');
            $table->text('website')->nullable();
            $table->text('instagram')->nullable();
            $table->text('facebook')->nullable();
            $table->text('x')->nullable();
            $table->integer('read_counter')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}