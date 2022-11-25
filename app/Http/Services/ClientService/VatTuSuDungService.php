<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\NhatKyDongRuong;
use App\Models\VatTuSuDung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class VatTuSuDungService{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function createVatTuSuDung(
        $id_nhatkydongruong,
        $id_giaodichmuabanvattu,
        $soluong,
        $timeuser
    ){
        try {
            
            if($soluong <= 0 ){
                Session::flash('error', 'Số lượng vật tư sử dụng phải lớn hơn 0');
                return false;
            }

            $nhatkydongruong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)->first();
            if($timeuser < $nhatkydongruong->date_start){
                Session::flash('error', 'Thời gian sử dụng phải lớn hơn ngày bắt đầu hoạt động');
                return false;
            }
            if($timeuser > $nhatkydongruong->date_end){
                Session::flash('error', 'Thời gian sử dụng phải nhỏ hơn ngày kết thúc hoạt động');
                return false;
            }
            DB::beginTransaction();
            $vatTuSuDung = VatTuSuDung::create([
                "id_nhatkydongruong" =>$id_nhatkydongruong,
                "id_giaodichmuaban_vattu"=>$id_giaodichmuabanvattu,
                "soluong"=>$soluong,
                "timeuser"=>$timeuser
            ]);
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thêm được vật tư vào hoạt động');
            return false;
        }
    }

}