<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->timestamp('last_updated')->nullable();
            $table->string('cache_id')->nullable()->default(null);
            $table->text('class_name');
            $table->string('name');
            $table->longText('configuration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
