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
        'id_hoptacxa',
        'id_lichmuavu',
        'status',
        'hoptacxa_xacnhan',
        'thuonglai_xacnhan',
        'xavien_xacnhan',
        'description_giaodich',
        'name_lohang',
        'price',
        'soluong',
        'img_lohang',
        'reason',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeWho($query, $who, $id)
    {
       
        $query->where($who, $id);
        
        return $query;
    }

    public function scopeLichMuaVu($query, $request)
    {
        if ($request->has('lichmuavu')) {
            if($request->lichmuavu != ''){
                $query->where('tbl_lichmuavu.id_lichmuavu',$request->lichmuavu);
            }
        }
        return $query;
    }
    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            if($request->search == "" ||$request->search == null){
                return $query;
            }
            $query->where('tbl_giaodichmuaban_lua.id_giaodichmuaban_lua', $request->search);
        }
        return $query;
    }


}
