<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\GiaoDichMuaBanLua;
use App\Models\LichMuaVu;
use App\Models\ThuongLai;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GiaoDichMuaBanLuaService
{

    protected $xaVienService;
    protected $commonService;
    protected $hopTacXaService;
    protected $notificationService;
    protected $uploadImageService;
    protected $thuongLaiService;

    public function __construct(
        XaVienService $xaVienService,
        HopTacXaService $hopTacXaService,
        ThuongLaiService $thuongLaiService,
        NotificationService $notificationService,
        CommonService $commonService,
        UploadImageService $uploadImageService
    ) {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
        $this->thuongLaiService = $thuongLaiService;
        $this->uploadImageService = $uploadImageService;
    }

    public function getDetailGiaoDichMuaBanLua($id_giaodichmuabanlua)
    {
        try {
            $giaodich = GiaoDichMuaBanLua::where('tbl_giaodichmuaban_lua.id_giaodichmuaban_lua', $id_giaodichmuabanlua)
                ->join('tbl_hoptacxa', 'tbl_hoptacxa.id_hoptacxa', 'tbl_giaodichmuaban_lua.id_hoptacxa')
                ->join('tbl_xavien', 'tbl_xavien.id_xavien', 'tbl_giaodichmuaban_lua.id_xavien')
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_thuonglai', 'tbl_thuonglai.id_thuonglai','tbl_giaodichmuaban_lua.id_thuonglai')
                ->join('tbl_user as tbl_user_thuonglai', 'tbl_user_thuonglai.id_user', 'tbl_thuonglai.id_user')
                ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', 'tbl_giaodichmuaban_lua.id_lichmuavu')
                ->join('tbl_gionglua', 'tbl_gionglua.id_gionglua', 'tbl_lichmuavu.id_gionglua')
                ->select(
                    'tbl_giaodichmuaban_lua.*',
                    'tbl_hoptacxa.name_hoptacxa',
                    'tbl_user.fullname as name_xavien',
                    'tbl_user.phone_number as phone_number_xavien',
                    'tbl_thuonglai.name_thuonglai as name_thuonglai',
                    'tbl_user_thuonglai.phone_number as phone_number_thuonglai',
                    'tbl_lichmuavu.name_lichmuavu',
                    'tbl_lichmuavu.date_start',
                    'tbl_lichmuavu.date_end',
                    'tbl_gionglua.name_gionglua',
                )
                ->first();
            if ($giaodich == null) {
                Session::flash('error', 'Giao dịch mua bán không tồn tại');
                return false;
            }

            return $giaodich;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không lấy được thông tin giao dịch mua bán lúa');
            return false;
        }
    }
   
    public function getListGiaoDichMuaBanLua($request)
    {
        $id = null;
        $who = "";

        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
        if ($id_xavien != false) {
            $id = $id_xavien;
            $who = "tbl_xavien.id_xavien";
        }

        if ($id_thuonglai != false) {
            $id = $id_thuonglai;
            $who = "tbl_thuonglai.id_thuonglai";
        }

        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;

        if ($page == null || $page == 0 || $page < 0) {
            $page = 1;
        }
        if ($limit == null || $limit == 0 || $limit < 0) {
            $limit = 15;
        }
        if ($search == null) {
            $search = "";
        }
        if ($order == null || $order == "") {
            $order = "id_giaodichmuaban_lua";
        }
        if ($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")) {
            $sort = "desc";
        }

        try {
            $data = GiaoDichMuaBanLua::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodichmuaban_lua.id_lichmuavu')
                ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodichmuaban_lua.id_xavien')
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_thuonglai', 'tbl_thuonglai.id_thuonglai', '=', 'tbl_giaodichmuaban_lua.id_thuonglai')
                ->Who($who, $id)
                ->select(
                    "tbl_giaodichmuaban_lua.id_giaodichmuaban_lua",
                    "tbl_giaodichmuaban_lua.name_lohang",
                    "tbl_lichmuavu.name_lichmuavu",
                    "tbl_user.fullname as name_xavien",
                    "tbl_thuonglai.name_thuonglai",
                    "tbl_giaodichmuaban_lua.img_lohang",
                    "tbl_giaodichmuaban_lua.soluong",
                    "tbl_giaodichmuaban_lua.price",
                    "tbl_giaodichmuaban_lua.status",
                    "tbl_giaodichmuaban_lua.hoptacxa_xacnhan",
                    "tbl_giaodichmuaban_lua.thuonglai_xacnhan",
                    "tbl_giaodichmuaban_lua.xavien_xacnhan"
                );

            $total = $data->count();
            $meta = $this->commonService->pagination($total, $page, $limit);
            $result = $data
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->orderBy($order, $sort)
                ->get();



            if ($result != []) {
                return [$result, $meta];
            }
            Session::flash('error', 'Danh sách giao dịch mua bán lúa !');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách mua bán lúa' . $error);
            return false;
        }
    }

    public function getListGiaoDichMuaBanLuaForHTX($request)
    {
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        if (!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)) {
            Session::flash('error', 'Bạn không có quyền quản trị để xem danh mục này');
            return false;
        }

        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;

        if ($page == null || $page == 0 || $page < 0) {
            $page = 1;
        }
        if ($limit == null || $limit == 0 || $limit < 0) {
            $limit = 15;
        }
        if ($search == null) {
            $search = "";
        }
        if ($order == null || $order == "") {
            $order = "id_giaodichmuaban_lua";
        }
        if ($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")) {
            $sort = "desc";
        }

        try {
            $data = GiaoDichMuaBanLua::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodichmuaban_lua.id_lichmuavu')
            ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodichmuaban_lua.id_xavien')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->join('tbl_thuonglai', 'tbl_thuonglai.id_thuonglai', '=', 'tbl_giaodichmuaban_lua.id_thuonglai')
                ->where('tbl_giaodichmuaban_lua.id_hoptacxa', $id_hoptacxa)
                ->select(
                    "tbl_giaodichmuaban_lua.id_giaodichmuaban_lua",
                    "tbl_giaodichmuaban_lua.name_lohang",
                    "tbl_lichmuavu.name_lichmuavu",
                    "tbl_user.fullname as name_xavien",
                    "tbl_thuonglai.name_thuonglai",
                    "tbl_giaodichmuaban_lua.img_lohang",
                    "tbl_giaodichmuaban_lua.soluong",
                    "tbl_giaodichmuaban_lua.price",
                    "tbl_giaodichmuaban_lua.status",
                    "tbl_giaodichmuaban_lua.hoptacxa_xacnhan",
                    "tbl_giaodichmuaban_lua.thuonglai_xacnhan",
                    "tbl_giaodichmuaban_lua.xavien_xacnhan"
                );

            $total = $data->count();
            $meta = $this->commonService->pagination($total, $page, $limit);
            $result = $data
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->orderBy($order, $sort)
                ->get();



            if ($result != []) {
                return [$result, $meta];
            }
            Session::flash('error', 'Danh sách giao dịch mua bán lúa!');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách giao dịch' .$error);
            return false;
        }
    }

    public function createGiaoDichMuaBanLua (
        $id_thuonglai,
        $id_xavien,
        $id_hoptacxa,
        $id_lichmuavu,
        $name_xavien,
        $name_thuonglai,
        $price,
    ){
        try {

            $lichmuavu = LichMuaVu::where('id_lichmua', $id_lichmuavu)->first();

            $giaodichmuabanlua = GiaoDichMuaBanLua::create([
                'id_thuonglai'=>$id_thuonglai,
                'id_xavien'=>$id_xavien,
                'id_hoptacxa'=>$id_hoptacxa,
                'id_lichmuavu'=>$id_lichmuavu,
                'status'=>0,
                'hoptacxa_xacnhan'=>0,
                'thuonglai_xacnhan'=>0,
                'xavien_xacnhan'=>0,
                'description_giaodich'=>'Giao dịch vừa được tạo chưa có mô tả chi tiết',
                'name_lohang'=>"Giao dịch mua bán lúa giữa xã viên $name_xavien và thương lái $name_thuonglai, mùa vụ : $lichmuavu->name_lichmuavu",
                'price'=>$price,
                'soluong'=>0,
            ]);
            if($giaodichmuabanlua == null){
                Session::flash('error', 'Tạo giao dịch mua bán lúa không thành công');
                return false;
            }
            return $giaodichmuabanlua;
        } catch (\Exception $error) {
            Session::flash('error', 'Không thể tạo giao dịch mua bán lúa cho tất cả các xã viên' . $error);
            return $error;
        }
    }


    
    public function updateGiaoDichMuaBanLua($request)
    {
        try {
            $id_user = $this->commonService->getIDByToken();
            $account_type = $this->commonService->getAccountTypeByToken();
            $who = "";
            $id = "";

            switch ($account_type) {
                case 'farmer':
                    $id_xavien = $this->xaVienService->getIdXaVienByToken();
                    $thuonglai_xacnhan = 0;
                    $xavien_xacnhan = 1;
                    $who = "id_xavien";
                    $id = $id_xavien;
                    break;

                case 'trader':
                    $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
                    $thuonglai_xacnhan = 1;
                    $xavien_xacnhan = 0;
                    $who = "id_thuonglai";
                    $id = $id_thuonglai;
                    break;

                default:
                    Session::flash('error', 'Không xác định được chủ thể');
                    return false;
                    break;
            }


            $id_giaodichmuabanlua = $request->id_giaodichmuabanlua;

            $giaodichmuabanlua = GiaoDichMuaBanLua::where('id_giaodichmuaban_lua', $id_giaodichmuabanlua)
                ->Who($who, $id)
                ->first();
            if ($giaodichmuabanlua == null) {
                Session::flash('error', 'Giao dịch không tồn tại');
                return false;
            }

            if ($giaodichmuabanlua->status == 1) {
                Session::flash('error', 'Giao dịch đã được xác nhận không thể chỉnh sửa !');
                return false;
            }

            $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanlua->id_lichmuavu)->first();
            if( $lichmuavu->status == 'finish'){
                Session::flash('error', 'Không thể cập nhật hoạt động của mùa vụ đã kết thúc');
                return false;
            }

            if($request->soluong < 0){
                Session::flash('error', 'Số lượng không được nhỏ hơn 0');
                return false;
            }

            $giaodichmuabanlua->soluong = $request->soluong;
            $giaodichmuabanlua->description_giaodich = $request->description_giaodich;
            $img_lohang = null;
            if ($request->hasFile('img_lohang')) {
                if ($giaodichmuabanlua->img_lohang != null) {
                    $this->uploadImageService->delete($giaodichmuabanlua->img_lohang);
                }
                $giaodichmuabanlua->img_lohang = $this->uploadImageService->store($request->img_lohang);
            }
            $giaodichmuabanlua->xavien_xacnhan = $xavien_xacnhan;
            $giaodichmuabanlua->thuonglai_xacnhan = $thuonglai_xacnhan;
            $giaodichmuabanlua->save();
            if ($giaodichmuabanlua != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao dịch mua bán lúa giống số " . $giaodichmuabanlua->id_giaodichmuaban_lua . " vừa được cập nhật bởi xã viên " . $xavien->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = ThuongLai::where('id_thuonglai', $giaodichmuabanlua->id_thuonglai)->first()->id_user;
                        break;

                    case 'trader':
                        $thuonglai = ThuongLai::where('tbl_thuonglai.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_thuonglai.id_user')->first();
                        $message = "Giao dịch mua bán lúa giống số " . $giaodichmuabanlua->id_giaodichmuaban_lua . " vừa được cập nhật bởi thương lái " . $thuonglai->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = XaVien::where('id_xavien', $giaodichmuabanlua->id_xavien)->first()->id_user;
                        break;

                    default:
                        break;
                }
                $status_notify = 0;
                $link = "/giaodichmuabanlua";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $user, $link);
                $this->notificationService->sendNotificationService($notify->id);
            }
            DB::commit();
            return $this->getDetailgiaodichmuabanlua($giaodichmuabanlua->id_giaodichmuaban_lua);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không cập nhật được giao dịch mua bán lúa giống' . $error);
            return false;
        }
    }

    public function confirmGiaoDichMuaBanLua($request)
    {
        $id_giaodichmuabanlua = $request->id_giaodichmuabanlua;
        $id_user = $this->commonService->getIDByToken();

        $account_type = $this->commonService->getAccountTypeByToken();
        $who = "";
        $id = "";

        switch ($account_type) {
            case 'farmer':
                $id_xavien = $this->xaVienService->getIdXaVienByToken();
                $id_hoptacxa = XaVien::where('id_xavien', $id_xavien)->first()->id_hoptacxa;
                $who = "id_xavien";
                $id = $id_xavien;

                break;

            case 'trader':
                $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
                $who = "id_thuonglai";
                $id = $id_thuonglai;
                break;

            default:
                Session::flash('error', 'Không xác định được chủ thể');
                return false;
                break;
        }

        $giaodichmuabanlua = GiaoDichMuaBanLua::where('id_giaodichmuaban_lua', $id_giaodichmuabanlua)
            ->Who($who, $id)
            ->first();

        if ($giaodichmuabanlua == null) {
            Session::flash('error', 'Giao dịch không tồn tại');
            return false;
        }

        if ($giaodichmuabanlua->status == 1) {
            Session::flash('error', 'Giao dịch đã được xác nhận không thể thay đổi trạng thái !');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanlua->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Không thể xác nhận hoạt động của mùa vụ đã kết thúc');
            return false;
        }

        try {
            DB::beginTransaction();
            switch ($account_type) {
                case 'farmer':
                    if ($giaodichmuabanlua->xavien_xacnhan == 1) {
                        $giaodichmuabanlua->xavien_xacnhan = 0;
                    } else {
                        $giaodichmuabanlua->xavien_xacnhan = 1;
                    }
                    $giaodichmuabanlua->save();
                    break;

                case 'trader':
                    if ($giaodichmuabanlua->thuonglai_xacnhan == 1) {
                        $giaodichmuabanlua->thuonglai_xacnhan = 0;
                    } else {
                        $giaodichmuabanlua->thuonglai_xacnhan = 1;
                    }
                    $giaodichmuabanlua->save();
                    break;

                default:
                    Session::flash('error', 'Không xác định được trạng thái');
                    return false;
                    break;
            }


            if ($giaodichmuabanlua != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao dịch mua bán lúa số " . $giaodichmuabanlua->id_giaodichmuaban_lua . " vừa được xác nhận bởi xã viên " . $xavien->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = ThuongLai::where('id_thuonglai', $giaodichmuabanlua->id_thuonglai)->first()->id_user;
                        break;

                    case 'trader':
                        $thuonglai = ThuongLai::where('tbl_thuonglai.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_thuonglai.id_user')->first();
                        $message = "Giao dịch mua bán lúa số " . $giaodichmuabanlua->id_giaodichmuaban_lua . " vừa được xác nhận bởi nhà cung cấp vật tư " . $thuonglai->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = XaVien::where('id_xavien', $giaodichmuabanlua->id_xavien)->first()->id_user;
                        break;

                    default:
                        break;
                }
                $status_notify = 0;
                $link = "/giaodichmuabanlua";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $user, $link);
                $this->notificationService->sendNotificationService($notify->id);
            }

            DB::commit();
            return $this->getDetailgiaodichmuabanlua($giaodichmuabanlua->id_giaodichmuaban_lua);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thay đổi được trạng thái'. $error);
            return false;
        }
    }

    public function approveGiaoDichMuaBanLua($request)
    {
        $id_giaodichmuabanlua = $request->id_giaodichmuabanlua;
        $hoptacxa_xacnhan = $request->hoptacxa_xacnhan;
        $id_user = $this->commonService->getIDByToken();
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(! $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền duyệt giao dịch');
            return false;
        }


        $giaodichmuabanlua = GiaoDichMuaBanLua::where('id_giaodichmuaban_lua', $id_giaodichmuabanlua)
            ->where('id_hoptacxa', $id_hoptacxa)
            ->first();

        if ($giaodichmuabanlua == null) {
            Session::flash('error', 'Giao dịch không tồn tại');
            return false;
        }

        if ($giaodichmuabanlua->status == 1) {
            Session::flash('error', 'Giao dịch đã được xác nhận không thể thay đổi trạng thái !');
            return false;
        }

        if($giaodichmuabanlua->xavien_xacnhan == 0){
            Session::flash('error', 'Giao dịch chưa được xác nhận bởi xã viên không thể thay đổi trạng thái !');
            return false;
        }
        if($giaodichmuabanlua->nhacungcap_xacnhan == 0){
            Session::flash('error', 'Giao dịch chưa được nhà cung cấp vật tư bởi không thể thay đổi trạng thái !');
            return false;
        }

        if(in_array($hoptacxa_xacnhan, [0,1]) == false){
            Session::flash('error', 'Không xác định được trạng thái');
            return false;
        }
        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanlua->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Không thể xác nhận hoạt động của mùa vụ đã kết thúc');
            return false;
        }
        
        try {
            DB::beginTransaction();
            $giaodichmuabanlua->hoptacxa_xacnhan = $hoptacxa_xacnhan;
            if($hoptacxa_xacnhan == 1){
                $giaodichmuabanlua->status = 1;
            }
           
            $giaodichmuabanlua->save();

            if($giaodichmuabanlua != null){
                $message = "Giao dịch mua bán lúa số $giaodichmuabanlua->id_giaodichmuaban_lua đã được duyệt bởi chủ nhiệm hợp tác xã";
                $status_notify = 0;
                $link = "/giaodichmuabanlua";
                $id_user_xavien = XaVien::where('id_xavien', $giaodichmuabanlua->id_xavien)->first()->id_user;
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$id_user_xavien,$link);
                $this->notificationService->sendNotificationService($notify->id);

                $id_user_nhacungcap = ThuongLai::where('id_thuonlai', $giaodichmuabanlua->id_thuonlai)->first()->id_user;
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$id_user_nhacungcap,$link);
                $this->notificationService->sendNotificationService($notify->id);
            }

            DB::commit();
            return $this->getDetailgiaodichmuabanlua($giaodichmuabanlua->id_giaodichmuaban_lua);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thay đổi được trạng thái');
            return false;
        }
    }

}
