<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran';
    public $timestamps = false;
    protected $primaryKey = "code";
    public $incrementing = false;

    protected $fillable = [
        'code',
        'code_visitor',
        'bukti_bayar',
        'st_valid',
        'create_by',
        'approve_by',
        'create_date',
        'approve_date'
    ];
}
