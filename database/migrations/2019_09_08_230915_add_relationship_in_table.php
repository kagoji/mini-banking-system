<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });

        Schema::table('banking_transactions', function (Blueprint $table) {
            $table->foreign('transaction_account_id')->references('account_id')->on('accounts')->onUpdate('cascade');
            $table->foreign('transaction_user_id')->references('id')->on('users')->onUpdate('cascade');
        });

        Schema::table('transfer_transactions', function (Blueprint $table) {
            $table->foreign('account_from_id')->references('account_id')->on('accounts')->onUpdate('cascade');
            $table->foreign('account_to_id')->references('account_id')->on('accounts')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
}
