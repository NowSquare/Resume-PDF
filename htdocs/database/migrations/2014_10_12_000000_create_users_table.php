<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->char('uuid', 36)->nullable();
            $table->string('slug', 250)->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('premium')->default(false);
            $table->tinyInteger('role')->unsigned()->default(3);
            $table->string('name', 164);
            $table->text('bio')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('language', 5)->nullable();
            $table->string('locale', 24)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('timezone', 32)->nullable();
            $table->integer('logins')->default(0)->unsigned();
            $table->dateTime('last_login')->nullable();
            $table->ipAddress('last_ip_address')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->tinyInteger('emails_sent')->unsigned()->default(0);
            $table->mediumText('about')->nullable();
            $table->string('salutation', 32)->nullable();
            $table->string('first_name', 64)->nullable();
            $table->string('last_name', 64)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->text('job_title')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact_email', 128)->nullable();
            $table->string('contact_phone', 64)->nullable();
            $table->string('address1', 250)->nullable();
            $table->string('address2', 250)->nullable();
            $table->string('address3', 250)->nullable();
            $table->string('linkedin', 250)->nullable();
            $table->text('languages')->nullable();
            $table->string('website', 250)->nullable();
            $table->mediumText('meta')->nullable();
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->unsigned();
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
        Schema::dropIfExists('users');
    }
}
