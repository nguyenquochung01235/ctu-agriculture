<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaCungCapVatTu extends Model
{
    use HasFactory;
    protected $table = 'tbl_nhacungcapvattu';
    protected $primaryKey = 'id_nhacungcapvattu';
    protected $fillable = [
        'id_user',
        'name_daily',
        'thubnail',
        'img_background',
        'description',
        'active'
        ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'id_user');   
    }
}
