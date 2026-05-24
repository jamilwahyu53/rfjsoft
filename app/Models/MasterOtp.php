<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterOtp extends Model
{
    use HasFactory;
    protected $table = 'masterotp';
    //public $timestamps = false;
    //protected $primaryKey = "Kode";
    //public $incrementing = false;

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'used',
    ];
    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
