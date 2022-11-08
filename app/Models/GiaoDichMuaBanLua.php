<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoDichMuaBanLua extends Model
{
    use HasFactory;
    protected $table = 'tbl_giaodichmuaban_lua';
    protected $primaryKey = 'id_giaodichmuaban_lua';
    protected $fillable = [
        'id_thuonglai',
        'id_xavien',
        'id_lichmuvu',
        'status_giaodich',
        'hoptacxa_xacnhan',
        'thuonglai_xacnhan',
        'xavien_xacnhan',
        'description_giaodich',
        'name_lohang',
        'price_lohang',
        'soluong',
        'img_lohang',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
