<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['university_id', 'name', 'type', 'language', 'is_active', 'display_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function university() { return $this->belongsTo(University::class); }
    public function semesters()  { return $this->hasMany(Semester::class); }
}