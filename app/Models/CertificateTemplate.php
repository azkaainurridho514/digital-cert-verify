<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',

        'x_position_name',
        'y_position_name',
        'width_position_name',
        'height_position_name',

        'x_position_cert_number',
        'y_position_cert_number',
        'width_cert_number',
        'height_cert_number',

        'x_position_grade',
        'y_position_grade',
        'width_grade',
        'height_grade',

        'x_position_program_name',
        'y_position_program_name',
        'width_program_name',
        'height_program_name',

        'x_position_publish_date',
        'y_position_publish_date',
        'width_publish_date',
        'height_publish_date',

        'x_position_qr_code',
        'y_position_qr_code',
        'width_qr_code',
        'height_qr_code',

        'width_template',
        'height_template',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}