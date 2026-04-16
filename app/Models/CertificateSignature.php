<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CertificateSignature extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false; 
    protected $fillable = [
        'certificate_id',
        'public_key',
        'signatures',
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
