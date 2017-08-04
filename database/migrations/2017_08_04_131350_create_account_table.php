<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            // If you try to add a transaction before the last transaction date then Account will need to rebuild the
            // balance for all transactions as well as the account current_balance because they are all linked.
            $table->date('last_transaction')->nullable()->default(null);
            $table->string('name');
            $table->integer('starting_balance')->default(0);
            $table->integer('current_balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
