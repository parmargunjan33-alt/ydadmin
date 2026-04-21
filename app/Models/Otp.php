<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['mobile', 'email', 'otp', 'purpose', 'is_used', 'expires_at'];
    protected $casts = [
        'is_used'    => 'boolean',
        'expires_at' => 'datetime',
    ];
}