<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = ['name', 'short_name', 'city', 'is_active', 'display_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}