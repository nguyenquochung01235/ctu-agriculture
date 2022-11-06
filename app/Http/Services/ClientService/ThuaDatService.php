<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\GiongLua;
use App\Models\ThuaDat;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ThuaDatService{

    protected $xaVienService;
    protected $commonService;

    public function __construct(XaVienService $xaVienService, CommonService $commonService)
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
    }

    public function getListThuaDatOfXaVien($request){
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        if($request->id_xavien != null){
            $id_xavien = $request->id_xavien;
        }
        try {
            $thuadat = ThuaDat::where('id_xavien', $id_xavien)->get();
            if($thuadat == []){
                Session::flash('error', 'Bạn chưa có thửa đất nào');
                return false;
            }
            return $thuadat;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin thửa đất');
            return false;
        }
    }

    public function createThuaDat($request){
        $id_user = $this->commonService->getIDByToken();
        $xavien = XaVien::where('id_user', $id_user)->first('id_xavien');

        try {
            DB::beginTransaction();
            $thuadat = ThuaDat::create([
                "id_xavien" => $xavien->id_xavien,
                "address" => $request->address,
                "location"=>$request->location,
                "thumbnail" => $request->thumbnail,
                "description" => $request->description,
                "active" => 1,
            ]);
            DB::commit();
            return $thuadat;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
    }
    

}