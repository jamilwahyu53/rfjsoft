<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmoLand extends Model
{
    use HasFactory;
    protected $table = 'user_emo_land';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $primaryKey = "user_id";
    protected $fillable = [
        "user_id",
        "password",
        "full_name",
    ];

    protected $casts = [
        'user_id' => 'string',
        'password' => 'string',
        'full_name' => 'string',
    ];

    protected $hidden = [
        "password"
    ];
}
