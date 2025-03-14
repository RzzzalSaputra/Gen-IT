create_jobs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->text('title');
            $table->text('description');
            $table->text('requirment');
            $table->integer('salary_range');
            $table->text('register_link');
            $table->foreignId('type')->constrained('options');
            $table->foreignId('experience')->constrained('options');
            $table->foreignId('work_type')->constrained('options'); // Added work_type field
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
        Schema::dropIfExists('jobs');
    }
}