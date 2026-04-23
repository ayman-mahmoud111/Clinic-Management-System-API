<?php

namespace App\Models;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Role extends Model
{
    use HasFactory;
    public function user(){
        return $this->hasMany(User::class);
    }
}
