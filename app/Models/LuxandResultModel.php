<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuxandResultModel extends Model
{
    use HasFactory;

    protected $table = 'luxand_results';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'user_id',
        'stage',
        'mode',
        'value',
        'created_at',
    ];

    protected $casts = [
        'uuid' => 'string',
        'user_id' => 'string',
        'stage' => 'integer',
        'mode' => 'string',
        'value' => 'float',
        'created_at' => 'datetime',
    ];
}
