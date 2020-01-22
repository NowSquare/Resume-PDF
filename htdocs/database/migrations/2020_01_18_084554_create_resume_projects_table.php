<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumeProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_projects', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->string('title', 250);
            $table->mediumText('description')->nullable();
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->unsigned();
            $table->timestamps();
        });

        // Many-to-many relation
        Schema::create('resume_project_tag', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('resume_project_id')->unsigned()->index();
            $table->foreign('resume_project_id')->references('id')->on('resume_projects')->onDelete('cascade');
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
        Schema::dropIfExists('resume_project_tag');
        Schema::dropIfExists('resume_projects');
    }
}
