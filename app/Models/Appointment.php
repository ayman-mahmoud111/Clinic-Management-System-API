<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable=[
        'patient_id',
        'doctor_id',
        'start_time',
        'end_time',
        'status'
    ];

    public function patient(){
        return $this->belongsTo(patient::class);
    }

    public function doctor(){
        return $this->belongsTo(user::class,'doctor_id');
    }

        
    }

