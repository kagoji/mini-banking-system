<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::defaultStringLength(191);
        Schema::create('banking_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_account_id')->index();
            $table->bigInteger('transaction_user_id')->unsigned();
            $table->date('transaction_date')->nullable();
            $table->string('transaction_type')->nullable();
            $table->float('opening_credit_amount')->nullable()->default(0);
            $table->float('closing_credit_amount')->nullable()->default(0);
            $table->float('opening_debit_amount')->nullable()->default(0);
            $table->float('closing_debit_amount')->nullable()->default(0);
            $table->float('opening_balance_amount')->nullable()->default(0);
            $table->float('closing_balance_amount')->nullable()->default(0);
            $table->string('transaction_method')->nullable();
            $table->float('transaction_amount')->nullable();
            $table->string('referrence_id')->nullable();
            $table->string('transaction_narration')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banking_transactions');
    }
}
