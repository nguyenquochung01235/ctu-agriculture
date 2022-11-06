<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification';
    protected $primaryKey = 'id';
    protected $fillable = [
        'message',
        'status',
        'user',
        'link',
        ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
