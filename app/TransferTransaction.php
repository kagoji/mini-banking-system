<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferTransaction extends Model
{
    use SoftDeletes;
    protected $table = 'transfer_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_from_id',
        'account_to_id',
        'transfer_narration',
        'transfer_amount',
    ];
}
