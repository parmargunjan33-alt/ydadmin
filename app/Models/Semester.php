<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['course_id', 'number', 'label', 'is_active', 'end_date'];
    protected $casts = ['is_active' => 'boolean', 'end_date' => 'date'];

    public function course()   { return $this->belongsTo(Course::class); }
    public function subjects() { return $this->hasMany(Subject::class); }
}