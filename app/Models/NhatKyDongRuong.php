<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhatKyDongRuong extends Model
{
    use HasFactory;


    protected $table = 'tbl_nhatkydongruong';
    protected $primaryKey = 'id_nhatkydongruong';
    protected $fillable = [
        'id_xavien',
        'id_lichmuavu',
        'id_thuadat',
        'id_hoatdongmuavu',
        'name_hoatdong',
        'description',
        'date_start',
        'date_end',
        'type',
        'status',
        'hoptacxa_xacnhan',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeXaVien($query, $request)
    {
        if ($request->has('id_xavien')) {
            $query->where('id_xavien', $request->id_xavien);
        }
        return $query;
    }
    public function scopeLichMuaVu($query, $request)
    {
        if ($request->has('id_lichmuavu')) {
            $query->where('id_lichmuavu', $request->id_lichmuavu);
        }
        return $query;
    }
    public function scopeHoatDongMuaVu($query, $request)
    {
        if ($request->has('id_hoatdongmuavu')) {
            $query->where('id_hoatdongmuavu', $request->id_hoatdongmuavu);
        }
        return $query;
    }
    public function scopeNameHoatDongMuaVu($query, $request)
    {
        if ($request->has('name_hoatdong')) {
            $query->where('name_hoatdong', 'LIKE', '%' . $request->name_hoatdong . '%');
        }
        return $query;
    }
    public function scopeDateStart($query, $request)
    {
        if ($request->has('date_start')) {
            $query->whereDate('date_start',$request->date_start);
        }
        return $query;
    }
    public function scopeDateEnd($query, $request)
    {
        if ($request->has('date_end')) {
            $query->whereDate('date_end',$request->date_end);
        }
        return $query;
    }
    public function scopeStatus($query, $request)
    {
        if ($request->has('status')) {
            $query->where('status',$request->status);
        }
        return $query;
    }

    public function scopeType($query, $request)
    {
        if ($request->has('type')) {
            $query->where('type',$request->type);
        }
        return $query;
    }

    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('name_hoatdong','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }
}
