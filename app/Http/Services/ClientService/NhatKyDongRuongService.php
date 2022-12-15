<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\BlockChainService\BlockChainAPIService;
use App\Http\Services\CommonService;
use App\Models\HoatDongMuaVu;
use App\Models\LichMuaVu;
use App\Models\NhatKyDongRuong;
use App\Models\ThuaDat;
use App\Models\VatTuSuDung;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NhatKyDongRuongService{
    
    protected $commonService;
    protected $xaVienService;
    protected $hopTacXaService;
    protected $lichMuaVuService;
    protected $thuaDatService;
    protected $vatTuSuDungService;
    protected $notificationService;
    protected $blockChainAPIService;
    

    public function __construct(
        CommonService $commonService,
        XaVienService $xaVienService,
        HopTacXaService $hopTacXaService ,
        LichMuaVuService $lichMuaVuService,
        ThuaDatService $thuaDatService,
        VatTuSuDungService $vatTuSuDungService,
        NotificationService $notificationService,
        BlockChainAPIService $blockChainAPIService)
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->lichMuaVuService = $lichMuaVuService;
        $this->thuaDatService = $thuaDatService;
        $this->vatTuSuDungService = $vatTuSuDungService;
        $this->notificationService = $notificationService;
        $this->blockChainAPIService = $blockChainAPIService;

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
            ->join('tbl_thuadat', 'tbl_thuadat.id_thuadat', 'tbl_nhatkydongruong.id_thuadat')
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
                'tbl_nhatkydongruong.reason',
                'tbl_lichmuavu.name_lichmuavu',
                'tbl_lichmuavu.date_start as lichmuavu_date_start',
                'tbl_lichmuavu.date_end as lichmuavu_date_end',
                'tbl_user.fullname',
                'tbl_thuadat.address'
                )
            ->first();


            $detailVatTuSuDung = VatTuSuDung::where('id_nhatkydongruong', $detailNhatKyDongRuong->id_nhatkydongruong)
            ->join('tbl_giaodichmuaban_vattu', 'tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu', 'tbl_vattusudung.id_giaodichmuaban_vattu')
            ->join('tbl_category_vattu', 'tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')    
            ->select('tbl_vattusudung.*', 'tbl_category_vattu.name_category_vattu')
            ->get();

            if($detailNhatKyDongRuong == null){
                Session::flash('error', 'Nhật ký hoạt động không tồn tại');
                return false; 
            }
            
            $result = ([
                "id_nhatkydongruong" => $detailNhatKyDongRuong->id_nhatkydongruong,
                "id_thuadat" => $detailNhatKyDongRuong->id_thuadat,
                "name_hoatdong" => $detailNhatKyDongRuong->name_hoatdong,
                "description" => $detailNhatKyDongRuong->description,
                "date_start" => $detailNhatKyDongRuong->date_start,
                "date_end" => $detailNhatKyDongRuong->date_end,
                "status" => $detailNhatKyDongRuong->status,
                "type" => $detailNhatKyDongRuong->type,
                "hoptacxa_xacnhan" => $detailNhatKyDongRuong->hoptacxa_xacnhan,
                "reason" => $detailNhatKyDongRuong->reason,
                "name_lichmuavu" => $detailNhatKyDongRuong->name_lichmuavu,
                "lichmuavu_date_start" => $detailNhatKyDongRuong->lichmuavu_date_start,
                "lichmuavu_date_end" => $detailNhatKyDongRuong->lichmuavu_date_end,
                "fullname" => $detailNhatKyDongRuong->fullname,
                "address" => $detailNhatKyDongRuong->address,
                "vattusudung" =>  $detailVatTuSuDung
            ]);
            return $result;
            // return $detailNhatKyDongRuong;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được nhật ký đồng ruộng' . $error);
            return false;
        }

        
    }
    public function getDetailNhatKyDongRuongBlockChain($request){
        $id_nhatkydongruong = $request->id_nhatkydongruong;
        try {
            $detailNhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
            ->join('tbl_thuadat', 'tbl_thuadat.id_thuadat', 'tbl_nhatkydongruong.id_thuadat')
            ->select(
                'tbl_nhatkydongruong.id_nhatkydongruong',
                'tbl_nhatkydongruong.name_hoatdong',
                'tbl_nhatkydongruong.date_start',
                'tbl_nhatkydongruong.date_end',
                'tbl_nhatkydongruong.id_thuadat',
                'tbl_thuadat.address'
                )
            ->first();

            $detailVatTuSuDung = VatTuSuDung::where('id_nhatkydongruong', $detailNhatKyDongRuong->id_nhatkydongruong)
            ->join('tbl_giaodichmuaban_vattu', 'tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu', 'tbl_vattusudung.id_giaodichmuaban_vattu')
            ->join('tbl_category_vattu', 'tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')    
            ->select('tbl_vattusudung.*',
            'tbl_category_vattu.name_category_vattu',
            'tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu',
            )
            ->get();

            $result = ([
                "id_nhatkydongruong" => $detailNhatKyDongRuong->id_nhatkydongruong,
                "id_thuadat" => $detailNhatKyDongRuong->id_thuadat,
                "address" => $detailNhatKyDongRuong->address,
                "name_hoatdong" => $detailNhatKyDongRuong->name_hoatdong,
                "date_start" => $detailNhatKyDongRuong->date_start,
                "date_end" => $detailNhatKyDongRuong->date_end,
                "vattusudung" =>  $detailVatTuSuDung
            ]);

            return $result;
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
            $order = "id_nhatkydongruong";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "desc";
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
            $data = NhatKyDongRuong::join('tbl_thuadat', 'tbl_thuadat.id_thuadat', 'tbl_nhatkydongruong.id_thuadat')
                ->where('tbl_nhatkydongruong.id_xavien', $id_xavien)
                ->where('tbl_nhatkydongruong.id_lichmuavu', $id_lichmuavu)
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
                    'tbl_thuadat.address',
                    )
                ->HoatDongMuaVu($request)
                ->NameHoatDongMuaVu($request)
                ->DateStart($request)
                ->DateEnd($request)
                ->Status($request)
                ->Type($request)
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
             Session::flash('error', 'Không lấy được danh sách');
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
            $order = "id_nhatkydongruong";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "desc";
        }
        
        if($request->has('id_lichmuavu')){
            if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
                Session::flash('error', 'Lịch mùa vụ không tồn tại');
                return false;
            }
        }else{
          $lichmuavu = LichMuaVu::orderBy('id_lichmuavu', 'desc')->first();
          $id_lichmuavu = $lichmuavu->id_lichmuavu;
        }

        
        try {
            $data = NhatKyDongRuong
                ::join('tbl_xavien', 'tbl_xavien.id_xavien', 'tbl_nhatkydongruong.id_xavien')
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_thuadat', 'tbl_thuadat.id_thuadat', 'tbl_nhatkydongruong.id_thuadat')
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
                    'tbl_user.fullname',
                    'tbl_thuadat.address'
                    )
                ->LichMuaVu($id_lichmuavu)
                ->HoatDongMuaVu($request)
                ->DateStart($request)
                ->DateEnd($request)
                ->Type($request)
                ->Status($request)
                ->Approve($request)
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
             Session::flash('error', 'Không lấy được danh sách');
             return false;
         }
    }

    public function toggleActiveNhatKyDongRuong($request)
    {

        $id_nhatkydongruong = $request->id_nhatkydongruong;

        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();

        $nhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
        ->where('id_xavien', $id_xavien)    
        ->first();

        if($nhatKyDongRuong == null){
            Session::flash('error', 'Hoạt động không tồn tại');
            return false;
        }
        if($nhatKyDongRuong->hoptacxa_xacnhan == 2){
            Session::flash('error', 'Hoạt động đã bị từ chối không thể cập nhật');
            return false;
        }
        if($nhatKyDongRuong->status == 1){
            Session::flash('error', 'Hoạt động đã được lưu trữ không thể thay đổi trạng thái');
            return false;
        }
        $id_lichmuavu = $nhatKyDongRuong->id_lichmuavu;

        if(!$this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $nhatKyDongRuong->id_lichmuavu)->where('id_hoptacxa', $id_hoptacxa)->first();

        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'Lịch mùa vụ đã kết thúc không thể cập nhật hoạt động');
            return false;
        }
       
        if($nhatKyDongRuong->hoptacxa_xacnhan == 0 && $nhatKyDongRuong->type == 'outside'){
            Session::flash('error', 'Hoạt động bên ngoài hoạt động chung vui lòng chờ hợp tác xã xác nhận trước khi đánh dấu đã hoàn thành');
            return false;
        }
       try {
        $status = 1;
        if($nhatKyDongRuong->status != 1){
            $status = 1;
        }
        DB::beginTransaction();
        $nhatKyDongRuong->status = 1;
        $nhatKyDongRuong->save();
        DB::commit();


        // CREATE BLOCKCHAIN NODE NHATKYDONGRUONG
        if($nhatKyDongRuong->status == 1){
            // call api blockchain save data
            $wallet = $this->commonService->getWalletTypeByToken();
            $password = $this->blockChainAPIService->BASE_PASSWORD;
            $time = $this->commonService->convertDateTOTimeStringForBlockChain($nhatKyDongRuong->date_start);

            if($nhatKyDongRuong->id_hoatdongmuavu == null){
                $id_hoatdongmuavu = 0;
            }else{
                $id_hoatdongmuavu = $nhatKyDongRuong->id_hoatdongmuavu;
            }

            $this->blockChainAPIService->createBlockChainNhatKyDongRuong(
                $nhatKyDongRuong->id_xavien,
                $nhatKyDongRuong->id_nhatkydongruong,
                $nhatKyDongRuong->id_lichmuavu,
                $nhatKyDongRuong->id_thuadat,
                $time,
                $id_hoatdongmuavu,
                $wallet,
                $password                
            );
        }
        // CREATE BLOCKCHAIN NODE NHATKYDONGRUONG
        $vattusudung = VatTuSuDung::where('tbl_vattusudung.id_nhatkydongruong',  $id_nhatkydongruong)
                    ->join('tbl_giaodichmuaban_vattu', 'tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu', 'tbl_vattusudung.id_giaodichmuaban_vattu')
                    ->join('tbl_category_vattu','tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')
                    ->select(
                        'tbl_vattusudung.id_vattusudung',
                        'tbl_vattusudung.id_nhatkydongruong',
                        'tbl_vattusudung.id_giaodichmuaban_vattu',
                        'tbl_vattusudung.soluong',
                        'tbl_vattusudung.timeuse',
                        'tbl_category_vattu.id_category_vattu',
                        'tbl_category_vattu.name_category_vattu',
                        )
                    ->get();
        if($vattusudung != [] || $vattusudung != null){
            foreach ($vattusudung as $key => $vattu) {
                $this->blockChainAPIService->createBlockChainVatTuSuDung(
                    $vattu->id_vattusudung,
                    $vattu->id_nhatkydongruong,
                    $vattu->id_category_vattu,
                    $vattu->id_giaodichmuaban_vattu,
                    $this->commonService->convertDateTOTimeStringForBlockChain($vattu->timeuse),
                    $vattu->soluong,
                    $vattu->name_category_vattu,
                    $wallet,
                    $password
                );
            }
        }

        return $nhatKyDongRuong;
       } catch (\Exception $error) {
        Session::flash('error', 'Cập nhật trạng thái không thành công');
        return false;
       } 
    }

    public function approveNhatKyDongRuong($request){
        $id_nhatkydongruong = $request->id_nhatkydongruong;
        $hoptacxa_xacnhan = $request->hoptacxa_xacnhan; 
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(! $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', "Bạn không có quyền quản trị để duyệt nhật ký hoạt động mùa vụ");
            return false;
        };

        $nhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
        ->first();

        if($nhatKyDongRuong == null){
            Session::flash('error', 'Hoạt động không tồn tại');
            return false;
        }
        if($nhatKyDongRuong->status == 1){
            Session::flash('error', 'Hoạt động đã được thực hiện không thể thay đổi trạng thái');
            return false;
        }
        if($nhatKyDongRuong->type != 'outside'){
            Session::flash('error', 'Đây là hoạt động chung bạn không cần duyệt');
            return false;
        }
        if(in_array($nhatKyDongRuong->hoptacxa_xacnhan, [1,2]) == true){
            Session::flash('error', 'Hoạt động đã được thẩm định không thể thay đổi trạng thái');
            return false;
        }
        if(in_array($hoptacxa_xacnhan, [1,2]) == false){
            Session::flash('error', 'Không xác định được trạng thái');
            return false;
        }
        $lichmuavu = LichMuaVu::where('id_lichmuavu', $nhatKyDongRuong->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Không thể xác nhận hoạt động của mùa vụ đã kết thúc');
            return false;
        }
        

       try {
        DB::beginTransaction();
        $nhatKyDongRuong->hoptacxa_xacnhan = $hoptacxa_xacnhan;
        if($hoptacxa_xacnhan == 2){
            if($request->reason == null){
                Session::flash('error', 'Vui lòng nhập lý do từ chối');
                return false;
            }
            $nhatKyDongRuong->reason = $request->reason;
        }
        $nhatKyDongRuong->save();

        if($nhatKyDongRuong != null){
            $message = "Chủ nhiệm hợp tác xã của bạn đã hủy duyệt hoạt động số $nhatKyDongRuong->id_nhatkydongruong: $nhatKyDongRuong->name_hoatdong";
            if($nhatKyDongRuong->hoptacxa_xacnhan == 1){
                $message = "Chủ nhiệm hợp tác xã của bạn đã duyệt hoạt động số $nhatKyDongRuong->id_nhatkydongruong: $nhatKyDongRuong->name_hoatdong";
            }
            $status_notify = 0;
            $link = "/htx/manage-story/detail/$nhatKyDongRuong->id_lichmuavu?limit=5&page=1&search=";
            $user = XaVien::where('id_xavien', $nhatKyDongRuong->id_xavien)->first()->id_user;
            $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
            $this->notificationService->sendNotificationService($notify->id);
        }

        DB::commit();
        return $nhatKyDongRuong;
       } catch (\Exception $error) {
        Session::flash('error', 'Cập nhật trạng thái không thành công' . $error);
        return false;
       } 
    }

    
    public function attachHoatDongIntoNhatKy($request){
        try {
            $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
            $id_lichmuavu = $request->id_lichmuavu;

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để thực hiện hành động này');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $id_lichmuavu)->where('id_hoptacxa', $id_hoptacxa)->first();

        if($lichmuavu == null){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }

        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'Không thể áp lịch mùa vụ đã kết thúc');
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

        if($this->lichMuaVuService->isLichMuaVuExist($id_hoptacxa, $id_lichmuavu)){
            $message = "Chủ nhiệm hợp tác xã của bạn vừa cập nhật hoạt động chung mùa vụ số $id_lichmuavu. Vui lòng kiểm tra nhật ký hoạt động" ;
            $status_notify = 0;
            $link = "/htx/manage-story/detail/$id_lichmuavu?limit=5&page=1&search=";
            $list_user = $this->hopTacXaService->getAllMemberOfHopTacXa($id_hoptacxa);
            foreach ($list_user as $key => $user) {
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$user->id_user,$link);
                $this->notificationService->sendNotificationService($notify->id);
            }
            
        }
        
        DB::commit();
        return true;
        } catch (\Exception $error) {
            
        }
    }

    public function addNewNhatKyDongRuong($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        $id_lichmuavu = $request->id_lichmuavu;
        $id_user = $this->commonService->getIDByToken();
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $vattusudung = $request->vattusudung;

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $id_lichmuavu)->where('id_hoptacxa', $id_hoptacxa)->first();

        if($lichmuavu == null){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }

       
        $checkDateStartDateEnd = $this->commonService->checkDate($date_start, $date_end);
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

            // Thêm vât tư sử dụng vào hoạt động
            if($vattusudung != null){
                $vattusudung = json_decode(json_encode($vattusudung));
                $id_nhatkydongruong = $nhatKyDongRuong->id_nhatkydongruong;
                foreach ($vattusudung as $key => $vattu) {
                    $id_giaodichmuabanvattu = $vattu->id_giaodichmuaban_vattu;
                    $soluong = $vattu->soluong;
                    $timeuse = $vattu->timeuse;
                    $this->vatTuSuDungService->createVatTuSuDung($id_nhatkydongruong,$id_giaodichmuabanvattu,$soluong, $timeuse);
                }
            }

            DB::commit();
            return $nhatKyDongRuong;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thể tạo nhật ký hoạt động' . $error);
            return false;
        }
    }

    public function updateNhatKyDongRuong($request){
        try {

            $id_nhatkydongruong = $request->id_nhatkydongruong;


            $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
            $id_user = $this->commonService->getIDByToken();
            $id_xavien = $this->xaVienService->getIdXaVienByToken();
            $date_start = $request->date_start;
            $date_end = $request->date_end;
            $vattusudung_update = $request->vattusudung;
           
            $checkDateStartDateEnd = $this->commonService->checkDate($date_start, $date_end);

            $nhatKyDongRuong = NhatKyDongRuong::where('id_nhatkydongruong', $id_nhatkydongruong)
                ->where('id_xavien', $id_xavien)->first();

            if($nhatKyDongRuong == null){
                Session::flash('error', 'Không tồn tại nhật ký hoạt động');
                return false;
            }

            if($nhatKyDongRuong->hoptacxa_xacnhan == null){
                Session::flash('error', 'Hoạt động chung không thể cập nhật');
                return false;
            }
            if($nhatKyDongRuong->hoptacxa_xacnhan == 1){
                Session::flash('error', 'Hoạt động đã được phê duyệt không thể cập nhật');
                return false;
            }
            if($nhatKyDongRuong->hoptacxa_xacnhan == 2){
                Session::flash('error', 'Hoạt động đã bị từ chối không thể cập nhật');
                return false;
            }

           
            $lichmuavu = LichMuaVu::where('id_lichmuavu', $nhatKyDongRuong->id_lichmuavu)->where('id_hoptacxa', $id_hoptacxa)->first();

            if($lichmuavu == null){
                Session::flash('error', 'Lịch mùa vụ không tồn tại');
                return false;
            }
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

            DB::beginTransaction();
            if(($checkDateStartDateEnd)){
                $nhatKyDongRuong->id_xavien = $id_xavien;
                $nhatKyDongRuong->id_thuadat = $request->id_thuadat;
                $nhatKyDongRuong->name_hoatdong = $request->name_hoatdong;
                $nhatKyDongRuong->description = $request->description;
                $nhatKyDongRuong->date_start = $request->date_start;
                $nhatKyDongRuong->date_end = $request->date_end;
            }
            $nhatKyDongRuong->save();

            
           if($request->has('vattusudung')){
            $vattusudung_old = VatTuSuDung::where('id_nhatkydongruong', $id_nhatkydongruong)->get();
            $array_id_vattusudung_old = [];
            foreach ($vattusudung_old as $key => $vattu_old) {
               array_push($array_id_vattusudung_old,$vattu_old->id_vattusudung);
            }

            $vattusudung_update = json_decode(json_encode($vattusudung_update));
            $array_id_vattusudung_update = [];
            foreach ($vattusudung_update as $key => $vattu_update) {
                if(property_exists($vattu_update,'id_vattusudung')){
                    $id_vattusudung = $vattu_update->id_vattusudung;
                    $id_giaodichmuabanvattu = $vattu_update->id_giaodichmuaban_vattu;
                    $soluong = $vattu_update->soluong;
                    $timeuse = $vattu_update->timeuse;
                    $this->vatTuSuDungService->updateVatTuSuDung($id_vattusudung,$id_nhatkydongruong,$id_giaodichmuabanvattu,$soluong, $timeuse);
                    array_push($array_id_vattusudung_update,$vattu_update->id_vattusudung);
                }else{
                    $id_giaodichmuabanvattu = $vattu_update->id_giaodichmuaban_vattu;
                    $soluong = $vattu_update->soluong;
                    $timeuse = $vattu_update->timeuse;
                    $this->vatTuSuDungService->createVatTuSuDung($id_nhatkydongruong,$id_giaodichmuabanvattu,$soluong, $timeuse);
                }
            }

            foreach (array_diff( $array_id_vattusudung_old,$array_id_vattusudung_update) as $key => $vattu_del) {
                $this->vatTuSuDungService->deleteVatTuSuDung($vattu_del);
            }
           }
    
            DB::commit();

            return $this->getDetailNhatKyDongRuong($request);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thể cập nhật ký hoạt động' . $error);
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

        $lichmuavu = LichMuaVu::where('id_lichmuavu',  $nhatKyDongRuong->id_lichmuavu)
                ->where('id_hoptacxa',  $id_hoptacxa)->first();

        if($lichmuavu == null){
            Session::flash('error', 'Lịch mùa vụ không tồn tại');
            return false;
        }
        
        if($lichmuavu->status == 'finish'){
            Session::flash('error', 'Lịch mùa vụ đã kết thúc không thể xóa hoạt động');
            return false;
        }
       
        if($nhatKyDongRuong->id_hoatdongmuavu != null){
            Session::flash('error', 'Bạn không thể xóa hoạt động chung của hợp tác xã');
            return false;
        }

        if($nhatKyDongRuong->hoptacxa_xacnhan == 1){
            Session::flash('error', 'Hoạt động đã được phê duyệt không thể xóa');
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