<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\BlockChainService\BlockChainAPIService;
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
    protected $blockChainAPIService;

    public function __construct(
        XaVienService $xaVienService,
        HopTacXaService $hopTacXaService,
        ThuongLaiService $thuongLaiService,
        NotificationService $notificationService,
        CommonService $commonService,
        UploadImageService $uploadImageService,
        BlockChainAPIService $blockChainAPIService
    ) {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
        $this->thuongLaiService = $thuongLaiService;
        $this->uploadImageService = $uploadImageService;
        $this->blockChainAPIService = $blockChainAPIService;
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
                    'tbl_gionglua.id_gionglua',
                )
                ->first();
            if ($giaodich == null) {
                Session::flash('error', 'Giao d???ch mua b??n kh??ng t???n t???i');
                return false;
            }

            return $giaodich;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng l???y ???????c th??ng tin giao d???ch mua b??n l??a');
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
            $order = "tbl_giaodichmuaban_lua.updated_at";
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
            Session::flash('error', 'Danh s??ch giao d???ch mua b??n l??a !');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng l???y ???????c danh s??ch mua b??n l??a' . $error);
            return false;
        }
    }

    public function getListGiaoDichMuaBanLuaForHTX($request)
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
            $order = "tbl_giaodichmuaban_lua.updated_at";
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
            Session::flash('error', 'Danh s??ch giao d???ch mua b??n l??a!');
            return false;
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng l???y ???????c danh s??ch giao d???ch' .$error);
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

            $lichmuavu = LichMuaVu::where('id_lichmuavu', $id_lichmuavu)->first();

            $giaodichmuabanlua = GiaoDichMuaBanLua::create([
                'id_thuonglai'=>$id_thuonglai,
                'id_xavien'=>$id_xavien,
                'id_hoptacxa'=>$id_hoptacxa,
                'id_lichmuavu'=>$id_lichmuavu,
                'status'=>0,
                'hoptacxa_xacnhan'=>0,
                'thuonglai_xacnhan'=>0,
                'xavien_xacnhan'=>0,
                'description_giaodich'=>'Giao d???ch v???a ???????c t???o ch??a c?? m?? t??? chi ti???t',
                'name_lohang'=>"Giao d???ch mua b??n l??a gi???a x?? vi??n $name_xavien v?? th????ng l??i $name_thuonglai, m??a v??? : $lichmuavu->name_lichmuavu",
                'price'=>$price,
                'soluong'=>0,
            ]);
            if($giaodichmuabanlua == null){
                Session::flash('error', 'T???o giao d???ch mua b??n l??a kh??ng th??nh c??ng');
                return false;
            }

            return $giaodichmuabanlua;
        } catch (\Exception $error) {
            Session::flash('error', 'Kh??ng th??? t???o giao d???ch mua b??n l??a cho t???t c??? c??c x?? vi??n' . $error);
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
                    Session::flash('error', 'Kh??ng x??c ?????nh ???????c ch??? th???');
                    return false;
                    break;
            }


            $id_giaodichmuabanlua = $request->id_giaodichmuabanlua;

            $giaodichmuabanlua = GiaoDichMuaBanLua::where('id_giaodichmuaban_lua', $id_giaodichmuabanlua)
                ->Who($who, $id)
                ->first();
            if ($giaodichmuabanlua == null) {
                Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
                return false;
            }

            if ($giaodichmuabanlua->status == 1) {
                Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? ch???nh s???a !');
                return false;
            }

            $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanlua->id_lichmuavu)->first();
            if( $lichmuavu->status == 'finish'){
                Session::flash('error', 'Kh??ng th??? c???p nh???t ho???t ?????ng c???a m??a v??? ???? k???t th??c');
                return false;
            }

            if($request->soluong < 0){
                Session::flash('error', 'S??? l?????ng kh??ng ???????c nh??? h??n 0');
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
                        $message = "Giao d???ch mua b??n l??a gi???ng s??? " . $giaodichmuabanlua->id_giaodichmuaban_lua . " v???a ???????c c???p nh???t b???i x?? vi??n " . $xavien->fullname . ". Vui l??ng ki???m tra th??ng tin";
                        $user = ThuongLai::where('id_thuonglai', $giaodichmuabanlua->id_thuonglai)->first()->id_user;
                        break;

                    case 'trader':
                        $thuonglai = ThuongLai::where('tbl_thuonglai.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_thuonglai.id_user')->first();
                        $message = "Giao d???ch mua b??n l??a gi???ng s??? " . $giaodichmuabanlua->id_giaodichmuaban_lua . " v???a ???????c c???p nh???t b???i th????ng l??i " . $thuonglai->fullname . ". Vui l??ng ki???m tra th??ng tin";
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
            Session::flash('error', 'Kh??ng c???p nh???t ???????c giao d???ch mua b??n l??a gi???ng' . $error);
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
                Session::flash('error', 'Kh??ng x??c ?????nh ???????c ch??? th???');
                return false;
                break;
        }

        $giaodichmuabanlua = GiaoDichMuaBanLua::where('id_giaodichmuaban_lua', $id_giaodichmuabanlua)
            ->Who($who, $id)
            ->first();

        if ($giaodichmuabanlua == null) {
            Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
            return false;
        }

        if ($giaodichmuabanlua->status == 1) {
            Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }

        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanlua->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Kh??ng th??? x??c nh???n ho???t ?????ng c???a m??a v??? ???? k???t th??c');
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
                    Session::flash('error', 'Kh??ng x??c ?????nh ???????c tr???ng th??i');
                    return false;
                    break;
            }


            if ($giaodichmuabanlua != null) {
                switch ($account_type) {
                    case 'farmer':
                        $xavien = XaVien::where('tbl_xavien.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')->first();
                        $message = "Giao d???ch mua b??n l??a s??? " . $giaodichmuabanlua->id_giaodichmuaban_lua . " v???a ???????c x??c nh???n b???i x?? vi??n " . $xavien->fullname . ". Vui l??ng ki???m tra th??ng tin";
                        $user = ThuongLai::where('id_thuonglai', $giaodichmuabanlua->id_thuonglai)->first()->id_user;
                        break;

                    case 'trader':
                        $thuonglai = ThuongLai::where('tbl_thuonglai.id_user', $id_user)->join('tbl_user', 'tbl_user.id_user', 'tbl_thuonglai.id_user')->first();
                        $message = "Giao d???ch mua b??n l??a s??? " . $giaodichmuabanlua->id_giaodichmuaban_lua . " v???a ???????c x??c nh???n b???i nh?? cung c???p v???t t?? " . $thuonglai->fullname . ". Vui l??ng ki???m tra th??ng tin";
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
            Session::flash('error', 'Kh??ng thay ?????i ???????c tr???ng th??i'. $error);
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
            Session::flash('error', 'B???n kh??ng c?? quy???n duy???t giao d???ch');
            return false;
        }


        $giaodichmuabanlua = GiaoDichMuaBanLua::where('id_giaodichmuaban_lua', $id_giaodichmuabanlua)
            ->where('id_hoptacxa', $id_hoptacxa)
            ->first();

        if ($giaodichmuabanlua == null) {
            Session::flash('error', 'Giao d???ch kh??ng t???n t???i');
            return false;
        }

        if ($giaodichmuabanlua->status == 1) {
            Session::flash('error', 'Giao d???ch ???? ???????c x??c nh???n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }

        if($giaodichmuabanlua->xavien_xacnhan == 0){
            Session::flash('error', 'Giao d???ch ch??a ???????c x??c nh???n b???i x?? vi??n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }
        if($giaodichmuabanlua->thuonglai_xacnhan == 0){
            Session::flash('error', 'Giao d???ch ch??a ???????c th????ng l??i x??c nh???n kh??ng th??? thay ?????i tr???ng th??i !');
            return false;
        }

        if(in_array($hoptacxa_xacnhan, [0,1]) == false){
            Session::flash('error', 'Kh??ng x??c ?????nh ???????c tr???ng th??i');
            return false;
        }
        $lichmuavu = LichMuaVu::where('id_lichmuavu', $giaodichmuabanlua->id_lichmuavu)->first();
        if( $lichmuavu->status == 'finish'){
            Session::flash('error', 'Kh??ng th??? x??c nh???n ho???t ?????ng c???a m??a v??? ???? k???t th??c');
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
                $message = "Giao d???ch mua b??n l??a s??? $giaodichmuabanlua->id_giaodichmuaban_lua ???? ???????c duy???t b???i ch??? nhi???m h???p t??c x??";
                $status_notify = 0;
                $link = "/giaodichmuabanlua";
                $id_user_xavien = XaVien::where('id_xavien', $giaodichmuabanlua->id_xavien)->first()->id_user;
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$id_user_xavien,$link);
                $this->notificationService->sendNotificationService($notify->id);

                $id_user_nhacungcap = ThuongLai::where('id_thuonglai', $giaodichmuabanlua->id_thuonglai)->first()->id_user;
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$id_user_nhacungcap,$link);
                $this->notificationService->sendNotificationService($notify->id);
            }
            $giaodichmuabanluaDetail = $this->getDetailgiaodichmuabanlua($giaodichmuabanlua->id_giaodichmuaban_lua);
            //CREATE BLOCKCHAIN GIAODICHMUABAN_LUA NODE

            if($giaodichmuabanlua->status == 1){
                $giaodichmuabanluaBlockChain = (object) $giaodichmuabanluaDetail;

                $this->blockChainAPIService->createBlockChainGiaoDichMuaBanLua(
                    $giaodichmuabanluaBlockChain->id_giaodichmuaban_lua,
                    $giaodichmuabanluaBlockChain->id_lichmuavu,
                    $giaodichmuabanluaBlockChain->id_giaodichmuaban_lua,
                    $giaodichmuabanluaBlockChain->id_xavien,
                    $giaodichmuabanluaBlockChain->id_thuonglai,
                    $this->commonService->convertDateTOTimeStringForBlockChain($giaodichmuabanluaBlockChain->created_at->format('Y-m-d')),
                    $giaodichmuabanluaBlockChain->price,
                    $giaodichmuabanluaBlockChain->id_gionglua,
                    $this->commonService->convertDateTOTimeStringForBlockChain($giaodichmuabanluaBlockChain->updated_at->format('Y-m-d')),
                    $giaodichmuabanluaBlockChain->name_gionglua,
                    $giaodichmuabanluaBlockChain->soluong,
                    $this->commonService->getWalletTypeByToken(),
                    '1234'
                );
            }

            DB::commit();
            return $giaodichmuabanluaDetail;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Kh??ng thay ?????i ???????c tr???ng th??i' .$error);
            return false;
        }
    }

}
