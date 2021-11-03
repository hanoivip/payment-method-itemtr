<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemtrTransactions extends Migration
{
    public function up()
    {
        Schema::create('itemtr_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trans');
            $table->string('key');
            $table->string('secret');
            $table->float('amount')->default(0);
            $table->float('net_amount')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('itemtr_transactions');
    }
}
