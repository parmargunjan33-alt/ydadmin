<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'mobile', 'password',
        'device_id', 'device_name',
        'mobile_verified', 'mobile_verified_at',
        'university_id', 'course_id', 'semester_id',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'mobile_verified'    => 'boolean',
        'mobile_verified_at' => 'datetime',
        'is_active'          => 'boolean',
    ];

    public function university() {
        return $this->belongsTo(University::class);
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function semester() {
        return $this->belongsTo(Semester::class);
    }
    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }
}
