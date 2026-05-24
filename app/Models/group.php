<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    use HasFactory;
    protected $table = 'groups';
    public $timestamps = false;
    protected $primaryKey = "code_gate";
    public $incrementing = false;

    protected $fillable = [
        'code_gate',
        'code_visitor',
        'full_name',
        'organization',
        'satker'
    ];
}
