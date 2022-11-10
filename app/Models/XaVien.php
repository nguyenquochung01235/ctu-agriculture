<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XaVien extends Model
{
    use HasFactory;
    protected $table = 'tbl_xavien';
    protected $primaryKey = 'id_xavien';
    protected $fillable = [
        'id_user',
        'id_hoptacxa',
        'active',
        'thubnail',
        'img_background',
        'description'
        ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'wallet',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User', 'id_user');   
    }

    public function hop_tac_xa(){
        return $this->belongsTo('App\Models\HopTacXa', 'id_hoptacxa');   
    }

    public function role(){
        return $this->belongsToMany('App\Models\RoleXaVien', 'xavien_rolexavien','xavien_id_xavien','rolexavien_id_role');
    }

    public function thuadat(){
        return $this->hasMany('App\Models\ThuaDat', 'id_xavien');
    }

    public function scopeXaVien($query, $request)
    {
        if ($request->has('id_xavien')) {
            $query->where('id_xavien', $request->id_xavien);
        }
        return $query;
    }
    public function scopeUser($query, $request)
    {
        if ($request->has('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        return $query;
    }

    public function scopeSearch($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($query_child) use ($request){ 
                $query_child
                    ->orWhere('fullname','LIKE', '%' . $request->search . '%')
                    ->orWhere('phone_number','LIKE', '%' . $request->search . '%')
                    ->orWhere('email','LIKE', '%' . $request->search . '%');
            });
        }
        return $query;
    }
    
    
    

}
