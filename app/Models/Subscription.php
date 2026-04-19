<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'semester_id', 'razorpay_order_id',
        'razorpay_payment_id', 'razorpay_signature',
        'amount', 'status', 'paid_at', 'expires_at',
    ];
    protected $casts = [
        'paid_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
}