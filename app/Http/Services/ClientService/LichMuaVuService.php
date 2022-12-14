<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\HoatDongMuaVu;
use App\Models\HopDongMuaBan;
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

    public function autoChangeStatusLichMuaVu($id_hoptacxa){
        try {
            $lichmuavu = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->whereIn('status', ['upcoming','start'])->first();
            if($lichmuavu != null){
                $status = $lichmuavu->status;
                $date_start = $lichmuavu->date_start;
                $date_end = $lichmuavu->date_end;
            }
            if($lichmuavu != null){
                switch ($status) {
                    case 'upcoming':
                        if($date_start>= now()->format('Y-m-d')){
                            $lichmuavu->status = 'start';
                        }
                        if($date_end<=now()->format('Y-m-d')){
                            $lichmuavu->status = 'finish';
                        }
                        break;
                    
                    case 'start':
                        if($date_end<=now()->format('Y-m-d')){
                            $lichmuavu->status = 'finish';
                        }
                        break;
                    
                    default:
                        break;
                }
                DB::beginTransaction();
                $lichmuavu->save();
                DB::commit();
            }
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng thay ?????i ???????c tr???ng th??i l???ch m??a v???');
            return false;
        }
        
        
    }

    public function isLichMuaVuExist($id_hoptacxa,$id_lichmuavu){
        try {
            $result =  LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->where('id_lichmuavu', $id_lichmuavu)->count();
            if($result > 0){
              return true;
            }
            Session::flash('error', 'Kh??ng t??m th???y m??a v??? !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Kh??ng l???y ???????c danh s??ch l???ch m??a v???');
              return false;
          }
    }
    public function isLichMuaVuEnd($id_lichmuavu){
        try {
            $result =  LichMuaVu::where('id_lichmuavu', $id_lichmuavu)->first();
            if($result == null){
            Session::flash('error', 'M??a v??? kh??ng t???n t???i');
              return false;
            }
            if($result->status == 'finish'){
              return true;
            }
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Kh??ng l???y ???????c danh s??ch l???ch m??a v???');
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
            Session::flash('error', 'Kh??ng c?? danh s??ch l???ch m??a v???');
            return false;
        }
    }
    
    public function getListLichMuaVuForHopDongMuaBan($id_hoptacxa){
        try {
            $lichmuavu = LichMuaVu::join('tbl_gionglua','tbl_lichmuavu.id_gionglua','=','tbl_gionglua.id_gionglua')
            ->where('id_hoptacxa', $id_hoptacxa)->whereIn('status', ['upcoming', 'start'])->get();
            return $lichmuavu;
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng c?? danh s??ch l???ch m??a v???');
            return false;
        }
    }

    public function getDetailLichMuaVu($request){
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        
        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'B???n kh??ng c?? quy???n qu???n tr??? ????? xem ho???t ?????ng m??a v???');
            return false;
        }if(!$this->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'L???ch m??a v??? kh??ng t???n t???i');
            return false;
        }
        

        try {
            $detailLichMuaVu = LichMuaVu::join('tbl_gionglua','tbl_lichmuavu.id_gionglua','=','tbl_gionglua.id_gionglua')
            ->where('id_lichmuavu', $id_lichmuavu)->first();
            if($detailLichMuaVu == null){
                Session::flash('error', 'L???ch m??a v??? kh??ng t???n t???i');
                return false; 
            }
            return ($detailLichMuaVu);
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng l???y ???????c l???ch m??a v???');
            return false;
        }

    }

    public function getListLichMuaVu($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        $this->autoChangeStatusLichMuaVu($id_hoptacxa);

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
            Session::flash('error', 'B???n kh??ng c?? quy???n xem l???ch m??a v??? !');
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
            Session::flash('error', 'Danh s??ch l???ch m??a v??? r???ng !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Kh??ng l???y ???????c danh s??ch l???ch m??a v???');
              return false;
          }
    }

    public function createLichMuaVu($request){
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $status = "upcoming"; // Tr???ng th??i s???p b???t ?????u / upcoming - start - finish

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'B???n kh??ng c?? quy???n qu???n tr??? ????? t???o l???ch m??a v???');
            return false;
        }

        if(!$this->commonService->checkDate($date_start, $date_end)){
            return false;
        }

        if($date_start <= now()->format('Y-m-d')){
            $status = "start"; //???? b???t ?????u
        }

        if($date_end < now()->format('Y-m-d')){
            Session::flash('error', 'Ng??y k???t th??c kh??ng th??? nh??? h??n n??y hi???n t???i');
            return false;
        }

        $lichmuavu_start = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->where('status', 'start')->first();
        $lichmuavu_upcoming = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->where('status', 'upcoming')->first();

        if($lichmuavu_start != null ||$lichmuavu_upcoming != null){
            if( $lichmuavu_start != null ){
                Session::flash('error', 'B???n ???? c?? 1 m??a v??? ??ang di???n ra, kh??ng th??? t???o th??m m??a v??? m???i');
                return false;
            }
    
            if( $lichmuavu_upcoming != null ){
                Session::flash('error', 'B???n ???? c?? 1 m??a v??? s???p di???n ra, kh??ng th??? t???o th??m m??a v??? m???i');
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
                    $message = "Ch??? nhi???m h???p t??c x?? c???a b???n v???a t???o l???ch m??a v??? m???i. M??a v??? s??? ". $lichmuavu->id_lichmuavu;
                    $status_notify = 0;
                    $link = "/htx/manage-story?limit=5&page=1&search=";
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
            Session::flash('error', 'Kh??ng t???o ???????c l???ch m??a v???');
            return false;
        }
    }


    public function updateLichMuaVu($request){
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $status = "upcoming"; // Tr???ng th??i s???p b???t ?????u

        $checkDateStartDateEnd = $this->commonService->checkDate($date_start, $date_end);
        
        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'B???n kh??ng c?? quy???n qu???n tr??? ????? c???p nh???t l???ch m??a v???');
            return false;
        }
        if(!$this->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'L???ch m??a v??? kh??ng t???n t???i');
            return false;
        }
        
        if($date_start <= now()->format('Y-m-d')){
            $status = "start"; //???? b???t ?????u
        } 

        if($date_end < now()->format('Y-m-d')){
            Session::flash('error', 'Ng??y k???t th??c kh??ng th??? nh??? h??n n??y hi???n t???i');
            return false;
        }

        $lichmuavu = LichMuaVu::find($id_lichmuavu);
        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'B???n kh??ng ch???nh s???a m??a v??? ???? k???t th??c');
            return false;
        }
        // Check ho???t ?????ng c?? trong l???ch m??a v???
        $hoatdong_in_lichmuavu_max_date_start = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)->min('date_start');
        $hoatdong_in_lichmuavu_max_date_end = HoatDongMuaVu::where('id_lichmuavu', $id_lichmuavu)->max('date_end');
        if($hoatdong_in_lichmuavu_max_date_start != null){
            if($hoatdong_in_lichmuavu_max_date_start > $date_start){
                Session::flash('error', 'Ng??y b???t ?????u l???ch m??a v??? l???n h??n ng??y b???t ?????u ho???t ?????ng m??a v???, vui l??ng c???p nh???t ho???t ?????ng m??a v??? tr?????c khi thay ?????i');
                return false;
            }
        }

        if($hoatdong_in_lichmuavu_max_date_end != null){
            if($hoatdong_in_lichmuavu_max_date_end > $date_end){
                Session::flash('error', 'Ng??y k???t th??c l???ch m??a v??? nh??? h??n ng??y k???t th??c ho???t ?????ng m??a v???, vui l??ng c???p nh???t ho???t ?????ng m??a v??? tr?????c khi thay ?????i');
                return false;
            }
        }

        //Check h???p ?????ng.
        $hopDongMuaBan = HopDongMuaBan::where('id_lichmuavu', $id_lichmuavu)->where('status', 'confirm')->first();

        if($hopDongMuaBan != null){
            if($lichmuavu->id_gionglua != $request->input('id_gionglua')){
                Session::flash('error', 'L???ch m??a v??? c?? trong h???p ?????ng ???? ???????c x??c nh???n, kh??ng th??? ch???nh s???a gi???ng l??a');
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
                    $message = "Ch??? nhi???m h???p t??c x?? c???a b???n v???a c???p nh???t l???ch m??a v??? s??? ". $lichmuavu->id_lichmuavu;
                    $status_notify = 0;
                    $link = "/htx/manage-story?limit=5&page=1&search=";
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
            Session::flash('error', 'Kh??ng c???p nh???t ???????c l???ch m??a v???');
            return false;
        }

    }

    public function deleteLichMuaVu($request){
        $id_lichmuavu = $request->id_lichmuavu;
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'B???n kh??ng c?? quy???n qu???n tr??? ????? x??a ho???t l???ch m??a v???');
            return false;
        }

        if(!$this->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'L???ch m??a v??? kh??ng t???n t???i');
            return false;
        }
        try {
            $lichmuavu = LichMuaVu::find($id_lichmuavu);
                
        
            if($lichmuavu->status == 'start'){
                Session::flash('error', 'L???ch m??a v??? kh??ng th??? x??a v?? m??a v??? ??ang ho???t ?????ng');
                return false;
            }
            if($lichmuavu->status == 'finish'){
                Session::flash('error', 'L???ch m??a v??? kh??ng th??? x??a v?? m??a v??? ???? k???t th??c');
                return false;
            }

            DB::beginTransaction();
            $lichmuavu->delete();
            
            if($lichmuavu != null){
                $message = "Ch??? nhi???m h???p t??c x?? c???a b???n v???a x??a l???ch m??a v??? s??? ". $lichmuavu->id_lichmuavu;
                $status_notify = 0;
                $link = "/htx/manage-story?limit=5&page=1&search=";
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
            Session::flash('error', 'Kh??ng x??a ???????c l???ch m??a v???');
            return false;
        }


    }

}