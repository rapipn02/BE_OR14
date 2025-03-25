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

    protected $appends = ['photo_url'];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get photo URL attribute with full app URL
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }

        // Generate absolute URL with domain
        return url(Storage::url($this->photo));
    }

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
