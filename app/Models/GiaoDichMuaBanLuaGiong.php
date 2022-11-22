<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoDichMuaBanLuaGiong extends Model
{
    use HasFactory;
    protected $table = 'tbl_giaodich_luagiong';
    protected $primaryKey = 'id_giaodich_luagiong';
    protected $fillable = [
        'id_hoptacxa',
        'id_xavien',
        'id_nhacungcapvattu',
        'id_lichmuavu',
        'id_gionglua',
        'img_lohang',
        'soluong',
        'status',
        'hoptacxa_xacnhan',
        'nhacungcap_xacnhan',
        'xavien_xacnhan',
        'description_giaodich',
        'reason'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeWho($query, $who, $id)
    {
       
        $query->where($who, $id);
        
        return $query;
    }
}
