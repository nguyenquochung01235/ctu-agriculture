<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleXaVien extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'tbl_rolexavien';
    protected $primaryKey = 'id_role';
    protected $fillable = [
        'role',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function xavien(){
        return $this->belongsToMany('App\Models\XaVien','xavien_rolexavien');
    }
}
