<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThuongLai extends Model
{
    use HasFactory;
    protected $table = 'tbl_thuonglai';
    protected $primaryKey = 'id_thuonglai';
    protected $fillable = [
        'id_user',
        'name_thuonglai',
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
