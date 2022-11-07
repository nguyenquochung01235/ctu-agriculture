<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThuaDat extends Model
{
    use HasFactory;
    protected $table = 'tbl_thuadat';
    protected $primaryKey = 'id_thuadat';
    protected $fillable = [
        'id_xavien',
        'address',
        'location',
        'thumbnail',
        'description',
        'active',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function xavien(){
        return $this->belongsTo('App\Models\XaVien', 'id_hoptacxa');
    }

    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('tbl_user.fullname','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }
}
