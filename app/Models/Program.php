<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
    ];
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
