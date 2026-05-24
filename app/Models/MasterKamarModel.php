<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKamarModel extends Model
{
    use HasFactory;
    protected $table = 'masterkamar';
    //public $timestamps = false;
    protected $primaryKey = "code";
    public $incrementing = false;

    protected $fillable = [
        'code',
        'harga',
        'active',
        'creat_by',
        'update_by',
        'created_at',
        'updated_at',
    ];
}
