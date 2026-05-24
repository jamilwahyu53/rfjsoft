<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemenagVisitorModel extends Model
{
    use HasFactory;
    protected $table = 'kemenag_visitor';
    //public $timestamps = false;
    protected $primaryKey = "code";
    public $incrementing = false;

    protected $fillable = [
        'code',
        'ticket',
        'full_name',
        'nip',
        'npwp',
        'place_of_birth',
        'birth_date',
        'gender',
        'organization',
        'satker',
        'grade',
        'role',
        'email',
        'address',
        'office_address',
        'phone',
        'password',
        'bank_name',
        'bank_number',
        'bank_account_name',
        'paper_work',
        'foto',
        'create_date',
        'tgl_entry',
        'size_jersey'
    ];
    protected $hidden = ['password', 'foto', 'paper_work'];
}
