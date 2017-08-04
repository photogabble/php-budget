<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table){
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('category_id');
            $table->timestamps();
            $table->integer('date'); // date is stored as unix timestamp
            $table->string('transaction_type');
            $table->text('description');
            $table->integer('paid_out');
            $table->integer('paid_in');
            $table->integer('balance');
            $table->text('notes')->default('');
            $table->char('sha1', 40);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
