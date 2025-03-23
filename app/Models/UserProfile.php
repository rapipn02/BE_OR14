<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama_lengkap', 'panggilan', 'nim', 
        'whatsapp', 'program_studi', 'divisi', 'sub_divisi', 'twibbon'
    ];

    /**
     * Relasi ke tabel users (Setiap user hanya punya satu profil).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute()
{
    return $this->photo ? Storage::url($this->photo) : null;
}
}

