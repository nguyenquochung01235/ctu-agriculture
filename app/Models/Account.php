<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'tbl_account';
    protected $primaryKey = 'id_account';
    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function user(){
        return $this->belongsToMany('App\Models\User');
        
    }

}
