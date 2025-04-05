<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_token',
        'email_verified_at',
        'role',
        'has_taken_exam'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function updateExamStatus()
    {
        $hasTakenExam = $this->exams()
            ->whereIn('status', ['completed', 'expired'])
            ->exists();

        $this->update(['has_taken_exam' => $hasTakenExam]);
    }

    public function verification()
    {
        return $this->hasOne(UserVerifikasi::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
