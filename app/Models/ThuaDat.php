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
}
