<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUndanganModel extends Model
{
    use HasFactory;
    protected $table = 'master_undangan';
    public $timestamps = false;
    protected $primaryKey = "code";
    public $incrementing = false;

    protected $fillable = [
        'code',
        'unit_kerja',
        'satker',
        'jml',
        'st_bank',
        'st_hotel',
    ];

}
