<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\BlockChainService\BlockChainAPIService;
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
    protected $blockChainAPIService;

    public function __construct(
        XaVienService $xaVienService,
        HopTacXaService $hopTacXaService,
        NhaCungCapVatTuService $nhaCungCapVatTuService,
        NotificationService $notificationService,
        CommonService $commonService,
        UploadImageService $uploadImageService,
        BlockChainAPIService $blockChainAPIService
    ) {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
        $this->nhaCungCapVatTuService = $nhaCungCapVatTuService;
        $this->uploadImageService = $uploadImageService;
        $this->blockChainAPIService = $blockChainAPIService;
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
                Session::flash('error', 'Giao d???ch mua b??n kh??ng t???n t???i');
                return false;
            }

            $xavien = XaVien::where('tbl_xavien.id_xavien', $giaodich->id_xavien)
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                ->first();

            if ($xavien == null) {
                Session::flash('error', 'Kh??ng l???y ???????c th??ng tin x?? vi??n');
                return false;
            }

            $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_nhacungcapvattu', $giaodich->id_nhacungcapvattu)
                ->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')
                ->first();

            if ($nhacungcapvattu == null) {
                Session::flash('error', 'Kh??ng l???y ???????c th??ng tin nh?? cung c???p v???t t??');
                return false;
            }

            return $result = ([
                "id_giaodichmuaban_vattu" => $giaodich->id_giaodichmuaban_vattu,
                "id_lichmuavu" => $giaodich->id_lichmuavu,
                "name_lichmuavu" => $giaodich->name_lichmuavu,
                "id_category_vattu" => $giaodich->id_category_vattu,
                "name_category_vattu" => $giaodich->name_category_vattu,
                "img_lohang" => $giaodich->img_lohang,
                "soluong" => $giaodich->soluong,
                "price" => $giaodich->price,
                "status" => $giaodich->status,
                "reason" => $giaodich->reason,
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
            Session::flash('error', 'Kh??ng l???y ???????c th??ng tin giao d???ch mua b??n v???t t??');
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
            Session::flash('error', 'Danh s??ch giao d???ch mua b??n v???t t?? !');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng l???y ???????c danh s??ch giao d???ch mua b??n v???t t??'. $error);
            return false;
        }
    }

    public function getListGiaoDichMuaBanVatTuForHTX($request)
    { 
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
        if (!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)) {
            Session::flash('error', 'B???n kh??ng c?? quy???n qu???n tr??? ????? xem danh m???c n??y');
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
            Session::flash('error', 'Danh s??ch giao d???ch mua b??n v???t t?? !');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng l???y ???????c danh s??ch giao d???ch mua b??n v???t t??');
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
                    Session::flash('error', 'Kh??ng x??c ?????nh ???????c ch??? th???');
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
            if($price < 0){
                Session::flash('error', 'Gi?? thua mua kh??ng ???????c nh??? h??n 0');
                return false;
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
                'price' => $price,
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
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c t???o b???i x?? vi??n " . $xavien->fullname . ". Vui l??ng ki???m tra th??ng tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c t???o b???i nh?? cung c???p v???t t?? " . $nhacungcapvattu->fullname . ". Vui l??ng ki???m tra th??ng tin";
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
                $message = "X?? vi??n c???a b???n v???a c?? m???t giao d???ch mua b??n v???t t?? m???i";
                $notify = $this->notificationService->createNotificationService($message, $status_notify, $id_user_chunhiem, $link);
                $this->notificationService->sendNotificationService($notify->id);


            }
            DB::commit();
            return $this->getDetailGiaoDichMuaBanVatTu( $giaodichmuabanvattu->id_giaodichmuaban_vattu);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng t???o ???????c giao d???ch mua b??n v???t t??' . $error);
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
                    Session::flash('error', 'Kh??ng x??c ?????nh ???????c ch??? th???');
                    return false;
                    break;
            }


            $id_giaodichmuabanvattu = $request->id_giaodichmuabanvattu;

            $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
                ->Who($who, $id)
                ->first();
            if ($giaodichmuabanvattu == null) {
                Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
                return false;
            }

            if ($giaodichmuabanvattu->hoptacxa_xacnhan == 2) {
                Session::flash('error', 'Giao d???ch ???? b??? h???y b???i ch??? nhi???m h???p t??c x?? kh??ng th??? ch???nh s???a !');
                return false;
            }
            if ($giaodichmuabanvattu->status == 1) {
                Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? ch???nh s???a !');
                return false;
            }

            $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanvattu->id_lichmuavu)->first();
            if( $lichmuavu->status == 'finish'){
                Session::flash('error', 'Kh??ng th??? c???p nh???t ho???t ?????ng c???a m??a v??? ???? k???t th??c');
                return false;
            }

            if( $request->price < 0){
                Session::flash('error', 'Gi?? thua mua kh??ng ???????c nh??? h??n 0');
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
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c c???p nh???t b???i x?? vi??n " . $xavien->fullname . ". Vui l??ng ki???m tra th??ng tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c c???p nh???t b???i nh?? cung c???p v???t t?? " . $nhacungcapvattu->fullname . ". Vui l??ng ki???m tra th??ng tin";
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
            Session::flash('error', 'Kh??ng c???p nh???t ???????c giao d???ch mua b??n v???t t??');
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
                    Session::flash('error', 'Kh??ng x??c ?????nh ???????c ch??? th???');
                    return false;
                    break;
            }


            $id_giaodichmuabanvattu = $request->id_giaodichmuabanvattu;

            $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
                ->Who($who, $id)
                ->first();

            if ($giaodichmuabanvattu == null) {
                Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
                return false;
            }

            if ($giaodichmuabanvattu->status == 1) {
                Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? x??a !');
                return false;
            }

            $giaodichmuabanvattu->delete();
            if ($giaodichmuabanvattu != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c x??a b???i x?? vi??n " . $xavien->fullname . ". Vui l??ng ki???m tra th??ng tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c x??a b???i nh?? cung c???p v???t t?? " . $nhacungcapvattu->fullname . ". Vui l??ng ki???m tra th??ng tin";
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
            Session::flash('error', 'Kh??ng x??a ???????c giao d???ch mua b??n v???t t??');
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
                Session::flash('error', 'Kh??ng x??c ?????nh ???????c ch??? th???');
                return false;
                break;
        }

        $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
            ->Who($who, $id)
            ->first();

        if ($giaodichmuabanvattu == null) {
            Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
            return false;
        }

        if ($giaodichmuabanvattu->status == 1) {
            Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanvattu->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Kh??ng th??? x??c nh???n ho???t ?????ng c???a m??a v??? ???? k???t th??c');
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
                    Session::flash('error', 'Kh??ng x??c ?????nh ???????c tr???ng th??i');
                    return false;
                    break;
            }


            if ($giaodichmuabanvattu != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c x??c nh???n b???i x?? vi??n " . $xavien->fullname . ". Vui l??ng ki???m tra th??ng tin";
                        $user = NhaCungCapVatTu::where('id_nhacungcapvattu', $giaodichmuabanvattu->id_nhacungcapvattu)->first()->id_user;
                        break;

                    case 'shop':
                        $nhacungcapvattu = NhaCungCapVatTu::where('tbl_nhacungcapvattu.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')->first();
                        $message = "Giao d???ch mua b??n v???t t?? s??? " . $giaodichmuabanvattu->id_giaodichmuaban_vattu . " v???a ???????c x??c nh???n b???i nh?? cung c???p v???t t?? " . $nhacungcapvattu->fullname . ". Vui l??ng ki???m tra th??ng tin";
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
            Session::flash('error', 'Kh??ng thay ?????i ???????c tr???ng th??i');
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
            Session::flash('error', 'B???n kh??ng c?? quy???n duy???t giao d???ch');
            return false;
        }


        $giaodichmuabanvattu = GiaoDichMuaBanVatTu::where('id_giaodichmuaban_vattu', $id_giaodichmuabanvattu)
            ->where('id_hoptacxa', $id_hoptacxa)
            ->first();

        if ($giaodichmuabanvattu == null) {
            Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
            return false;
        }

        if ($giaodichmuabanvattu->status == 1) {
            Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }

        if($giaodichmuabanvattu->xavien_xacnhan == 0){
            Session::flash('error', 'Giao d???ch ch??a ???????c x??c nh???n b???i x?? vi??n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }
        if($giaodichmuabanvattu->nhacungcap_xacnhan == 0){
            Session::flash('error', 'Giao d???ch ch??a ???????c nh?? cung c???p v???t t?? b???i kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }

        if(in_array($hoptacxa_xacnhan, [0,1,2]) == false){
            Session::flash('error', 'Kh??ng x??c ?????nh ???????c tr???ng th??i');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanvattu->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Kh??ng th??? x??c nh???n ho???t ?????ng c???a m??a v??? ???? k???t th??c');
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
                    Session::flash('error', 'Vui l??ng nh???p l?? do t??? ch???i');
                    return false;
                }
                $giaodichmuabanvattu->reason = $request->reason;
            }
            $giaodichmuabanvattu->save();

            if($giaodichmuabanvattu != null){
                $message = "Giao d???ch mua b??n v???t t?? s??? $giaodichmuabanvattu->id_giaodichmuaban_vattu ???? b??? h???y b???i ch??? nhi???m h???p t??c x??";
                if($giaodichmuabanvattu->hoptacxa_xacnhan == 1){
                    $message = "Giao d???ch mua b??n v???t t?? s??? $giaodichmuabanvattu->id_giaodichmuaban_vattu ???? ???????c duy???t b???i ch??? nhi???m h???p t??c x??";
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

            $giaodichmuabanvattuDetail = $this->getDetailgiaodichmuabanvattu($giaodichmuabanvattu->id_giaodichmuaban_vattu);
            // CREATE BLOCKCHAIN GIAODICHMUABAN_VATTU NODE
            if($giaodichmuabanvattu->status == 1){
                $giaodichmuabanvattuBlockChain = (object) $giaodichmuabanvattuDetail;


                $this->blockChainAPIService->createBlockChainGiaoDichMuaBanVatTu(
                    $giaodichmuabanvattuBlockChain->id_giaodichmuaban_vattu,
                    $giaodichmuabanvattuBlockChain->id_xavien,
                    $giaodichmuabanvattuBlockChain->id_nhacungcapvattu,
                    $giaodichmuabanvattuBlockChain->id_giaodichmuaban_vattu,
                    $giaodichmuabanvattuBlockChain->id_category_vattu,
                    $this->commonService->convertDateTOTimeStringForBlockChain($giaodichmuabanvattuBlockChain->created_at->format('Y-m-d')),
                    $giaodichmuabanvattuBlockChain->price,
                    $giaodichmuabanvattuBlockChain->id_lichmuavu,
                    $giaodichmuabanvattuBlockChain->name_category_vattu,
                    $this->commonService->convertDateTOTimeStringForBlockChain($giaodichmuabanvattuBlockChain->updated_at->format('Y-m-d')),
                    $giaodichmuabanvattuBlockChain->soluong,
                    $this->commonService->getWalletTypeByToken(),
                    '1234'
                );
            }

            DB::commit();
            return $giaodichmuabanvattuDetail;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng thay ?????i ???????c tr???ng th??i');
            return false;
        }
    }
   
}
