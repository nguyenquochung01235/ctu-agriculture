<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'tbl_user';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'address',
        'password',
        'remember_token',
        'wallet',
        'dob',
        'avatar',
        'active'
        ];

    protected $hidden = [
        'password',
        'remember_token',
        'wallet',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    

    public function account(){
        return $this->belongsToMany('App\Models\Account' ,'user_account');
    }

    public function xavien(){
        return $this->hasOne('App\Models\XaVien','id_user');
    }

}
