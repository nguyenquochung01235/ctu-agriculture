<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiongLua extends Model
{
    use HasFactory;
    protected $table = 'tbl_gionglua';
    protected $primaryKey = 'id_gionglua';
    protected $fillable = [
        'name_gionglua',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


}
