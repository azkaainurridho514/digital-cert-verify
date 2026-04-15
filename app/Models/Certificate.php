<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'certificate_number',
        'program_id',
        'description',
        'grade',
        'level',
        'issued_date',
        'file_path',
        'status',
        'created_by',
    ];

    // Relasi ke User (Many to One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Program (Many to One)
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Relasi ke Verification (One to One)
    public function verification()
    {
        return $this->hasOne(CertificateVerification::class);
    }

    // Relasi ke Signature (One to One)
    public function signature()
    {
        return $this->hasOne(CertificateSignature::class);
    }
}