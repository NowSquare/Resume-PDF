<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
          
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('name', 250)->nullable();
            $table->string('value_string', 250)->nullable();
            $table->mediumText('value_text')->nullable();
            $table->integer('value_int')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->timestamp('value_date_time')->nullable();
            $table->date('value_date')->nullable();
            $table->time('value_time')->nullable();
            $table->mediumText('value_json')->nullable();
            $table->ipAddress('value_ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
