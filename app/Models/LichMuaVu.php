<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichMuaVu extends Model
{
    use HasFactory;
    protected $table = 'tbl_lichmuavu';
    protected $primaryKey = 'id_lichmuavu';
    protected $fillable = [
        'id_hoptacxa',
        'id_gionglua',
        'name_lichmuavu',
        'date_start',
        'date_end',
        'status',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function xavien(){
        return $this->hasMany('App\Models\XaVien', 'id_hoptacxa');
    }

    public function gionglua(){
        return $this->hasOne('App\Models\GiongLua', 'id_gionglua', 'id_gionglua');
    }

    public function scopeLichMuaVu($query, $request)
    {
        if ($request->has('id_lichmuavu')) {
            $query->where('id_lichmuavu', $request->id_lichmuavu);
        }
        return $query;
    }

    public function scopeNameLichMuaVu($query, $request)
    {
        if ($request->has('name_lichmuavu')) {
            $query->where('name_lichmuavu', 'LIKE', '%' . $request->name_lichmuavu . '%');
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

    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('name_lichmuavu','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }

}
