<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, SoftDelete;

    protected $fillable = [
    'name',
    'phone',
    'age',
    'address'
    ];

    public function appointments()
    {
    return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
    return $this->hasMany(MedicalRecord::class);
    }

}
