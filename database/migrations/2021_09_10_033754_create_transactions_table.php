<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('courier')->nullable();
            $table->string('payment')->default('MIDTRANS');
            $table->string('payment_url')->nullable();
            $table->string('status')->default('pending');
            $table->string('total_price')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}