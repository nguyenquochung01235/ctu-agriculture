<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\HoatDongMuaVu;
use App\Models\NhatKyDongRuong;
use App\Models\ThuaDat;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NhatKyDongRuongService{
    
    protected $commonService;
    protected $xaVienService;
    protected $hopTacXaService;
    protected $lichMuaVuService;
    protected $thuaDatService;

    public function __construct(CommonService $commonService, XaVienService $xaVienService, HopTacXaService $hopTacXaService ,LichMuaVuService $lichMuaVuService, ThuaDatService $thuaDatService)
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->lichMuaVuService = $lichMuaVuService;
        $this->thuaDatService = $thuaDatService;

    }


    public function getDetailNhatKyDongRuong($request){
        $id_nhatkydongruong = $request->id_nhatkydongruong;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        

        if(!$this->xaVienService->isXaVienBelongToHTX($this->commonService->getIDByToken(), $id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền xem chi tiết nhật ký hoạt động');
            return false;
        }
        
        try {
            $detailNhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
            ->join('tbl_xavien', 'tbl_xavien.id_xavien', 'tbl_nhatkydongruong.id_xavien')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', 'tbl_nhatkydongruong.id_lichmuavu')
            ->select(
                'tbl_nhatkydongruong.id_nhatkydongruong',
                'tbl_nhatkydongruong.id_thuadat',
                'tbl_nhatkydongruong.name_hoatdong',
                'tbl_nhatkydongruong.description',
                'tbl_nhatkydongruong.date_start',
                'tbl_nhatkydongruong.date_end',
                'tbl_nhatkydongruong.status',
                'tbl_nhatkydongruong.type',
                'tbl_nhatkydongruong.hoptacxa_xacnhan',
                'tbl_lichmuavu.name_lichmuavu',
                'tbl_lichmuavu.date_start as lichmuavu_date_start',
                'tbl_lichmuavu.date_end as lichmuavu_date_end',
                'tbl_user.fullname',
                
                )
            ->first();
            if($detailNhatKyDongRuong == null){
                Session::flash('error', 'Nhật ký hoạt động không tồn tại');
                return false; 
            }
            return $detailNhatKyDongRuong;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được nhật ký đồng ruộng');
            return false;
        }

        
    }

    // forXaVien
    public function getListNhatKyDongRuong($request){
        // @param
        $id_user = $this->commonService->getIDByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $id_lichmuavu = $request->id_lichmuavu;
        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;
        


        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
       
        if($page == null || $page == 0 || $page < 0){
            $page = 1;
        }
        if($limit == null || $limit == 0 || $limit < 0){
            $limit = 15;
        }
        if($search == null){
            $search = "";
        }
        if($order == null || $order == ""){
            $order = "date_start";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "asc";
        }
        
        if(!$this->xaVienService->isXaVienBelongToHTX($id_user,$id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xem danh sách nhật ký hoạt động mùa vụ');
            return false;
        }
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        try {
            $data = NhatKyDongRuong::where('id_xavien', $id_xavien)
                ->where('id_lichmuavu', $id_lichmuavu)
                ->select(
                    'tbl_nhatkydongruong.id_nhatkydongruong',
                    'tbl_nhatkydongruong.id_thuadat',
                    'tbl_nhatkydongruong.name_hoatdong',
                    'tbl_nhatkydongruong.description',
                    'tbl_nhatkydongruong.date_start',
                    'tbl_nhatkydongruong.date_end',
                    'tbl_nhatkydongruong.status',
                    'tbl_nhatkydongruong.type',
                    'tbl_nhatkydongruong.hoptacxa_xacnhan')
                ->HoatDongMuaVu($request)
                ->NameHoatDongMuaVu($request)
                ->DateStart($request)
                ->DateEnd($request)
                ->Status($request)
                ->Search($request);

            $total = $data->count();

            $meta = $this->commonService->pagination($total,$page,$limit);
            $result =  $data
                ->skip(($page-1)*$limit)
                ->take($limit)
                ->orderBy($order, $sort)
                ->get();

            if($result != []){
             return [$result, $meta];
            }
            Session::flash('error', 'Không có danh sách nhật ký hoạt động');
             return false;
         } catch (\Exception $error) {
             Session::flash('error', 'Không lấy được danh sách' . $error);
             return false;
         }
    }

    //All HTX
    public function getListNhatKyDongRuongForHTX($request){
        // @param
        $id_user = $this->commonService->getIDByToken();
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(! $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', "Bạn không có quyền quản trị để xem danh sách nhật ký hoạt động mùa vụ");
            return false;
        };

        $id_lichmuavu = $request->id_lichmuavu;
        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;
        


       
        if($page == null || $page == 0 || $page < 0){
            $page = 1;
        }
        if($limit == null || $limit == 0 || $limit < 0){
            $limit = 15;
        }
        if($search == null){
            $search = "";
        }
        if($order == null || $order == ""){
            $order = "date_start";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "asc";
        }
        
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        try {
            $data = NhatKyDongRuong::where('id_lichmuavu', $id_lichmuavu)
                ->join('tbl_xavien', 'tbl_xavien.id_xavien', 'tbl_nhatkydongruong.id_xavien')
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->select(
                    'tbl_nhatkydongruong.id_nhatkydongruong',
                    'tbl_nhatkydongruong.id_thuadat',
                    'tbl_nhatkydongruong.name_hoatdong',
                    'tbl_nhatkydongruong.description',
                    'tbl_nhatkydongruong.date_start',
                    'tbl_nhatkydongruong.date_end',
                    'tbl_nhatkydongruong.status',
                    'tbl_nhatkydongruong.type',
                    'tbl_nhatkydongruong.hoptacxa_xacnhan',
                    'tbl_user.fullname')
                ->HoatDongMuaVu($request)
                ->NameHoatDongMuaVu($request)
                ->DateStart($request)
                ->DateEnd($request)
                ->Status($request)
                ->Search($request);

            $total = $data->count();

            $meta = $this->commonService->pagination($total,$page,$limit);
            $result =  $data
                ->skip(($page-1)*$limit)
                ->take($limit)
                ->orderBy($order, $sort)
                ->get();

            if($result != []){
             return [$result, $meta];
            }
            Session::flash('error', 'Không có danh sách nhật ký hoạt động');
             return false;
         } catch (\Exception $error) {
             Session::flash('error', 'Không lấy được danh sách' . $error);
             return false;
         }
    }

    public function toggleActiveNhatKyDongRuong($id_nhatkydongruong){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();

        $nhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
        ->where('id_xavien', $id_xavien)    
        ->first();

        if($nhatKyDongRuong == null){
            Session::flash('error', 'Hoạt động không tồn tại');
            return false;
        }
        $id_lichmuavu = $nhatKyDongRuong->id_lichmuavu;

        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
       
        if($nhatKyDongRuong->hoptacxa_xacnhan == 0 && $nhatKyDongRuong->type == 'outside'){
            Session::flash('error', 'Hoạt động bên ngoài hoạt động chung vui lòng chờ hợp tác xã xác nhận trước khi đánh dấu đã hoàn thành');
            return false;
        }
       try {
        $status = 0;
        if($nhatKyDongRuong->status != 1){
            $status = 1;
        }
        DB::beginTransaction();
        $nhatKyDongRuong->status = $status;
        $nhatKyDongRuong->save();
        DB::commit();
        return $nhatKyDongRuong;
       } catch (\Exception $error) {
        Session::flash('error', 'Cập nhật trạng thái không thành công');
        return false;
       } 
    }

    
    public function attachHoatDongIntoNhatKy($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_lichmuavu = $request->id_lichmuavu;

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để thực hiện hành động này');
            return false;
        }
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        if(HoatDongMuaVu::where('id_lichmuavu',$id_lichmuavu)->count() == 0
        ){
            Session::flash('error', 'Lịch mùa vụ chưa có hoạt động nào!');
            return false;
        }

        if(HoatDongMuaVu::where('id_lichmuavu',$id_lichmuavu)->where('attach', 'no_attach')->count() == 0
            && HoatDongMuaVu::where('id_lichmuavu',$id_lichmuavu)->where('attach', 'update')->count() == 0
        ){
            Session::flash('error', 'Hoạt động của lịch mùa vụ đã được áp lịch cho tất cả xã viên, không thể áp lịch nữa !');
            return false;
        }


        DB::beginTransaction();
        // TẠO NHẬT KÝ MỚI CHO XÃ VIÊN
        $listIdXaVien = XaVien::where('id_hoptacxa', $id_hoptacxa)->where('active', 1)->get('id_xavien');
        // return dd($listIdXaVien->count());

        foreach ($listIdXaVien as $key_1 => $xaVien) {
            $listIdThuaDat = ThuaDat::where('id_xavien', $xaVien->id_xavien)->where('active', 1)->get('id_thuadat');
            
            foreach ($listIdThuaDat as $key_2 => $thuaDat) {
                $listHoatDong_no_attach = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)
                    ->where('attach', 'no_attach')
                    ->get();
                foreach ($listHoatDong_no_attach as $key_3 => $hoatDong_no_attach) {
                    try {
                        NhatKyDongRuong::create([
                            'id_xavien' => $xaVien->id_xavien,
                            'id_lichmuavu' => $id_lichmuavu,
                            'id_thuadat' =>$thuaDat->id_thuadat,
                            'id_hoatdongmuavu' => $hoatDong_no_attach->id_hoatdongmuavu,
                            'name_hoatdong' => $hoatDong_no_attach->name_hoatdong,
                            'description' => $hoatDong_no_attach->description_hoatdong,
                            'date_start' => $hoatDong_no_attach->date_start,
                            'date_end' => $hoatDong_no_attach->date_end,
                            'type' => 'inside',
                            'status' => 0
                        ]);
                    } catch (\Exception $error) {
                        DB::rollBack();
                        Session::flash('error', $error);
                        return false;
                    }
                } 
            }
        }


        // CẬP NHẬT HOẠT ĐỘNG KHI CÓ THAY ĐỔI
        try {
            $listHoatDong_update = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)
                    ->where('attach', 'update')
                    ->get();
            foreach ($listHoatDong_update as $key => $hoatDong_update) {
                NhatKyDongRuong::where('id_hoatdongmuavu', $hoatDong_update->id_hoatdongmuavu)
                    ->update(array(
                        'name_hoatdong' => $hoatDong_update->name_hoatdong,
                        'description' => $hoatDong_update->description_hoatdong,
                        'date_start' => $hoatDong_update->date_start,
                        'date_end' => $hoatDong_update->date_end,
                        'type' => 'inside',
                        'status' => 0
                    ));
            }


            HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)->where('attach', 'no_attach')->update(array('attach' => 'attached'));
            HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)->where('attach', 'update')->update(array('attach' => 'attached'));
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không cập nhật được trạng thái và thay đổi hành động');
            return false;
        }
        
        DB::commit();
        return true;
    }

    public function addNewNhatKyDongRuong($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_lichmuavu = $request->id_lichmuavu;
        $id_user = $this->commonService->getIDByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
       
        $checkDateStartDateEnd = $this->commonService->checkDate($date_start, $date_end);

        try {
            DB::beginTransaction();
            if(($checkDateStartDateEnd)){
            $nhatKyDongRuong = NhatKyDongRuong::create([
                'id_xavien' => $id_xavien,
                'id_lichmuavu' => $id_lichmuavu,
                'id_thuadat' =>$request->id_thuadat,
                'name_hoatdong' => $request->name_hoatdong,
                'description' => $request->description,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'type' => 'outside',
                'status' => 0,
                'hoptacxa_xacnhan' => 0
            ]);}
            DB::commit();
            return $nhatKyDongRuong;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }



    }

    public function deleteNhatKyHoatDong($id_nhatkydongruong){

        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();

        $nhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
        ->where('id_xavien', $id_xavien)    
        ->first();

        if($nhatKyDongRuong == null){
            Session::flash('error', 'Hoạt động không tồn tại');
            return false;
        }
        $id_lichmuavu = $nhatKyDongRuong->id_lichmuavu;

        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
       
        if($nhatKyDongRuong->id_hoatdongmuavu != null){
            Session::flash('error', 'Bạn không thể xóa hoạt động chung của hợp tác xã');
            return false;
        }

        try {
            DB::beginTransaction();
            NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
                            ->where('id_xavien', $id_xavien)
                            ->delete();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không thể xóa hoạt động.');
            return false;
        }
    }

}