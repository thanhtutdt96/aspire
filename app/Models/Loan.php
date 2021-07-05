<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public static $INIT = 'init';
    public static $APPROVED = 'approved';
    public static $FULL_PAID = 'full_paid';
    public static $CANCELED = 'cancelled';

    protected $fillable = [
        'user_id',
        'package_id',
        'total_amount',
        'base_amount',
        'paid_amount',
        'start_date',
        'end_date',
        'purpose',
        'status',
        'enabled'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function repayments() {
        return $this->hasMany(Repayment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function package() {
        return $this->belongsTo(Package::class);
    }
}
