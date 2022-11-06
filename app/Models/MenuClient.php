<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuClient extends Model
{
    use HasFactory;
    protected $table = 'tbl_menu_client';
    protected $primaryKey = 'id_menu';
    protected $fillable = [
        'title',
        'href',
        'parent_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
