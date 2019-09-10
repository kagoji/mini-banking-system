<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankingTransaction extends Model
{
    use SoftDeletes;
    protected $table = 'banking_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_account_id',
        'transaction_user_id',
        'transaction_date',
        'transaction_type', // deposit,withdrawl,
        'opening_credit_amount',
        'closing_credit_amount',
        'opening_debit_amount',
        'closing_debit_amount',
        'opening_balance_amount',
        'closing_balance_amount',
        'transaction_method', // credit/debit
        'transaction_amount',
        'referrence_id',
        'transaction_narration'
    ];


}
