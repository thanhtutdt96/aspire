<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    public static $PAID = 'paid';
    public static $UNPAID = 'unpaid';

    protected $fillable = [
        'loan_id',
        'user_id',
        'amount',
        'payment_method',
        'nth_payment',
        'paid_date',
        'due_date',
        'status',
        'note',
        'enabled'
    ];

    protected $casts = [
        'paid_date' => 'datetime',
        'due_date' => 'datetime'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function loan() {
        return $this->belongsTo(Loan::class);
    }
}
