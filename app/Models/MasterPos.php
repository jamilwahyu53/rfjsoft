<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPos extends Model
{
    use HasFactory;
    protected $table = 'masterpos';
    //public $timestamps = false;
    protected $primaryKey = "Kode";
    public $incrementing = false;

    protected $fillable = [
        'Kode',
        'pos_name',
        'information',
        'created_at',
        'updated_at',
    ];
}
