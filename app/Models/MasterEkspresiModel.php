<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterEkspresiModel extends Model
{
    use HasFactory;
    protected $table = 'master_expression';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "expression",
        "stars",
        "min_value",
        "max_value",
        "solution",
        "active",
        "created_at",
        "created_by",
        "updated_by",
        "updated_date"
    ];

    protected $casts = [
        'id' => 'string',
        'expression' => 'string',
        'stars' => 'integer',
        'min_value' => 'integer',
        'max_value' => 'integer',
        'solution' => 'string',
        'active' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
    ];
}
