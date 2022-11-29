<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGiaCuoiMua extends Model
{
    use HasFactory;
    protected $table = 'tbl_danhgiacuoimua';
    protected $primaryKey = 'id_danhgiacuoimua';
    protected $fillable = [
        'id_lichmuavu',
        'id_xavien',
        'giong',
        'phanbon',
        'xangdau',
        'vattukhac',
        'lamdat',
        'gieosa',
        'lamco',
        'bomtuoi',
        'thuhoach',
        'rahat',
        'phoisay',
        'vanchuyen',
        'thuyloiphi',
        'tongsanluong',
        'giaban',
        'kiennghi'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeLichMuaVu($query, $request)
    {
        if ($request->has('id_lichmuavu')) {
            $query->where('tbl_lichmuavu.id_lichmuavu', $request->id_lichmuavu);
        }
        return $query;
    }
}
