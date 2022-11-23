<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoDichMuaBanVatTu extends Model
{
    use HasFactory;
    protected $table = 'tbl_giaodichmuaban_vattu';
    protected $primaryKey = 'id_giaodichmuaban_vattu';
    protected $fillable = [
        'id_hoptacxa',
        'id_xavien',
        'id_nhacungcapvattu',
        'id_lichmuavu',
        'id_category_vattu',
        'soluong',
        'price',
        'img_lohang',
        'description_giaodich',
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
