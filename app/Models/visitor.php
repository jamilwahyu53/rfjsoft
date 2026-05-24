<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class visitor extends Model
{
    use HasFactory;

    //nama, ttl, alamat rumah, hp, utusan, jabatan, alamat kantor, email. no rek, bank, an rek, ttd digital

    protected $table = 'visitor';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $primaryKey = "qr_code";
    protected $fillable = [
        "qr_code",
        "qr_ticket",
        "full_name",
        "place_of_birth",
        "birth_date",
        "address",
        "phone",
        "email",
        "gender",
        "mesengger",
        "status_mesengger",
        "status_mesengger_other",
        "position",
        "office_address",
        "account_number",
        "bank_name",
        "account_bank_name",
        "signature",
        "size_jersey",
    ];
}
