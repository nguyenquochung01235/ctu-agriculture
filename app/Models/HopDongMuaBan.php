<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopDongMuaBan extends Model
{
    use HasFactory;
    protected $table = 'tbl_hopdongmuaban';
    protected $primaryKey = 'id_hopdongmuaban';
    protected $fillable = [
        'id_thuonglai',
        'id_hoptacxa',
        'id_lichmuavu',
        'id_danhmucquydinh',
        'id_gionglua',
        'title_hopdongmuaban',
        'price',
        'description_hopdongmuaban',
        'thuonglai_xacnhan',
        'hoptacxa_xacnhan',
        'status'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function htx(){
        return $this->belongsTo('App\Models\HopTacXa' ,'id_hoptacxa');
    }
    public function thuonglai(){
        return $this->belongsTo('App\Models\ThuongLai' ,'id_thuonglai');
    }


    public function scopeWho($query, $who, $id)
    {
       
        $query->where($who, $id);
        
        return $query;
    }

    public function scopeStatus($query, $request)
    {
        if ($request->has('status')) {
            $query->where('tbl_hopdongmuaban.status',$request->status);
        }
        return $query;
    }

    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('tbl_hopdongmuaban.title_hopdongmuaban','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }

}
