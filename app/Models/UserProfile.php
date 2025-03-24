<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'panggilan',
        'nim',
        'whatsapp',
        'program_studi',
        'divisi',
        'sub_divisi',
        'departemen',
        'twibbon',
        'photo'
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get photo URL attribute
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    /**
     * Append additional attributes to JSON
     */
    protected $appends = ['photo_url'];

    /**
     * Check if profile is complete
     */
    public function isComplete()
    {
        $requiredFields = [
            'nama_lengkap',
            'panggilan',
            'nim',
            'whatsapp',
            'program_studi',
            'divisi',
            'sub_divisi'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }
}
