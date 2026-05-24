<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterSidebar extends Model
{
    use HasFactory;
    
    protected $table = 'mastersidebar';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $primaryKey = "qr_code";
    protected $fillable = [
        "Kode",
        "Menu",
        "Link",
        "Icon",
        "Staff",
        "IsActive",
        "Company",
    ];
}
