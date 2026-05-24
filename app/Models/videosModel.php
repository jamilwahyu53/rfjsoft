<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videosModel extends Model
{
    use HasFactory;
    protected $table = 'videos';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $primaryKey = "video_id";
    protected $fillable = [
        "video_id",
        "url",
        "grade",
        "created_at",
        "created_by",
        "updated_by",
        "updated_date"
    ];

    protected $casts = [
        'video_id' => 'string',
        'url' => 'string',
        'grade' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
    ];
}
