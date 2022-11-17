<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\HoatDongMuaVu;
use App\Models\LichMuaVu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LichMuaVuService{

    protected $xaVienService;
    protected $commonService;
    protected $hopTacXaService;
    protected $notificationService;

    public function __construct(
        XaVienService $xaVienService,
        CommonService $commonService,
        HopTacXaService $hopTacXaService,
        NotificationService $notificationService)
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
    }
    public function isLichMuaVuExist($id_hoptacxa,$id_lichmuavu){
        try {
            $result =  LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->where('id_lichmuavu', $id_lichmuavu)->count();
            if($result > 0){
              return true;
            }
            Session::flash('error', 'Không tìm thấy mùa vụ !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách lịch mùa vụ' . $error);
              return false;
          }
    }

    public function getListLichMuaVuAutoComplete($request){
        try {
            $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
            
            $lichmuavu = LichMuaVu::join('tbl_gionglua','tbl_lichmuavu.id_gionglua','=','tbl_gionglua.id_gionglua')
            ->where('id_hoptacxa', $id_hoptacxa)
            ->Search($request)
            ->limit(15)
            ->orderBy('id_lichmuavu', 'desc')
            ->get();
            return $lichmuavu;
        } catch (\Exception $error) {
            Session::flash('error', 'Không có danh sách lịch mùa vụ');
            return false;
        }
    }
    
    public function getListLichMuaVuForHopDongMuaBan($id_hoptacxa){
        try {
            $lichmuavu = LichMuaVu::join('tbl_gionglua','tbl_lichmuavu.id_gionglua','=','tbl_gionglua.id_gionglua')
            ->where('id_hoptacxa', $id_hoptacxa)->whereIn('status', ['upcoming', 'start'])->get();
            return $lichmuavu;
        } catch (\Exception $error) {
            Session::flash('error', 'Không có danh sách lịch mùa vụ');
            return false;
        }
    }

    public function getDetailLichMuaVu($request){
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        
        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xem hoạt động mùa vụ');
            return false;
        }if(!$this->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        

        try {
            $detailLichMuaVu = LichMuaVu::join('tbl_gionglua','tbl_lichmuavu.id_gionglua','=','tbl_gionglua.id_gionglua')
            ->where('id_lichmuavu', $id_lichmuavu)->first();
            if($detailLichMuaVu == null){
                Session::flash('error', 'Lịch mùa vụ không tồn tại');
                return false; 
            }
            return ($detailLichMuaVu);
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được lịch mùa vụ');
            return false;
        }

    }

    public function getListLichMuaVu($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_user = $this->commonService->getIDByToken();
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

        if(!$this->xaVienService->isXaVienBelongToHTX($id_user, $id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền xem lịch mùa vụ !');
            return false;
        }

        try {
            $data = LichMuaVu::join('tbl_gionglua','tbl_lichmuavu.id_gionglua','=','tbl_gionglua.id_gionglua')
            ->where('id_hoptacxa', $id_hoptacxa)
            ->NameLichMuaVu($request)
            ->DateStart($request)
            ->DateEnd($request)
            ->Status($request)
            ->Search($request);

            $total = $data->count();
            $meta = $this->commonService->pagination($total,$page,$limit);
            $result = $data
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->orderBy($order, $sort)
            ->get();
            

            if($result != []){
              return [$result,$meta];
            }
            Session::flash('error', 'Danh sách lịch mùa vụ rỗng !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách lịch mùa vụ' . $error);
              return false;
          }
    }

    public function createLichMuaVu($request){
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $status = "upcoming"; // Trạng thái sắp bắt đầu / upcoming - start - finish

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để tạo lịch mùa vụ');
            return false;
        }

        if(!$this->commonService->checkDate($date_start, $date_end)){
            return false;
        }

        if($date_start <= now()->format('Y-m-d')){
            $status = "start"; //Đã bắt đầu
        }

        if($date_end < now()->format('Y-m-d')){
            Session::flash('error', 'Ngày kết thúc không thể nhỏ hơn này hiện tại');
            return false;
        }

        $lichmuavu_start = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->where('status', 'start')->first();
        $lichmuavu_upcoming = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->where('status', 'upcoming')->first();

        if($lichmuavu_start != null ||$lichmuavu_upcoming != null){
            if( $lichmuavu_start != null ){
                Session::flash('error', 'Bạn đã có 1 mùa vụ đang diễn ra, không thể tạo thêm mùa vụ mới');
                return false;
            }
    
            if( $lichmuavu_upcoming != null ){
                Session::flash('error', 'Bạn đã có 1 mùa vụ sắp diễn ra, không thể tạo thêm mùa vụ mới');
                return false;
            }
        }



        try {

            DB::beginTransaction();

                $lichmuavu = LichMuaVu::create([
                    'id_hoptacxa' => $id_hoptacxa,
                    'id_gionglua' => $request->input('id_gionglua'),
                    'name_lichmuavu' => $request->input('name_lichmuavu'),
                    'date_start' => $request->input('date_start'),
                    'date_end' => $request->input('date_end'),
                    'status' => $status
                ]);
                
                // Send notify
                if($lichmuavu != null){
                    $message = "Chủ nhiệm hợp tác xã của bạn vừa tạo lịch mùa vụ mới. Mùa vụ số ". $lichmuavu->id_lichmuavu;
                    $status_notify = 0;
                    $link = "/lichmuavu";
                    $list_user = $this->hopTacXaService->getAllMemberOfHopTacXa($id_hoptacxa);
                    foreach ($list_user as $key => $user) {
                        $notify = $this->notificationService->createNotificationService($message, $status_notify,$user->id_user,$link);
                        $this->notificationService->sendNotificationService($notify->id);
                    }
                }

                DB::commit();
                return $lichmuavu;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không tạo được lịch mùa vụ' . $error);
            return false;
        }
    }


    public function updateLichMuaVu($request){
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $status = "upcoming"; // Trạng thái sắp bắt đầu

        $checkDateStartDateEnd = $this->commonService->checkDate($date_start, $date_end);
        
        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để cập nhật lịch mùa vụ');
            return false;
        }
        if(!$this->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        
        if($date_start <= now()->format('Y-m-d')){
            $status = "start"; //Đã bắt đầu
        } 

        if($date_end < now()->format('Y-m-d')){
            Session::flash('error', 'Ngày kết thúc không thể nhỏ hơn này hiện tại');
            return false;
        }

        $lichmuavu = LichMuaVu::find($id_lichmuavu);
        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'Bạn không chỉnh sửa mùa vụ đã kết thúc');
            return false;
        }

        $hoatdong_in_lichmuavu_max_date_start = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)->min('date_start');
        $hoatdong_in_lichmuavu_max_date_end = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)->max('date_end');
        if($hoatdong_in_lichmuavu_max_date_start != null){
            if($hoatdong_in_lichmuavu_max_date_start > $date_start){
                Session::flash('error', 'Ngày bắt đầu lịch mùa vụ lớn hơn ngày bắt đầu hoạt động mùa vụ, vui lòng cập nhật hoạt động mùa vụ trước khi thay đổi');
                return false;
            }
        }

        // return dd($hoatdong_in_lichmuavu_max_date_end);

        if($hoatdong_in_lichmuavu_max_date_end != null){
            if($hoatdong_in_lichmuavu_max_date_end > $date_end){
                Session::flash('error', 'Ngày kết thúc lịch mùa vụ nhỏ hơn ngày kết thúc hoạt động mùa vụ, vui lòng cập nhật hoạt động mùa vụ trước khi thay đổi');
                return false;
            }
        }
       


        try {
            DB::beginTransaction();
            if(($checkDateStartDateEnd)){

                
                    $lichmuavu->id_gionglua = $request->input('id_gionglua');
                    $lichmuavu->name_lichmuavu = $request->input('name_lichmuavu');
                    $lichmuavu->date_start = $request->input('date_start');
                    $lichmuavu->date_end = $request->input('date_end');
                    $lichmuavu->status = $status;
                
                $lichmuavu->save();

                if($lichmuavu != null){
                    $message = "Chủ nhiệm hợp tác xã của bạn vừa cập nhật lịch mùa vụ số ". $lichmuavu->id_lichmuavu;
                    $status_notify = 0;
                    $link = "/lichmuavu";
                    $list_user = $this->hopTacXaService->getAllMemberOfHopTacXa($id_hoptacxa);
                    foreach ($list_user as $key => $user) {
                        $notify = $this->notificationService->createNotificationService($message, $status_notify,$user->id_user,$link);
                        $this->notificationService->sendNotificationService($notify->id);
                    }
                    
                }
                DB::commit();
                return $lichmuavu;
            }
            DB::rollBack();
            return false;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không cập nhật được lịch mùa vụ' . $error);
            return false;
        }

    }

    public function deleteLichMuaVu($request){
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xóa hoạt lịch mùa vụ');
            return false;
        }

        if(!$this->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        try {
            $lichmuavu = LichMuaVu::find($id_lichmuavu);
                
            if($lichmuavu->status != 'upcoming'){
                Session::flash('error', 'Lịch mùa vụ không thể xóa vì đang hoạt động hoặc đã kết thúc');
                return false;
            }

            DB::beginTransaction();
            $lichmuavu->delete();
            
            if($lichmuavu != null){
                $message = "Chủ nhiệm hợp tác xã của bạn vừa xóa lịch mùa vụ số ". $lichmuavu->id_lichmuavu;
                $status_notify = 0;
                $link = "/lichmuavu";
                $list_user = $this->hopTacXaService->getAllMemberOfHopTacXa($id_hoptacxa);
                foreach ($list_user as $key => $user) {
                    $notify = $this->notificationService->createNotificationService($message, $status_notify,$user->id_user,$link);
                    $this->notificationService->sendNotificationService($notify->id);
                }
                
            }
            DB::commit();
            return true;
           
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không xóa được lịch mùa vụ');
            return false;
        }


    }

}