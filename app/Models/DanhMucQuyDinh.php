<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMucQuyDinh extends Model
{
    use HasFactory;
    protected $table = 'tbl_danhmucquydinh';
    protected $primaryKey = 'id_danhmucquydinh';
    protected $fillable = [
        'id_thuonglai',
        'name_danhmucquydinh',
        'active'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('name_danhmucquydinh','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }



}
