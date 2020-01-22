<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumeExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_experience', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->string('type', 32);
            $table->string('name', 250)->nullable();
            $table->string('location', 250)->nullable();
            $table->mediumText('description')->nullable();
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->unsigned();
            $table->timestamps();
        });

        // Many-to-many relation
        Schema::create('resume_experience_tag', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('resume_experience_id')->unsigned()->index();
            $table->foreign('resume_experience_id')->references('id')->on('resume_experience')->onDelete('cascade');
            $table->bigInteger('tag_id')->unsigned()->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_experience_tag');
        Schema::dropIfExists('resume_experience');
    }
}
