<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PdfFile extends Model
{
    protected $fillable = [
        'subject_id', 'semester_id', 'title', 'type', 'file_path',
        'file_size', 'is_free', 'language', 'display_order', 'is_active'
    ];
    protected $casts = ['is_free' => 'boolean', 'is_active' => 'boolean'];

    public function subject() { return $this->belongsTo(Subject::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
}