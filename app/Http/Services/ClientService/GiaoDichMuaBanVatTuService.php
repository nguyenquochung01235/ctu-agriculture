<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\GiaoDichMuaBanVatTu;
use App\Models\LichMuaVu;
use App\Models\NhaCungCapVatTu;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GiaoDichMuaBanVatTuService
{

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
    ) {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
        $this->nhaCungCapVatTuService = $nhaCungCapVatTuService;
        $this->uploadImageService = $uploadImageService;
    }


    public function getDetailGiaoDichMuaBanVatTu($id_giaodichmuabanvattu)
    {
        try {
            $giaodich = GiaoDichMuaBanVatTu::where('tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
                ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', 'tbl_giaodichmuaban_vattu.id_lichmuavu')
                ->join('tbl_hoptacxa', 'tbl_hoptacxa.id_hoptacxa', 'tbl_giaodichmuaban_vattu.id_hoptacxa')
                ->join('tbl_category_vattu', 'tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')
                ->select(
                    "tbl_giaodichmuaban_vattu.*",
                    "tbl_lichmuavu.name_lichmuavu",
                    "tbl_hoptacxa.name_hoptacxa",
                    "tbl_hoptacxa.phone_number",
                    "tbl_giaodichmuaban_vattu.id_category_vattu",
                    "tbl_category_vattu.name_category_vattu"
                )
                ->first();

            if ($giaodich == null) {
                Session::flash('error', 'Giao dịch mua bán không tồn tại');
                return false;
            }

            $xavien = XaVien::where('tbl_xavien.id_xavien', $giaodich->id_xavien)
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->first();

            if ($xavien == null) {
                Session::flash('error', 'Không lấy được thông tin xã viên');
                return false;
            }

            $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_nhacungcapvattu', $giaodich->id_nhacungcapvattu)
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')
                ->first();

            if ($nhacungcapvattu == null) {
                Session::flash('error', 'Không lấy được thông tin nhà cung cấp vật tư');
                return false;
            }

            return $result = ([
                "id_giaodichmuaban_vattu" => $giaodich->id_giaodichmuaban_vattu,
                "id_lichmuavu" => $giaodich->id_lichmuavu,
                "name_lichmuavu" => $giaodich->name_lichmuavu,
                "id_category_vattu" => $giaodich->id_category_vattu,
                "name_category_vattu" => $giaodich->id_category_vattu,
                "img_lohang" => $giaodich->img_lohang,
                "soluong" => $giaodich->soluong,
                "price" => $giaodich->price,
                "status" => $giaodich->status,
                "hoptacxa_xacnhan" => $giaodich->hoptacxa_xacnhan,
                "nhacungcap_xacnhan" => $giaodich->nhacungcap_xacnhan,
                "xavien_xacnhan" => $giaodich->xavien_xacnhan,
                "description_giaodich" => $giaodich->description_giaodich,
                "created_at" => $giaodich->created_at,
                "updated_at" => $giaodich->updated_at,
                "id_xavien" => $giaodich->id_xavien,
                "name_xavien" => $xavien->fullname,
                "xavien_phone_number" => $xavien->phone_number,
                "name_hoptacxa" => $giaodich->name_hoptacxa,
                "hoptacxa_phone_number" => $giaodich->phone_number,
                "id_nhacungcapvattu" => $giaodich->id_nhacungcapvattu,
                "nhacungcapvattu_name" => $nhacungcapvattu->name_daily,
                "nhacungcapvattun_phone_number" => $nhacungcapvattu->phone_number
            ]);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không lấy được thông tin giao dịch mua bán vật tư');
            return false;
        }
    }


    public function getListGiaoDichMuaBanVatTu($request)
    {
        $id = null;
        $who = "";

        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
        if ($id_xavien != false) {
            $id = $id_xavien;
            $who = "tbl_xavien.id_xavien";
        }

        if ($id_nhacungcapvattu != false) {
            $id = $id_nhacungcapvattu;
            $who = "tbl_nhacungcapvattu.id_nhacungcapvattu";
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
            $order = "id_giaodichmuaban_vattu";
        }
        if ($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")) {
            $sort = "desc";
        }

        try {
            $data = GiaoDichMuaBanVatTu::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodichmuaban_vattu.id_lichmuavu')
                ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodichmuaban_vattu.id_xavien')
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_nhacungcapvattu', 'tbl_nhacungcapvattu.id_nhacungcapvattu', '=', 'tbl_giaodichmuaban_vattu.id_nhacungcapvattu')
                ->join('tbl_category_vattu', 'tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')
                ->Who($who, $id)
                ->select(
                    "tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu",
                    "tbl_lichmuavu.name_lichmuavu",
                    "tbl_category_vattu.name_category_vattu",
                    "tbl_user.fullname as name_xavien",
                    "tbl_nhacungcapvattu.name_daily",
                    "tbl_giaodichmuaban_vattu.img_lohang",
                    "tbl_giaodichmuaban_vattu.soluong",
                    "tbl_giaodichmuaban_vattu.status",
                    "tbl_giaodichmuaban_vattu.hoptacxa_xacnhan",
                    "tbl_giaodichmuaban_vattu.nhacungcap_xacnhan",
                    "tbl_giaodichmuaban_vattu.xavien_xacnhan"
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
            Session::flash('error', 'Danh sách giao dịch mua bán vật tư !');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách giao dịch mua bán vật tư'. $error);
            return false;
        }
    }

    public function getListGiaoDichMuaBanVatTuForHTX($request)
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
            $order = "id_giaodichmuaban_vattu";
        }
        if ($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")) {
            $sort = "desc";
        }

        try {
            $data = GiaoDichMuaBanVatTu::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodichmuaban_vattu.id_lichmuavu')
                ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodichmuaban_vattu.id_xavien')
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_nhacungcapvattu', 'tbl_nhacungcapvattu.id_nhacungcapvattu', '=', 'tbl_giaodichmuaban_vattu.id_nhacungcapvattu')
                ->join('tbl_category_vattu', 'tbl_category_vattu.id_category_vattu', 'tbl_giaodichmuaban_vattu.id_category_vattu')
                ->where('tbl_giaodichmuaban_vattu.id_hoptacxa', $id_hoptacxa)
                ->select(
                    "tbl_giaodichmuaban_vattu.id_giaodichmuaban_vattu",
                    "tbl_lichmuavu.name_lichmuavu",
                    "tbl_category_vattu.name_category_vattu",
                    "tbl_user.fullname as name_xavien",
                    "tbl_nhacungcapvattu.name_daily",
                    "tbl_giaodichmuaban_vattu.img_lohang",
                    "tbl_giaodichmuaban_vattu.soluong",
                    "tbl_giaodichmuaban_vattu.status",
                    "tbl_giaodichmuaban_vattu.hoptacxa_xacnhan",
                    "tbl_giaodichmuaban_vattu.nhacungcap_xacnhan",
                    "tbl_giaodichmuaban_vattu.xavien_xacnhan"
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
            Session::flash('error', 'Danh sách giao dịch mua bán vật tư !');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách giao dịch mua bán vật tư');
            return false;
        }
    }

    public function createGiaoDichMuaBanVatTu($request){
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
                    $id_xavien = $request->id_xavien;
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
            $id_category_vattu = $request->id_category_vattu;
            $soluong = $request->soluong;
            $price = $request->price;
            $status = 0;
            $description_giaodich = $request->description_giaodich;
            $hoptacxa_xacnhan = 0;
            $img_lohang = null;
            if ($request->hasFile('img_lohang')) {
                $img_lohang = $this->uploadImageService->store($request->img_lohang);
            }

            DB::beginTransaction();
            $giaodichmuabanvattu = GiaoDichMuaBanVatTu::create([
                'id_xavien' => $id_xavien,
                'id_hoptacxa' => $id_hoptacxa,
                'id_nhacungcapvattu' => $id_nhacungcapvattu,
                'id_lichmuavu' => $id_lichmuavu,
                'id_category_vattu' => $id_category_vattu,
                'img_lohang' => $img_lohang,
                'soluong' => $soluong,
                'status' => $status,
                'description_giaodich' => $description_giaodich,
                'hoptacxa_xacnhan' => $hoptacxa_xacnhan,
                'nhacungcap_xacnhan' => $nhacungcap_xacnhan,
                'xavien_xacnhan' => $xavien_xacnhan,
            ]);
            if ($giaodichmuabanvattu != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được tạo bởi xã viên " . $xavien->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được tạo bởi nhà cung cấp vật tư " . $nhacungcapvattu->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = XaVien::where('id_xavien', $id_xavien)->first()->id_user;
                        break;

                    default:
                        break;
                }
                $status_notify = 0;
                $link = "/giaodichmuabanvattu";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $user, $link);
                $this->notificationService->sendNotificationService($notify->id);

                $id_user_chunhiem = $this->hopTacXaService->getChuNhiemHTX($id_hoptacxa)->id_user;
                $message = "Xã viên của bạn vừa có một giao dịch mua bán vật tư mới";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $id_user_chunhiem, $link);
                $this->notificationService->sendNotificationService($notify->id);


            }
            DB::commit();
            return $this->getDetailGiaoDichMuaBanVatTu( $giaodichmuabanvattu->id_giaodichmuaban_vattu);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không tạo được giao dịch mua bán vật tư' . $error);
            return false;
        }
    }

    public function updateGiaoDichMuaBanVatTu($request)
    {
        try {
            $id_user = $this->commonService->getIDByToken();
            $account_type = $this->commonService->getAccountTypeByToken();
            $who = "";
            $id = "";

            switch ($account_type) {
                case 'farmer':
                    $id_xavien = $this->xaVienService->getIdXaVienByToken();
                    $id_nhacungcapvattu = $request->id_nhacungcapvattu;
                    $nhacungcap_xacnhan = 0;
                    $xavien_xacnhan = 1;
                    $who = "id_xavien";
                    $id = $id_xavien;
                    break;

                case 'shop':
                    $id_xavien = $request->id_xavien;
                    $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
                    $nhacungcap_xacnhan = 1;
                    $xavien_xacnhan = 0;
                    $who = "id_nhacungcapvattu";
                    $id = $id_nhacungcapvattu;
                    break;

                default:
                    Session::flash('error', 'Không xác định được chủ thể');
                    return false;
                    break;
            }


            $id_giaodichmuabanvattu = $request->id_giaodichmuabanvattu;

            $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
                ->Who($who, $id)
                ->first();
            if ($giaodichmuabanvattu == null) {
                Session::flash('error', 'Giao dịch không tồn tại');
                return false;
            }

            if ($giaodichmuabanvattu->hoptacxa_xacnhan == 2) {
                Session::flash('error', 'Giao dịch đã bị hủy bởi chủ nhiệm hợp tác xã không thể chỉnh sửa !');
                return false;
            }
            if ($giaodichmuabanvattu->status == 1) {
                Session::flash('error', 'Giao dịch đã được xác nhận không thể chỉnh sửa !');
                return false;
            }

            $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanvattu->id_lichmuavu)->first();
            if( $lichmuavu->status == 'finish'){
                Session::flash('error', 'Không thể cập nhật hoạt động của mùa vụ đã kết thúc');
                return false;
            }

            $giaodichmuabanvattu->soluong = $request->soluong;
            $giaodichmuabanvattu->price = $request->price;
            $giaodichmuabanvattu->description_giaodich = $request->description_giaodich;
            $img_lohang = null;
            if ($request->hasFile('img_lohang')) {
                if ($giaodichmuabanvattu->img_lohang != null) {
                    $this->uploadImageService->delete($giaodichmuabanvattu->img_lohang);
                }
                $giaodichmuabanvattu->img_lohang = $this->uploadImageService->store($request->img_lohang);
            }
            $giaodichmuabanvattu->xavien_xacnhan = $xavien_xacnhan;
            $giaodichmuabanvattu->nhacungcap_xacnhan = $nhacungcap_xacnhan;
            $giaodichmuabanvattu->save();
            if ($giaodichmuabanvattu != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được cập nhật bởi xã viên " . $xavien->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được cập nhật bởi nhà cung cấp vật tư " . $nhacungcapvattu->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = XaVien::where('id_xavien', $giaodichmuabanvattu->id_xavien)->first()->id_user;
                        break;

                    default:
                        break;
                }
                $status_notify = 0;
                $link = "/giaodichmuabanvattu";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $user, $link);
                $this->notificationService->sendNotificationService($notify->id);
            }
            DB::commit();
            return $this->getDetailgiaodichmuabanvattu($giaodichmuabanvattu->id_giaodichmuaban_vattu);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không cập nhật được giao dịch mua bán vật tư');
            return false;
        }
    }


    public function deleteGiaoDichMuaBanVatTu($request)
    {
        try {
            $id_user = $this->commonService->getIDByToken();
            $account_type = $this->commonService->getAccountTypeByToken();
            $who = "";
            $id = "";

            switch ($account_type) {
                case 'farmer':
                    $id_xavien = $this->xaVienService->getIdXaVienByToken();
                    $who = "id_xavien";
                    $id = $id_xavien;
                    break;

                case 'shop':
                    $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
                    $who = "id_nhacungcapvattu";
                    $id = $id_nhacungcapvattu;
                    break;

                default:
                    Session::flash('error', 'Không xác định được chủ thể');
                    return false;
                    break;
            }


            $id_giaodichmuabanvattu = $request->id_giaodichmuabanvattu;

            $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
                ->Who($who, $id)
                ->first();

            if ($giaodichmuabanvattu == null) {
                Session::flash('error', 'Giao dịch không tồn tại');
                return false;
            }

            if ($giaodichmuabanvattu->status == 1) {
                Session::flash('error', 'Giao dịch đã được xác nhận không thể xóa !');
                return false;
            }

            $giaodichmuabanvattu->delete();
            if ($giaodichmuabanvattu != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được xóa bởi xã viên " . $xavien->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được xóa bởi nhà cung cấp vật tư " . $nhacungcapvattu->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = XaVien::where('id_xavien', $giaodichmuabanvattu->id_xavien)->first()->id_user;
                        break;

                    default:
                        break;
                }
                $status_notify = 0;
                $link = "/giaodichmuabanvattu";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $user, $link);
                $this->notificationService->sendNotificationService($notify->id);
            }
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không xóa được giao dịch mua bán vật tư');
            return false;
        }
    }

    public function confirmGiaoDichMuaBanVatTu($request)
    {
        $id_giaodichmuabanvattu = $request->id_giaodichmuabanvattu;
        $id_user = $this->commonService->getIDByToken();

        $account_type = $this->commonService->getAccountTypeByToken();
        $who = "";
        $id = "";

        switch ($account_type) {
            case 'farmer':
                $id_xavien = $this->xaVienService->getIdXaVienByToken();
                $who = "id_xavien";
                $id = $id_xavien;

                break;

            case 'shop':
                $id_nhacungcapvattu = $this->nhaCungCapVatTuService->getIdNhaCungCapVatTu();
                $who = "id_nhacungcapvattu";
                $id = $id_nhacungcapvattu;
                break;

            default:
                Session::flash('error', 'Không xác định được chủ thể');
                return false;
                break;
        }

        $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
            ->Who($who, $id)
            ->first();

        if ($giaodichmuabanvattu == null) {
            Session::flash('error', 'Giao dịch không tồn tại');
            return false;
        }

        if ($giaodichmuabanvattu->status == 1) {
            Session::flash('error', 'Giao dịch đã được xác nhận không thể thay đổi trạng thái !');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanvattu->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Không thể xác nhận hoạt động của mùa vụ đã kết thúc');
            return false;
        }

        try {
            DB::beginTransaction();
            switch ($account_type) {
                case 'farmer':

                    if ($giaodichmuabanvattu->xavien_xacnhan == 1) {
                        $giaodichmuabanvattu->xavien_xacnhan = 0;
                    } else {
                        $giaodichmuabanvattu->xavien_xacnhan = 1;
                    }
                    $giaodichmuabanvattu->save();
                    
                    break;


                case 'shop':
                    if ($giaodichmuabanvattu->nhacungcap_xacnhan == 1) {
                        $giaodichmuabanvattu->nhacungcap_xacnhan = 0;
                    } else {
                        $giaodichmuabanvattu->nhacungcap_xacnhan = 1;
                    }
                    $giaodichmuabanvattu->save();
                    break;

                default:
                    Session::flash('error', 'Không xác định được trạng thái');
                    return false;
                    break;
            }


            if ($giaodichmuabanvattu != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được xác nhận bởi xã viên " . $xavien->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao dịch mua bán vật tư số " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " vừa được xác nhận bởi nhà cung cấp vật tư " . $nhacungcapvattu->fullname . ". Vui lòng kiểm tra thông tin";
                        $user = XaVien::where('id_xavien', $giaodichmuabanvattu->id_xavien)->first()->id_user;
                        break;

                    default:
                        break;
                }
                $status_notify = 0;
                $link = "/giaodichmuabanvattu";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $user, $link);
                $this->notificationService->sendNotificationService($notify->id);
            }

            DB::commit();
            return $this->getDetailgiaodichmuabanvattu($giaodichmuabanvattu->id_giaodichmuaban_vattu);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thay đổi được trạng thái');
            return false;
        }
    }

    public function approveGiaoDichMuaBanVatTu($request)
    {
        $id_giaodichmuabanvattu = $request->id_giaodichmuabanvattu;
        $hoptacxa_xacnhan = $request->hoptacxa_xacnhan;
        $id_user = $this->commonService->getIDByToken();
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();

        if(! $this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền duyệt giao dịch');
            return false;
        }


        $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
            ->where('id_hoptacxa', $id_hoptacxa)
            ->first();

        if ($giaodichmuabanvattu == null) {
            Session::flash('error', 'Giao dịch không tồn tại');
            return false;
        }

        if ($giaodichmuabanvattu->status == 1) {
            Session::flash('error', 'Giao dịch đã được xác nhận không thể thay đổi trạng thái !');
            return false;
        }

        if($giaodichmuabanvattu->xavien_xacnhan == 0){
            Session::flash('error', 'Giao dịch chưa được xác nhận bởi xã viên không thể thay đổi trạng thái !');
            return false;
        }
        if($giaodichmuabanvattu->nhacungcap_xacnhan == 0){
            Session::flash('error', 'Giao dịch chưa được nhà cung cấp vật tư bởi không thể thay đổi trạng thái !');
            return false;
        }

        if(in_array($hoptacxa_xacnhan, [0,1,2]) == false){
            Session::flash('error', 'Không xác định được trạng thái');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanvattu->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Không thể xác nhận hoạt động của mùa vụ đã kết thúc');
            return false;
        }
        
        try {
            DB::beginTransaction();
            $giaodichmuabanvattu->hoptacxa_xacnhan = $hoptacxa_xacnhan;
            if($hoptacxa_xacnhan == 1){
                $giaodichmuabanvattu->status = 1;
            }
            if($hoptacxa_xacnhan == 2){
                if($request->reason == null){
                    Session::flash('error', 'Vui lòng nhập lý do từ chối');
                    return false;
                }
                $giaodichmuabanvattu->reason = $request->reason;
            }
            $giaodichmuabanvattu->save();

            if($giaodichmuabanvattu != null){
                $message = "Giao dịch mua bán vật tư số $giaodichmuabanvattu->id_giaodichmuaban_vattu đã bị hủy bởi chủ nhiệm hợp tác xã";
                if($giaodichmuabanvattu->hoptacxa_xacnhan == 1){
                    $message = "Giao dịch mua bán vật tư số $giaodichmuabanvattu->id_giaodichmuaban_vattu đã được duyệt bởi chủ nhiệm hợp tác xã";
                }
                $status_notify = 0;
                $link = "/giaodichmuabanvattu";
                $id_user_xavien = XaVien::where('id_xavien', $giaodichmuabanvattu->id_xavien)->first()->id_user;
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$id_user_xavien,$link);
                $this->notificationService->sendNotificationService($notify->id);

                $id_user_nhacungcap = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$id_user_nhacungcap,$link);
                $this->notificationService->sendNotificationService($notify->id);
            }

            DB::commit();
            return $this->getDetailgiaodichmuabanvattu($giaodichmuabanvattu->id_giaodichmuaban_vattu);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thay đổi được trạng thái');
            return false;
        }
    }
   
}
