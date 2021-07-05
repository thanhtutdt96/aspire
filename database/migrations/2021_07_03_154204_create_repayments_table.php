<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->integer('user_id');
            $table->double('amount', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->integer('nth_payment')->nullable();
            $table->dateTime('paid_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('status');
            $table->text('note')->nullable();
            $table->boolean('enabled')->default(1);
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
        Schema::dropIfExists('repayments');
    }
}
