<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatTuSuDung extends Model
{
    use HasFactory;
    protected $table = 'tbl_vattusudung';
    protected $primaryKey = 'id_vattusudung';
    protected $fillable = [
        'id_nhatkydongruong',
        'id_giaodichmuaban_vattu',
        'soluong',
        'timeuse'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
