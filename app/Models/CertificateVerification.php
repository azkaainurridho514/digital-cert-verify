<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CertificateVerification extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false; 
    protected $fillable = [
        'certificate_id',
        'verified_at',
        'ip_address',
        'device_info',
        'result',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
