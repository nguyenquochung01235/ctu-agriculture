<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopTacXa extends Model
{
    use HasFactory;
    protected $table = 'tbl_hoptacxa';
    protected $primaryKey = 'id_hoptacxa';
    protected $fillable = [
        'name_hoptacxa',
        'phone_number',
        'email',
        'address',
        'thumbnail',
        'img_background',
        'description',
        'active',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function xavien(){
        return $this->hasMany('App\Models\XaVien', 'id_hoptacxa');
    }
}
