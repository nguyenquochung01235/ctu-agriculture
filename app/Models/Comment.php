<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'tbl_comment';
    protected $primaryKey = 'id_comment';
    protected $fillable = [
        'id_post',
        'id_user',
        'parent_id',    
        'image',
        'content',
        'type',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_comment.id_user')
            ->with('replies')
            ->select(
                'tbl_comment.*',
                'tbl_user.avatar',
                'tbl_user.fullname',
            )
            ;
    }
}
