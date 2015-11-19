<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->string('title');
            $table->text('description');
            $table->text('apply');
            $table->enum('type', ['full-time', 'part-time', 'temporal', 'freelance']);

            $table->string('city')
                  ->nullable();
            $table->string('country')
                  ->nullable();
            $table->boolean('remote')
                  ->default(false);

            $table->boolean('listing')
                  ->default(false);

            $table->integer('recruiter_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
