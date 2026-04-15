<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'certificate_id',
        'verified_at',
        'ip_address',
        'device_info',
        'result',
    ];
    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
