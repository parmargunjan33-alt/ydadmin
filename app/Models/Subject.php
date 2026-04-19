<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['semester_id', 'name', 'display_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function semester()  { return $this->belongsTo(Semester::class); }
    public function pdfFiles()  { return $this->hasMany(PdfFile::class); }
}