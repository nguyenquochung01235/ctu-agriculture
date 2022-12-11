<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\HopDongMuaBan;
use App\Models\HopTacXa;
use App\Models\LichMuaVu;
use App\Models\ThuongLai;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HopDongMuaBanService{

  protected $thuongLaiService;
  protected $hopTacXaService;
  protected $xaVienService;
  protected $commonService;
  protected $notificationService;
  protected $giaoDichMuaBanLuaService;

  public function __construct(
    ThuongLaiService $thuongLaiService, 
    HopTacXaService $hopTacXaService,
    XaVienService  $xaVienService,
    CommonService $commonService,
    NotificationService $notificationService,
    GiaoDichMuaBanLuaService $giaoDichMuaBanLuaService)
  {
    $this->thuongLaiService = $thuongLaiService;
    $this->hopTacXaService = $hopTacXaService;
    $this->xaVienService = $xaVienService;
    $this->commonService = $commonService;
    $this->notificationService = $notificationService;
    $this->giaoDichMuaBanLuaService = $giaoDichMuaBanLuaService;
  }


  public function confirmHopDong($id_hopdongmuaban){
    $id_chuthe = null;
    $who = "";

    $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
    $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
    
    if($id_thuonglai == null && $id_hoptacxa == null){
      Session::flash('error', 'Không xác định được chủ thể');
      return false;
    }
    
    if($id_thuonglai != false){
      $id_chuthe = $id_thuonglai;
      $who = "id_thuonglai";
    }

    if($id_hoptacxa != false){
      $id_chuthe = $id_hoptacxa;
      $who = "id_hoptacxa";

      $checkXaVienIsChuNhiemHTX = $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa);
      if($checkXaVienIsChuNhiemHTX == false){
        Session::flash('error', 'Bạn không phải là chủ nhiệm hợp tác xã, không thể chỉnh sữa hợp đồng');
        return false;
      }

    }

    try {
      $hopDongMuaBan = HopDongMuaBan::where('id_hopdongmuaban', $id_hopdongmuaban)
      ->Who($who, $id_chuthe)
      ->first();

      if($hopDongMuaBan == null){
      Session::flash('error', 'Hợp đồng mua bán không tồn tại');
      return false;
      }


      if($hopDongMuaBan->status == "confirm"){
        Session::flash('error', 'Hợp đồng đã được 2 bên xác nhận');
        return false;
      }

      switch ($who) {
        case 'id_thuonglai':
          if($hopDongMuaBan->thuonglai_xacnhan == 1){
            Session::flash('error', 'Bạn đã xác nhận rồi không thể xác nhận lại');
            return false;
          }
          $hopDongMuaBan->thuonglai_xacnhan = 1;
          

          if($hopDongMuaBan->hoptacxa_xacnhan == 1){
           $hopDongMuaBan->status = 'confirm';
           
          }

          break;

        case 'id_hoptacxa':
          if($hopDongMuaBan->hoptacxa_xacnhan == 1){
            Session::flash('error', 'Bạn đã xác nhận rồi không thể xác nhận lại');
            return false;
          }
          $hopDongMuaBan->hoptacxa_xacnhan = 1;


          if($hopDongMuaBan->thuonglai_xacnhan == 1){
            $hopDongMuaBan->status = 'confirm';
           }

          break;
        
        default:
          Session::flash('error', 'Xác nhận hợp đồng không thành công');
          return false;
          break;
      }
      DB::beginTransaction();
      $hopDongMuaBan->save();

      if($hopDongMuaBan != null){
        switch ($who) {
          case 'id_thuonglai':
            $message = "Hợp đồng số ". $id_hopdongmuaban . " vừa được xác nhận bởi thương lái ";
            $user = $this->hopTacXaService->getChuNhiemHTX($hopDongMuaBan->id_hoptacxa)->id_user;
            break;
          
          case 'id_hoptacxa':
            $message = "Hợp đồng số ". $id_hopdongmuaban . " vừa được xác nhận bởi hợp tác xã ";
            $thuonglai = ThuongLai::where('id_thuonglai', $hopDongMuaBan->id_thuonglai)->first();
            $user = $thuonglai->id_user;
            break;
          
          default:
          return false;
            break;
        }
        $status_notify = 0;
        $link = "/hopdongmuaban";
        $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
        $this->notificationService->sendNotificationService($notify->id);
      }
      if($hopDongMuaBan->status == 'confirm'){
        // create giaodichmuabanlua if status == confirm
          // infor thuonglai
          $thuonglai = ThuongLai::join('tbl_user', 'tbl_user.id_user', 'tbl_thuonglai.id_user')
              ->where('id_thuonglai', $hopDongMuaBan->id_thuonglai)
              ->first();
          //Get list xavien of htx
          $listXaVienOfHTXInContract = XaVien::join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
              ->where('tbl_xavien.id_hoptacxa', $hopDongMuaBan->id_hoptacxa) 
              ->get();
            
          if($listXaVienOfHTXInContract == null){
            Session::flash('error', 'Không có xã viên nào trong hợp tác xã của bạn');
            return false;
          }

          foreach ($listXaVienOfHTXInContract as $key => $xavien) {
            $giaodich = $this->giaoDichMuaBanLuaService->createGiaoDichMuaBanLua(
              $hopDongMuaBan->id_thuonglai,
              $xavien->id_xavien,
              $hopDongMuaBan->id_hoptacxa,
              $hopDongMuaBan->id_lichmuavu,
              $xavien->fullname,
              $thuonglai->name_thuonglai,
              $hopDongMuaBan->price
            );
            if($giaodich == null){
              Session::flash('error', 'Không tạo được giao dịch mua bán lúa');
              return false;
            }
            $message = "Giao dịch mua bán lúa số $giaodich->id_giaodichmuaban_lua vừa được tạo thành công do hợp đồng mua bán lúa đã được xác nhận";
            $status_notify = 0;
            $link = "/giaodichmuabanlua";
            $notify = $this->notificationService->createNotificationService($message, $status_notify,$xavien->id_user,$link);
            $this->notificationService->sendNotificationService($notify->id);
          }
      }
      

      DB::commit();
      return $this->getDetailHopDong($id_hopdongmuaban);
      
    } catch (\Exception $error) {
      DB::rollBack();
      Session::flash('error', 'Xác nhận hợp đồng không thành công !' . $error);
      return false;
    }

  }


  public function getDetailHopDong($id_hopdongmuaban){
   try {
    $id = null;
    $who = "";

    $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
    $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
    if($id_thuonglai == null && $id_hoptacxa == null){
      Session::flash('error', 'Không xác định được chủ thể');
      return false;
    }
    
    if($id_thuonglai != false){
      $id = $id_thuonglai;
      $who = "tbl_hopdongmuaban.id_thuonglai";
    }

    if($id_hoptacxa != false){
      $id = $id_hoptacxa;
      $who = "tbl_hopdongmuaban.id_hoptacxa";
    }
     

                  
    $hopDongMuaBan = HopDongMuaBan::where('id_hopdongmuaban', $id_hopdongmuaban)
                  ->join('tbl_danhmucquydinh', 'tbl_danhmucquydinh.id_danhmucquydinh', 'tbl_hopdongmuaban.id_danhmucquydinh')
                  ->join('tbl_gionglua', 'tbl_gionglua.id_gionglua', 'tbl_hopdongmuaban.id_gionglua')
                  ->select('*','tbl_hopdongmuaban.created_at AS created_at','tbl_hopdongmuaban.updated_at AS updated_at')
                  ->Who($who, $id)
                  ->first();
    if($hopDongMuaBan == null){
      Session::flash('error', 'Hợp đồng không tồn tại');
      return false;
    };

      $first_date = strtotime(now());
      $second_date = strtotime($hopDongMuaBan->created_at);
      $datediff = abs($first_date - $second_date);
      $ruleDate = $datediff / (60*60*24);

      if(($ruleDate >= 30.0)){
        if($hopDongMuaBan->status != 'confirm'){
          DB::beginTransaction();
          $hopDongMuaBan->delete();
          DB::commit();
          Session::flash('error', 'Hợp đồng đã bị xóa vì quá hạn xác nhận 30 ngày kể từ ngày tạo hợp đồng');
          return false;
        }
      }

    $thuonglai = ThuongLai::where('id_thuonglai',$hopDongMuaBan->id_thuonglai)
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_thuonglai.id_user')
                ->first();

    $hoptacxa = HopTacXa::where('id_hoptacxa',$hopDongMuaBan->id_hoptacxa)
                ->first();

    $chunhiem = XaVien::join('xavien_rolexavien', 'xavien_rolexavien.xavien_id_xavien', 'tbl_xavien.id_xavien')
            ->join('tbl_rolexavien', 'tbl_rolexavien.id_role', 'xavien_rolexavien.rolexavien_id_role')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->where('id_hoptacxa', $hoptacxa->id_hoptacxa)
            ->where('tbl_rolexavien.role','chunhiem')
            ->first(['fullname']);

    return ([
      'id_hopdongmuaban' => $hopDongMuaBan->id_hopdongmuaban,
      'title_hopdongmuaban' => $hopDongMuaBan->title_hopdongmuaban,
      'description_hopdongmuaban' => $hopDongMuaBan->description_hopdongmuaban,
      'status' => $hopDongMuaBan->status,
      'thuonglai_xacnhan'=> $hopDongMuaBan->thuonglai_xacnhan,
      'hoptacxa_xacnhan'=> $hopDongMuaBan->hoptacxa_xacnhan,
      'created_at'=> $hopDongMuaBan->created_at,
      'updated_at'=> $hopDongMuaBan->updated_at,
      'id_gionglua'=> $hopDongMuaBan->id_gionglua,
      'name_gionglua'=> $hopDongMuaBan->name_gionglua,
      'id_danhmucquydinh'=> $hopDongMuaBan->id_danhmucquydinh,
      'name_danhmucquydinh'=> $hopDongMuaBan->name_danhmucquydinh,
      'id_lichmuavu'=> $hopDongMuaBan->id_lichmuavu,
      'name_lichmuavu'=> $hopDongMuaBan->name_lichmuavu,
      'id_thuonglai'=>$thuonglai->id_thuonglai,
      'name_thuonglai'=>$thuonglai->name_thuonglai,
      'phone_number_thuonglai'=>$thuonglai->phone_number,
      'address_thuonglai'=>$thuonglai->address,
      'daidien_thuonglai'=>$thuonglai->fullname,
      'id_hoptacxa'=>$hoptacxa->id_hoptacxa,
      'name_hoptacxa'=>$hoptacxa->id_hoptacxa,
      'id_hoptacxa'=>$hoptacxa->id_hoptacxa,
      'name_hoptacxa'=>$hoptacxa->name_hoptacxa,
      'phone_number_hoptacxa'=>$hoptacxa->phone_number,
      'address_hoptacxa'=>$hoptacxa->address,
      'daidien_hoptacxa'=>$chunhiem->fullname,
    ]);
   } catch (\Exception $error) {
    Session::flash('error', 'Không lấy được thông tin hợp đồng ');
    return false;
   }

  }

  public function getListHopDong($request){
   
    $id = null;
    $who = "";

    $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
    $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
    
    if($id_thuonglai == null && $id_hoptacxa == null){
      Session::flash('error', 'Không xác định được chủ thể');
      return false;
    }
    
    if($id_thuonglai != false){
      $id = $id_thuonglai;
      $who = "tbl_thuonglai.id_thuonglai";
    }

    if($id_hoptacxa != false){
      $id = $id_hoptacxa;
      $who = "tbl_hoptacxa.id_hoptacxa";
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
        $order = "id_hopdongmuaban";
    }
    if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
        $sort = "desc";
    }

    try {
        $data = HopDongMuaBan::join('tbl_thuonglai', 'tbl_thuonglai.id_thuonglai', '=', 'tbl_hopdongmuaban.id_thuonglai')
        ->join('tbl_hoptacxa', 'tbl_hoptacxa.id_hoptacxa', '=', 'tbl_hopdongmuaban.id_hoptacxa')
        ->join('tbl_gionglua', 'tbl_gionglua.id_gionglua', '=', 'tbl_hopdongmuaban.id_gionglua')
        ->Who($who, $id)
        ->Status($request)
        ->Search($request);
      
        $total = $data->count();
        $meta = $this->commonService->pagination($total,$page,$limit);
        $result = $data
        ->skip(($page-1)*$limit)
        ->take($limit)
        ->orderBy($order, $sort)
        ->get(['id_hopdongmuaban','name_thuonglai','name_hoptacxa', 'title_hopdongmuaban','name_gionglua' ,'status']);
        
    

        if($result != []){
          return [$result,$meta];
        }
        Session::flash('error', 'Danh sách hợp đồng rỗng !');
        return false;
      } catch (\Exception $error) {
          Session::flash('error', 'Không lấy được danh sách hợp đồng');
          return false;
      }


  }

  public function createHopDongMuaBan($request){
    $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
    $id_hoptacxa = $request->id_hoptacxa;
    $id_lichmuavu = $request->id_lichmuavu;
    $id_danhmucquydinh = $request->id_danhmucquydinh;
    $id_gionglua = $request->id_gionglua;
    $price = $request->price;
    $title_hopdongmuaban = $request->title_hopdongmuaban;
    $description_hopdongmuaban = $request->description_hopdongmuaban;
    $thuonglai_xacnhan = 1;
    $hoptacxa_xacnhan = 0;
    $status = 'waiting'; //waiting - confirm - hoptacxa-update - thuonglai-update

    if($id_thuonglai == false){
      Session::flash('error', 'Bạn không phải là thương lái, không thể tạo hợp đồng');
      return false;
    }

    if($price < 0){
      Session::flash('error', 'Giá thua mua không được nhỏ hơn 0');
      return false;
    }


    $isHopDongExistWithCurrentThuongLai = HopDongMuaBan::where('id_thuonglai', $id_thuonglai)
                      ->where('id_hoptacxa', $id_hoptacxa)
                      ->where('id_lichmuavu', $id_lichmuavu)
                      ->count();
                
    if($isHopDongExistWithCurrentThuongLai != 0){
      Session::flash('error', 'Hợp đồng đã tồn tại, vui lòng thay đổi hoặc kiểm tra thông tin');
      return false;
    }

    $isHopDongExistWithOrtherThuongLai = HopDongMuaBan::where('id_hoptacxa', $id_hoptacxa)
                      ->where('id_lichmuavu', $id_lichmuavu)
                      ->count();
                
    if($isHopDongExistWithOrtherThuongLai != 0){
      Session::flash('error', 'Hợp tác xã với lịch mùa vụ này đã có hợp đồng với thương lái khác, vui lòng thay đổi hoặc kiểm tra thông tin');
      return false;
    }

    try {
      DB::beginTransaction();
      
      $hopDongMuaBan = HopDongMuaBan::create([
        'id_thuonglai' => $id_thuonglai,
        'id_hoptacxa' => $id_hoptacxa,
        'id_lichmuavu' => $id_lichmuavu,
        'id_danhmucquydinh' => $id_danhmucquydinh,
        'id_gionglua' => $id_gionglua,
        'title_hopdongmuaban' => $title_hopdongmuaban,
        'price' => $price,
        'description_hopdongmuaban' => $description_hopdongmuaban,
        'thuonglai_xacnhan' => $thuonglai_xacnhan,
        'hoptacxa_xacnhan' => $hoptacxa_xacnhan,
        'status' => $status
      ]);
      DB::commit();

      if($hopDongMuaBan != null){
        $thuonglai = ThuongLai::where('id_thuonglai', $id_thuonglai)->first();
        $message = "Bạn vừa được tạo một hợp đồng với thương lái: " . $thuonglai->name_thuonglai;
        $user = $this->hopTacXaService->getChuNhiemHTX($id_hoptacxa)->id_user;
        $status_notify = 0;
        $link = "/hopdongmuaban";
        $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
        $this->notificationService->sendNotificationService($notify->id);
      }

      return $hopDongMuaBan;
    } catch (\Exception $error) {
      DB::rollBack();
      Session::flash('error', 'Không thể tạo hợp đồng.');
      return false;
    }

  }


  public function updateHopDong($request){
    try {

    $id_hopdongmuaban = $request->id_hopdongmuaban;

    $id_chuthe = null;
    $who = "";

    $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
    $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
    
    if($id_thuonglai == null && $id_hoptacxa == null){
      Session::flash('error', 'Không xác định được chủ thể');
      return false;
    }
    
    if($id_thuonglai != false){
      $thuonglai_xacnhan = 1;
      $hoptacxa_xacnhan = 0;
      $status = 'thuonglai-update'; //waiting - confirm - hoptacxa-update - thuonglai-update

      $id_chuthe = $id_thuonglai;
      $who = "id_thuonglai";


    }

    if($id_hoptacxa != false){
      $thuonglai_xacnhan = 0;
      $hoptacxa_xacnhan = 1;
      $status = 'hoptacxa-update'; //waiting - confirm - hoptacxa-update - thuonglai-update

      $id_chuthe = $id_hoptacxa;
      $who = "id_hoptacxa";



      $checkXaVienIsChuNhiemHTX = $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa);
      if($checkXaVienIsChuNhiemHTX == false){
        Session::flash('error', 'Bạn không phải là chủ nhiệm hợp tác xã, không thể chỉnh sữa hợp đồng');
        return false;
      }

    }

      $id_lichmuavu = $request->id_lichmuavu;
      $id_danhmucquydinh = $request->id_danhmucquydinh;
      $id_gionglua = $request->id_gionglua;
      $title_hopdongmuaban = $request->title_hopdongmuaban;
      $description_hopdongmuaban = $request->description_hopdongmuaban;
      $price = $request->price;

      if($price < 0){
        Session::flash('error', 'Giá thua mua không được nhỏ hơn 0');
        return false;
      }

      $hopDongMuaBan = HopDongMuaBan::where('id_hopdongmuaban', $id_hopdongmuaban)
                      ->Who($who, $id_chuthe)
                      ->first();

      if($hopDongMuaBan == null){
        Session::flash('error', 'Hợp đồng mua bán không tồn tại');
        return false;
      }

      if($hopDongMuaBan->status == 'confirm'){
        Session::flash('error', 'Hợp đồng đã được xác nhận bới 2 bên, không thể chỉnh sửa !');
        return false;
      }

      $lichmuavu = LichMuaVu::where('id_licmuavu', $hopDongMuaBan->id_lichmuavu)->first();
      if($lichmuavu->status == 'finish'){
        Session::flash('error', 'Lịch mùa vụ trong hợp đồng đã kết thúc, không thể chỉnh sửa !');
        return false;
      }
      $first_date = strtotime(now());
      $second_date = strtotime($hopDongMuaBan->created_at);
      $datediff = abs($first_date - $second_date);
      $ruleDate = $datediff / (60*60*24);

      if(($ruleDate >= 30.0)){
        if($hopDongMuaBan->status != 'confirm'){
          DB::beginTransaction();
          $hopDongMuaBan->delete();
          DB::commit();
          Session::flash('error', 'Hợp đồng đã bị xóa vì quá hạn xác nhận 30 ngày kể từ ngày tạo hợp đồng');
          return false;
        }
      }
  
      DB::beginTransaction();
      $hopDongMuaBan->id_danhmucquydinh =  $id_danhmucquydinh;
      $hopDongMuaBan->title_hopdongmuaban =  $title_hopdongmuaban;
      $hopDongMuaBan->price =  $price;
      $hopDongMuaBan->description_hopdongmuaban =  $description_hopdongmuaban;
      $hopDongMuaBan->thuonglai_xacnhan =  $thuonglai_xacnhan;
      $hopDongMuaBan->hoptacxa_xacnhan =  $hoptacxa_xacnhan;
      $hopDongMuaBan->status =  $status;
      $hopDongMuaBan->updated_at = now();
      $hopDongMuaBan->save();
      DB::commit();

      
      if($hopDongMuaBan != null){
        switch ($who) {
          case 'id_thuonglai':
            $message = "Hợp đồng số ". $id_hopdongmuaban . " vừa được cập nhật bởi thương lái ";
            $user = $this->hopTacXaService->getChuNhiemHTX($hopDongMuaBan->id_hoptacxa)->id_user;
            break;
          
          case 'id_hoptacxa':
            $message = "Hợp đồng số ". $id_hopdongmuaban . " vừa được cập nhật bởi hợp tác xã ";
            $user = ThuongLai::where('id_thuonglai', $hopDongMuaBan->id_thuonglai)->first()->id_user;
            break;
          
          default:
          return false;
            break;
        }
        $status_notify = 0;
        $link = "/hopdongmuaban";
        $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
        $this->notificationService->sendNotificationService($notify->id);
      }

      return $this->getDetailHopDong($id_hopdongmuaban);

    } catch (\Exception $error) {
      DB::rollBack();
      Session::flash('error', 'Không thể cập nhật hợp đồng.');
      return false;
    }
  }

  public function deleteHopDong($id_hopdongmuaban){

    try {

      $id_chuthe = null;
      $who = "";

      $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
      $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

      if($id_thuonglai == null && $id_hoptacxa == null){
        Session::flash('error', 'Không xác định được chủ thể');
        return false;
      }

      if($id_thuonglai != false){
        $id_chuthe = $id_thuonglai;
        $who = "id_thuonglai";
      }
  
      if($id_hoptacxa != false){
        $id_chuthe = $id_hoptacxa;
        $who = "id_hoptacxa";
  
        $checkXaVienIsChuNhiemHTX = $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa);
        if($checkXaVienIsChuNhiemHTX == false){
          Session::flash('error', 'Bạn không phải là chủ nhiệm hợp tác xã, không thể xóa hợp đồng');
          return false;
        }
      }
  
      $hopDongMuaBan = HopDongMuaBan::where('id_hopdongmuaban', $id_hopdongmuaban)
      ->Who($who, $id_chuthe)
      ->first();
  
      if($hopDongMuaBan == null){
        Session::flash('error', 'Hợp đồng mua bán không tồn tại');
        return false;
      }
  
       if($hopDongMuaBan->status == 'confirm'){
        Session::flash('error', 'Hợp đồng đã được kích hoạt không thể xóa');
        return false;
      }
      DB::beginTransaction();
      $hopDongMuaBan->delete();
      DB::commit();

      if($hopDongMuaBan != null){
        switch ($who) {
          case 'id_thuonglai':
            $thuonglai = ThuongLai::where('id_thuonglai', $id_thuonglai)->first();
            $message = "Hợp đồng số ". $id_hopdongmuaban . " đã bị hủy và xóa bởi thương lái: ". $thuonglai->name_thuonglai;
            $user = $this->hopTacXaService->getChuNhiemHTX($hopDongMuaBan->id_hoptacxa)->id_user;
            break;
          
          case 'id_hoptacxa':
            $hoptacxa = HopTacXa::where('id_hoptacxa', $id_hoptacxa)->first();
            $message = "Hợp đồng số ". $id_hopdongmuaban . " đã bị từ chối xóa bởi hợp tác xã: ". $hoptacxa->name_hoptacxa;
            $user = ThuongLai::where('id_thuonglai', $hopDongMuaBan->id_thuonglai)->first()->id_user;
            break;
          
          default:
          return false;
            break;
        }
        $status_notify = 0;
        $link = "/hopdongmuaban";
        $notify = $this->notificationService->createNotificationService($message, $status_notify,$user,$link);
        $this->notificationService->sendNotificationService($notify->id);
      }

      return true;
    } catch (\Exception $error) {
      DB::rollBack();
      Session::flash('error', 'Xóa hợp đồng mua bán không thành công');
      return false;
    }

  }

}