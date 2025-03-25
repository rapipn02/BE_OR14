<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['division_id', 'question_text'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
