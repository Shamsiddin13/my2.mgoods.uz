<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    // Define which fields can be mass-assigned
    protected $fillable = [
        'amount',
        'description',
        'date',
        'contractor_name',
        'contractor_balance',
        'type',
        'currency',
        'transaction_id',
        'transaction_date',
        'transaction_category_name',
    ];

}
