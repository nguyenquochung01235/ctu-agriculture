<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryVatTu extends Model
{
    use HasFactory;
    protected $table = 'tbl_category_vattu';
    protected $primaryKey = 'id_category_vattu';
    protected $fillable = [
        'id_danhmucquydinh',
        'name_category_vattu',
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
                    ->orWhere('name_category_vattu','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }
}
