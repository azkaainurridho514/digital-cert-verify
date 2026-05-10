<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false; 
    public $timestamps = false;
    protected $fillable = [
        'ids',
        'username',
        'certificate_number',
        'program_name',
        'grade',
        'publication_date',
        'level',
        'file_path',
        'digital_signature',
        'status',
        'description',
        'created_at',
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

    // Relasi ke Verification (One to many)
    public function verification()
    {
        return $this->hasMany(CertificateVerification::class);
    }

}