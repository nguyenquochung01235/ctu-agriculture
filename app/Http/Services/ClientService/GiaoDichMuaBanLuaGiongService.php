<?php

namespace App\Http\Services\ClientService;
use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\GiaoDichMuaBanLuaGiong;
use App\Models\NhaCungCapVatTu;
use App\Models\XaVien;
use AWS\CRT\HTTP\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GiaoDichMuaBanLuaGiongService{

    protected $xaVienService;
    protected $commonService;
    protected $hopTacXaService;
    protected $notificationService;
    protected $uploadImageService;
    protected $nhaCungCapVatTuService;

    public function __construct(
        XaVienService $xaVienService,
        HopTacXaService $hopTacXaService,
        NhaCungCapVatTuService $nhaCungCapVatTuService,
        NotificationService $notificationService,
        CommonService $commonService,
        UploadImageService $uploadImageService
        )
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
        $this->nhaCungCapVatTuService = $nhaCungCapVatTuService;
        $this->uploadImageService = $uploadImageService;
    }

    public function getDetailGiaoDichMuaBanLuaGiong($id_giaodichmuabanluagiong){
        try {
            $giaodich = GiaoDichMuaBanLuaGiong::where('tbl_giaodich_luagiong.id_giaodich_luagiong', $id_giaodichmuabanluagiong)
            ->join('tbl_gionglua', 'tbl_gionglua.id_gionglua','tbl_giaodich_luagiong.id_gionglua')
            ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu','tbl_giaodich_luagiong.id_lichmuavu')
            ->join('tbl_hoptacxa', 'tbl_hoptacxa.id_hoptacxa','tbl_giaodich_luagiong.id_hoptacxa')
            ->select(
                "tbl_giaodich_luagiong.*",
                "tbl_lichmuavu.name_lichmuavu",
                "tbl_gionglua.name_gionglua",
                "tbl_hoptacxa.name_hoptacxa",
                "tbl_hoptacxa.phone_number",
            )
            ->first();
            if($giaodich == null){
                Session::flash('error', 'Giao dịch mua bán không tồn tại');
                return false;
            }

            $xavien = XaVien::where('tbl_xavien.id_xavien', $giaodich->id_xavien)
            ->join('tbl_user', 'tbl_user.id_user','tbl_xavien.id_user')
            ->first();

            if($xavien == null){
                Session::flash('error', 'Không lấy được thông tin xã viên');
                return false;
            }

            $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_nhacungcapvattu', $giaodich->id_nhacungcapvattu)
            ->join('tbl_user', 'tbl_user.id_user','tbl_nhacungcapvattu.id_user')
            ->first();

            if($nhacungcapvattu == null){
                Session::flash('error', 'Không lấy được thông tin nhà cung cấp vật tư');
                return false;
            }

            return $result = ([
                "id_giaodich_luagiong" => $giaodich->id_giaodich_luagiong,
                "id_lichmuavu" => $giaodich->id_lichmuavu,
                "name_lichmuavu" => $giaodich->name_lichmuavu,
                "id_gionglua" => $giaodich->id_gionglua,
                "name_gionglua" => $giaodich->name_gionglua,
                "img_lohang" => $giaodich->img_lohang,
                "soluong" => $giaodich->soluong,
                "status" => $giaodich->status,
                "hoptacxa_xacnhan" => $giaodich->hoptacxa_xacnhan,
                "nhacungcap_xacnhan" => $giaodich->nhacungcap_xacnhan,
                "xavien_xacnhan" => $giaodich->xavien_xacnhan,
                "description_giaodich" => $giaodich->description_giaodich,
                "created_at" => $giaodich->created_at,
                "updated_at" => $giaodich->updated_at,
                "id_xavien"=>$giaodich->id_xavien,
                "name_xavien"=>$xavien->fullname,
                "xavien_phone_number"=>$xavien->phone_number,
                "name_hoptacxa"=>$giaodich->name_hoptacxa,
                "hoptacxa_phone_number"=>$giaodich->phone_number,
                "id_nhacungcapvattu"=>$giaodich->id_nhacungcapvattu,
                "nhacungcapvattu_name"=>$nhacungcapvattu->name_daily,
                "nhacungcapvattun_phone_number"=>$nhacungcapvattu->phone_number
            ]);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không tạo được giao dịch mua bán lúa giống');
            return false;
        }
    }

    public function getListGiaoDichMuaBanLuaGiong($request){
        $id = null;
        $who = "";

        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
        if($id_xavien != false){
            $id = $id_xavien;
            $who = "tbl_xavien.id_xavien";
          }
      
          if($id_nhacungcapvattu != false){
            $id = $id_nhacungcapvattu;
            $who = "tbl_nhacungcapvattu.id_nhacungcapvattu";
          }
      
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
              $order = "id_giaodich_luagiong";
          }
          if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
              $sort = "desc";
          }

          try {
            $data = GiaoDichMuaBanLuaGiong::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodich_luagiong.id_lichmuavu')
            ->join('tbl_gionglua', 'tbl_gionglua.id_gionglua', '=', 'tbl_giaodich_luagiong.id_gionglua')
            ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodich_luagiong.id_xavien')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->join('tbl_nhacungcapvattu', 'tbl_nhacungcapvattu.id_nhacungcapvattu', '=', 'tbl_giaodich_luagiong.id_nhacungcapvattu')
            ->Who($who, $id)
            ->select(
                "tbl_giaodich_luagiong.id_giaodich_luagiong",
                "tbl_lichmuavu.name_lichmuavu",
                "tbl_gionglua.name_gionglua",
                "tbl_user.fullname as name_xavien",
                "tbl_nhacungcapvattu.name_daily",
                "tbl_giaodich_luagiong.img_lohang",
                "tbl_giaodich_luagiong.soluong",
                "tbl_giaodich_luagiong.status",
                "tbl_giaodich_luagiong.hoptacxa_xacnhan" ,
                "tbl_giaodich_luagiong.nhacungcap_xacnhan",
                "tbl_giaodich_luagiong.xavien_xacnhan"
            )
            ;
          
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
            Session::flash('error', 'Danh sách giao dịch mua bán lúa giống !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách hợp đồng' . $error);
              return false;
          }
        
    }

    public function getListGiaoDichMuaBanLuaGiongForHTX($request){
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
          if( ! $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xem danh mục này');
            return false;
          }
      
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
              $order = "id_giaodich_luagiong";
          }
          if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
              $sort = "desc";
          }

          try {
            $data = GiaoDichMuaBanLuaGiong::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodich_luagiong.id_lichmuavu')
            ->join('tbl_gionglua', 'tbl_gionglua.id_gionglua', '=', 'tbl_giaodich_luagiong.id_gionglua')
            ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodich_luagiong.id_xavien')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->join('tbl_nhacungcapvattu', 'tbl_nhacungcapvattu.id_nhacungcapvattu', '=', 'tbl_giaodich_luagiong.id_nhacungcapvattu')
            ->where('tbl_giaodich_luagiong.id_hoptacxa', $id_hoptacxa)
            ->select(
                "tbl_giaodich_luagiong.id_giaodich_luagiong",
                "tbl_lichmuavu.name_lichmuavu",
                "tbl_gionglua.name_gionglua",
                "tbl_user.fullname as name_xavien",
                "tbl_nhacungcapvattu.name_daily",
                "tbl_giaodich_luagiong.img_lohang",
                "tbl_giaodich_luagiong.soluong",
                "tbl_giaodich_luagiong.status",
                "tbl_giaodich_luagiong.hoptacxa_xacnhan" ,
                "tbl_giaodich_luagiong.nhacungcap_xacnhan",
                "tbl_giaodich_luagiong.xavien_xacnhan"
            )
            ;
          
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
            Session::flash('error', 'Danh sách giao dịch mua bán lúa giống !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách hợp đồng');
              return false;
          }
        
    }



    public function createGiaoDichMuaBanLuaGiong($request){
        try {
            $id_user = $this->commonService->getIDByToken();
            $account_type = $this->commonService->getAccountTypeByToken();

            switch ($account_type) {
                case 'farmer':
                    $id_xavien = $this->xaVienService->getIdXaVienByToken();
                    $id_hoptacxa = XaVien::where('id_xavien', $id_xavien)->first()->id_hoptacxa;
                    $id_nhacungcapvattu = $request->id_nhacungcapvattu;
                    $nhacungcap_xacnhan = 0;
                    $xavien_xacnhan = 1;
                    break;
                
                case 'shop':
                    $id_xavien= $request->id_xavien;
                    $id_hoptacxa = XaVien::where('id_xavien', $id_xavien)->first()->id_hoptacxa;
                    $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
                    $nhacungcap_xacnhan = 1;
                    $xavien_xacnhan = 0;
                    break;
                
                default:
                    Session::flash('error', 'Không xác định được chủ thể');
                    return false;
                    break;
            }
         

            $id_lichmuavu = $request->id_lichmuavu;
            $id_gionglua = $request->id_gionglua;
            $soluong = $request->soluong;
            $status = 0;
            $description_giaodich = $request->description_giaodich;
            $hoptacxa_xacnhan = 0;
            $img_lohang = null;
            // return dd($request->hasFile('img_lohang'));
            if($request->hasFile('img_lohang'))
            {
                $img_lohang= $this->uploadImageService->store($request->img_lohang);
            }
           
            DB::beginTransaction();
            $giaodichmuabanluagiong = GiaoDichMuaBanLuaGiong::create([
                'id_xavien'=> $id_xavien,
                'id_hoptacxa'=> $id_hoptacxa,
                'id_nhacungcapvattu'=> $id_nhacungcapvattu,
                'id_lichmuavu'=> $id_lichmuavu,
                'id_gionglua'=> $id_gionglua,
                'img_lohang'=> $img_lohang,
                'soluong'=> $soluong,
                'status'=> $status,
                'description_giaodich'=> $description_giaodich,
                'hoptacxa_xacnhan'=> $hoptacxa_xacnhan,
                'nhacungcap_xacnhan'=> $nhacungcap_xacnhan,
                'xavien_xacnhan'=> $xavien_xacnhan,
            ]);
           if($giaodichmuabanluagiong != null){
            switch ($account_type) {
                case 'farmer':
                    $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                    $message = "Giao dịch mua bán lúa giống số ". $giaodichmuabanluagiong->id_giaodich_luagiong . " vừa được cập nhật bởi xã viên " . $xavien->fullname.". Vui lòng kiểm tra thông tin";
                    $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $id_nhacungcapvattu)->first()->id_user;
                    break;
                
                case 'shop':
                    $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                    $message = "Giao dịch mua bán lúa giống số ". $giaodichmuabanluagiong->id_giaodich_luagiong . " vừa được cập nhật bởi nhà cung cấp vật tư " . $nhacungcapvattu->fullname.". Vui lòng kiểm tra thông tin";
                    $user = XaVien::where('id_xavien', $id_xavien)->first()->id_user;
                    break;
                
                default:
                    break;
            }
            $status_notify = 0;
            $link = "/giaodichmuabanluagiong";
            $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
            $this->notificationService->sendNotificationService($notify->id);
           }
            DB::commit();
            return $this->getDetailGiaoDichMuaBanLuaGiong($giaodichmuabanluagiong->id_giaodich_luagiong);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không tạo được giao dịch mua bán lúa giống');
            return false;
        }
    }

    // public function updateGiaoDichMuaBanLuaGiong($request){
    //     try {
    //         $id_user = $this->commonService->getIDByToken();
    //         $account_type = $this->commonService->getAccountTypeByToken();
    //         $who = "";
    //         $id = "";

    //         switch ($account_type) {
    //             case 'farmer':
    //                 $id_xavien = $this->xaVienService->getIdXaVienByToken();
    //                 $id_hoptacxa = XaVien::where('id_xavien', $id_xavien)->first()->id_hoptacxa;
    //                 $id_nhacungcapvattu = $request->id_nhacungcapvattu;
    //                 $nhacungcap_xacnhan = 0;
    //                 $xavien_xacnhan = 1;
    //                 $who = "id_xavien";
    //                 $id = $id_xavien;
    //                 break;
                
    //             case 'shop':
    //                 $id_xavien= $request->id_xavien;
    //                 $id_hoptacxa = XaVien::where('id_xavien', $id_xavien)->first()->id_hoptacxa;
    //                 $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
    //                 $nhacungcap_xacnhan = 1;
    //                 $xavien_xacnhan = 0;
    //                 $who = "id_nhacungcapvattu";
    //                 $id = $id_nhacungcapvattu;
    //                 break;
                
    //             default:
    //                 Session::flash('error', 'Không xác định được chủ thể');
    //                 return false;
    //                 break;
    //         }
         

    //         $id_giaodichmuabanluagiong = $request->id_giaodichmuabanluagiong;

    //         $giaodichmuabanluagiong = GiaoDichMuaBanLuaGiong::where('id_giaodichmuabanluagiong', $id_giaodichmuabanluagiong)
    //         ->Who($who,$id)
    //         ->first();
    //         return $giaodichmuabanluagiong;


    //         $id_lichmuavu = $request->id_lichmuavu;
    //         $id_gionglua = $request->id_gionglua;
    //         $soluong = $request->soluong;
    //         $status = 0;
    //         $description_giaodich = $request->description_giaodich;
    //         $hoptacxa_xacnhan = 0;
    //         $img_lohang = null;
    //         // return dd($request->hasFile('img_lohang'));
    //         if($request->hasFile('img_lohang'))
    //         {
    //             $img_lohang= $this->uploadImageService->store($request->img_lohang);
    //         }

           
            
    //        if($giaodichmuabanluagiong != null){
    //         switch ($account_type) {
    //             case 'farmer':
    //                 $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
    //                 $message = "Giao dịch mua bán lúa giống số ". $giaodichmuabanluagiong->id_giaodich_luagiong . " vừa được cập nhật bởi xã viên " . $xavien->fullname.". Vui lòng kiểm tra thông tin";
    //                 $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $id_nhacungcapvattu)->first()->id_user;
    //                 break;
                
    //             case 'shop':
    //                 $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
    //                 $message = "Giao dịch mua bán lúa giống số ". $giaodichmuabanluagiong->id_giaodich_luagiong . " vừa được cập nhật bởi nhà cung cấp vật tư " . $nhacungcapvattu->fullname.". Vui lòng kiểm tra thông tin";
    //                 $user = XaVien::where('id_xavien', $id_xavien)->first()->id_user;
    //                 break;
                
    //             default:
    //                 break;
    //         }
    //         $status_notify = 0;
    //         $link = "/giaodichmuabanluagiong";
    //         $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
    //         $this->notificationService->sendNotificationService($notify->id);
    //        }
    //         DB::commit();
    //         return $this->getDetailGiaoDichMuaBanLuaGiong($giaodichmuabanluagiong->id_giaodich_luagiong);
    //     } catch (\Exception $error) {
    //         DB::rollBack();
    //         Session::flash('error', 'Không tạo được giao dịch mua bán lúa giống');
    //         return false;
    //     }
    // }

}