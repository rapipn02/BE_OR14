<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'user_verification_documents';

    protected $fillable = ['user_id', 'krs_path', 'payment_proof_path', 'verification_status', 'neo_path', 'marketing_path', 'rejection_reason', 'verified_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
