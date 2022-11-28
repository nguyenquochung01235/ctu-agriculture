<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'tbl_post';
    protected $primaryKey = 'id_post';
    protected $fillable = [
        'title_post',
        'short_description',
        'description',
        'image',
        'content',
        'id_user',
        'view',
        'type',
        'status'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('title_post','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }

}
