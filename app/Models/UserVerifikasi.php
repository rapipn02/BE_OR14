<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'user_verifikasis'; // Pastikan tabelnya sesuai

    protected $fillable = ['user_id', 'krs_path', 'payment_proof_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

