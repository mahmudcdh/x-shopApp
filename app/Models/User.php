<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['firstName','lastName','userName','email','mobile','password','otp','userImage'];
    protected $attributes = [
        'otp' => '0'
    ];
}
