<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\LichMuaVu;
use App\Models\NhatKyDongRuong;
use App\Models\VatTuSuDung;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class VatTuSuDungService{
    protected $commonService;
    protected $hopTacXaService;
    protected $xaVienService;

    public function __construct(CommonService $commonService, HopTacXaService $hopTacXaService, XaVienService $xaVienService)
    {
        $this->commonService = $commonService;
        $this->hopTacXaService = $hopTacXaService;
        $this->xaVienService = $xaVienService;
    }


    public function autoCompleteVatTuSuDung($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $search = $request->search;
        $vattu = LichMuaVu::where('tbl_lichmuavu.id_hoptacxa', $id_hoptacxa)
            ->join('tbl_giaodichmuaban_vattu', 'tbl_giaodichmuaban_vattu.id_lichmuavu', 'tbl_lichmuavu.id_lichmuavu')
            ->where('tbl_giaodichmuaban_vattu.status','1')
            ->where('tbl_giaodichmuaban_vattu.id_xavien',$id_xavien)
            ->join('tbl_category_vattu', 'tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')
            ->whereIn('tbl_lichmuavu.status', ['upcoming', 'start'])
            ->where('tbl_category_vattu.name_category_vattu', 'LIKE', "%$search%")
            ->select(
                "tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu",
                "tbl_category_vattu.name_category_vattu",
                "tbl_giaodichmuaban_vattu.soluong",
            )
            ->limit(15)
            ->get();

        return $vattu;
    }
    
    public function createVatTuSuDung(
        $id_nhatkydongruong,
        $id_giaodichmuabanvattu,
        $soluong,
        $timeuse
    ){
        try {

            if($soluong <= 0 ){
                Session::flash('error', 'S??? l?????ng v???t t?? s??? d???ng ph???i l???n h??n 0');
                return false;
            }

            $nhatkydongruong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)->first();
            if($timeuse < $nhatkydongruong->date_start){
                Session::flash('error', 'Th???i gian s??? d???ng ph???i l???n h??n ng??y b???t ?????u ho???t ?????ng');
                return false;
            }

            if($timeuse > $nhatkydongruong->date_end){
                Session::flash('error', 'Th???i gian s??? d???ng ph???i nh??? h??n ng??y k???t th??c ho???t ?????ng');
                return false;
            }

            DB::beginTransaction();
            $vatTuSuDung = VatTuSuDung::create([
                "id_nhatkydongruong" =>$id_nhatkydongruong,
                "id_giaodichmuaban_vattu"=>$id_giaodichmuabanvattu,
                "soluong"=>$soluong,
                "timeuse"=>$timeuse
            ]);
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng th??m ???????c v???t t?? v??o ho???t ?????ng');
            return false;
        }
    }

    public function updateVatTuSuDung(
        $id_vattusudung,
        $id_nhatkydongruong,
        $id_giaodichmuabanvattu,
        $soluong,
        $timeuse
    ){
        try {
            
            if($soluong <= 0 ){
                Session::flash('error', 'S??? l?????ng v???t t?? s??? d???ng ph???i l???n h??n 0');
                return false;
            }

            $nhatkydongruong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)->first();
            if($timeuse < $nhatkydongruong->date_start){
                Session::flash('error', 'Th???i gian s??? d???ng ph???i l???n h??n ng??y b???t ?????u ho???t ?????ng');
                return false;
            }
            if($timeuse > $nhatkydongruong->date_end){
                Session::flash('error', 'Th???i gian s??? d???ng ph???i nh??? h??n ng??y k???t th??c ho???t ?????ng');
                return false;
            }
            DB::beginTransaction();
            $vatTuSuDung = VatTuSuDung::where('id_vattusudung', $id_vattusudung)->first();
            if($vatTuSuDung ==null){
                Session::flash('error', 'Kh??ng c???p nh???t v???t t?? v??o ho???t ?????ng');
                return false;
            }
            $vatTuSuDung->id_giaodichmuaban_vattu= $id_giaodichmuabanvattu;
            $vatTuSuDung->soluong= $soluong;
            $vatTuSuDung->timeuse = $timeuse;
            $vatTuSuDung->save();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng c???p nh???t v???t t?? v??o ho???t ?????ng');
            return false;
        }
    }

    public function deleteVatTuSuDung(
        $id_vattusudung
    ){
        try {
            DB::beginTransaction();
            $vatTuSuDung = VatTuSuDung::where('id_vattusudung', $id_vattusudung)->first();
            if($vatTuSuDung ==null){
                Session::flash('error', 'Kh??ng x??a v???t t?? kh???i ho???t ?????ng');
                return false;
            }
           
            $vatTuSuDung->delete();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng x??a v???t t?? kh???i ho???t ?????ng');
            return false;
        }
    }

}