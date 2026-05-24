<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dataAbsen extends Model
{
    use HasFactory;
    protected $table = 'dataabsen';
    //public $timestamps = false;
    protected $primaryKey = "Kode";
    public $incrementing = false;

    protected $fillable = [
        'Kode',
        'code_staff',
        'code_qr',
        'code_post',
        'created_at',
        'updated_at',
    ];
}
