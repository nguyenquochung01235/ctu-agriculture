<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\HoatDongMuaVu;
use App\Models\LichMuaVu;
use App\Models\NhatKyDongRuong;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HoatDongMuaVuService{

    protected $commonService;
    protected $hopTacXaService;
    protected $xaVienService;
    protected $lichMuaVuService;

    public function __construct(CommonService $commonService, HopTacXaService $hopTacXaService ,XaVienService $xaVienService, LichMuaVuService $lichMuaVuService)
    {
        $this->commonService = $commonService;
        $this->hopTacXaService = $hopTacXaService;
        $this->xaVienService = $xaVienService;
        $this->lichMuaVuService = $lichMuaVuService;

    }

    public function getDetailHoatDongMuaVu($request){
        $id_hoatdongmuavu = $request->id_hoatdongmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xem hoạt động mùa vụ');
            return false;
        }
        

        try {
            $detailHoatDongMuaVu = HoatDongMuaVu::where('id_hoatdongmuavu', $id_hoatdongmuavu)->first();
            if($detailHoatDongMuaVu == null){
                Session::flash('error', 'Hoạt động không tồn tại');
                return false; 
            }
            return $detailHoatDongMuaVu;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được lịch mùa vụ');
            return false;
        }

    }

    public function getListHoatDongMuaVu($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
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

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xem hoạt động mùa vụ');
            return false;
        }
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        
        try {
            $data = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)
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
              return [$result,$meta];
            }
            Session::flash('error', 'Danh sách hoạt động mùa vụ rỗng !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách hoạt động mùa vụ' . $error);
              return false;
          }
    }

    public function createHoatDongMuaVu($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_lichmuavu = $request->id_lichmuavu;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $status = "upcoming"; // Trạng thái sắp bắt đầu

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để tạo hoạt động mùa vụ');
            return false;
        }
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }

        if(!$this->commonService->checkDate($date_start, $date_end)){
            Session::flash('error', 'Ngày bắt đầu lớn hơn ngày kết thúc');
            return false;
        }
         
        if($date_start < now()->format('Y-m-d')){
            $status = "start"; //Đã bắt đầu
        }
        if($date_end < now()->format('Y-m-d')){
            $status = "finish"; //Kết thúc
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $id_lichmuavu)->where('id_hoptacxa', $id_hoptacxa)->first();
        $lichmuavu_date_start = $lichmuavu->date_start;
        $lichmuavu_date_end = $lichmuavu->date_end;

        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'Lịch mùa vụ đã kết thúc không thể thêm hoạt động');
            return false;
        }

        if($date_start <  $lichmuavu_date_start){
            Session::flash('error', 'Ngày bắt đầu hoạt động nằm ngoài thời gian hoạt động của mùa vụ');
            return false;
        }
        if($date_end >  $lichmuavu_date_end){
            Session::flash('error', 'Ngày kết thúc của hoạt động nằm ngoài thời gian hoạt động của mùa vụ');
            return false;
        }

        try {
            DB::beginTransaction();
            $hoatDongMuaVu = HoatDongMuaVu::create([
                'id_lichmuavu' => $request->input('id_lichmuavu'),
                'name_hoatdong' => $request->input('name_hoatdong'),
                'description_hoatdong' => $request->input('description_hoatdong'),
                'date_start' => $request->input('date_start'),
                'date_end' => $request->input('date_end'),
                'status' => $status,
                'attach' => 'no_attach'
            ]);
            DB::commit();
            return $hoatDongMuaVu;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thể tạo hoạt động' . $error);
            return false;
        }
    }

    public function updateHoatDongMuaVu($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoatdongmuavu = $request->id_hoatdongmuavu;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $status = "upcoming"; // Trạng thái sắp bắt đầu
        $attach = 'no_attach';

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để cập nhật hoạt động mùa vụ');
            return false;
        }
        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }

        if(!$this->commonService->checkDate($date_start, $date_end)){
            Session::flash('error', 'Ngày bắt đầu lớn hơn ngày kết thúc');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu',  $id_lichmuavu)
            ->where('id_hoptacxa',  $id_hoptacxa)->first();
        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'Lịch mùa vụ đã kết thúc không thể cập nhật hoạt động');
            return false;
        }

        $lichmuavu_date_start = $lichmuavu->date_start;
        $lichmuavu_date_end = $lichmuavu->date_end;

        if($date_start <  $lichmuavu_date_start){
            Session::flash('error', 'Ngày bắt đầu hoạt động nằm ngoài thời gian hoạt động của mùa vụ');
            return false;
        }
        if($date_end >  $lichmuavu_date_end){
            Session::flash('error', 'Ngày kết thúc của hoạt động nằm ngoài thời gian hoạt động của mùa vụ');
            return false;
        }

        try {
            if($date_start < now()->format('Y-m-d')){
                $status = "start"; //Đã bắt đầu
            }
            if($date_end < now()->format('Y-m-d')){
                $status = "finish"; //Kết thúc
            }
            DB::beginTransaction();

            $hoatDongMuaVu = HoatDongMuaVu::find($id_hoatdongmuavu);
            if($hoatDongMuaVu == null){
                Session::flash('error', 'Hoạt động không tồn tại');
                return false;
            }

            if($hoatDongMuaVu->attach != 'no_attach'){
                $attach = 'update';
            }
            
            $hoatDongMuaVu->name_hoatdong = $request->name_hoatdong;
            $hoatDongMuaVu->description_hoatdong = $request->description_hoatdong;
            $hoatDongMuaVu->date_start = $request->date_start;
            $hoatDongMuaVu->date_end = $request->date_end;
            $hoatDongMuaVu->status = $status;
            $hoatDongMuaVu->attach = $attach;
            $hoatDongMuaVu->save();

            DB::commit();
            return $hoatDongMuaVu;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thể cập nhật hoạt động' . $error);
            return false;
        }
    }
    public function deleteHoatDongMuaVu($id_hoatdongmuavu){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xóa hoạt động mùa vụ');
            return false;
        }

        try {
            
            DB::beginTransaction();

            $hoatDongMuaVu =  HoatDongMuaVu::find($id_hoatdongmuavu);
            $lichmuavu = LichMuaVu::where('id_lichmuavu',  $hoatDongMuaVu->id_lichmuavu)
                ->where('id_hoptacxa',  $id_hoptacxa)->first();
            if($lichmuavu->status == 'finish'){
                Session::flash('error', 'Lịch mùa vụ đã kết thúc không thể xóa hoạt động');
                return false;
            }

            if($hoatDongMuaVu != null){
                $hoatDongMuaVu->delete();
                NhatKyDongRuong::where('id_hoatdongmuavu', $id_hoatdongmuavu)->delete();
            }else{
                DB::rollBack();
                Session::flash('error', 'Hoạt động không tồn tại');
                return false;
            }

            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thể xóa hoạt động' . $error);
            return false;
        }
    }

}