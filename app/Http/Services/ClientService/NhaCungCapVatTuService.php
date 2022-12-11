<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Account;
use App\Models\GiaoDichMuaBanLuaGiong;
use App\Models\GiaoDichMuaBanVatTu;
use App\Models\NhaCungCapVatTu;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NhaCungCapVatTuService{
    

    protected $commonService;
    protected $uploadImageService;


    public function __construct(CommonService $commonService, UploadImageService $uploadImageService)
    {
        $this->commonService = $commonService;
        $this->uploadImageService = $uploadImageService;
    }

    public function getInfoDashBoard(){
        try {
            $id_user = $this->commonService->getIDByToken();
            $nhacungcapvattu = NhaCungCapVatTu::where('id_user', $id_user)->first();
            $luagiong_count = GiaoDichMuaBanLuaGiong::where('id_nhacungcapvattu',$nhacungcapvattu->id_nhacungcapvattu)->count();
            $vattu_count = GiaoDichMuaBanVatTu::where('id_nhacungcapvattu',$nhacungcapvattu->id_nhacungcapvattu)->count();
            $tongsanluong_count = $luagiong_count + $vattu_count;
            return $result = ([
                'lohang_count' => $tongsanluong_count,
                'luagiong_count' => $luagiong_count,
                'vattu_count' => $vattu_count,
                'tongsanluong_count' => $tongsanluong_count,
            ]);
            } catch (\Exception $error) {
            Session::flash('error', 'Không thể lấy dữ liệu');
            return false;
            }
    }

    public function searchNhaCungCapByPhoneNumber($request){
        try {
            $phone_number = $request->phone_number;
            $nhacungcapvattu = NhaCungCapVatTu::join('tbl_user','tbl_user.id_user', 'tbl_nhacungcapvattu.id_user')
                ->where('tbl_user.phone_number',  $phone_number)
                ->select(
                    "tbl_nhacungcapvattu.*",
                    "tbl_user.id_user",
                    "tbl_user.fullname",
                    "tbl_user.phone_number",
                )
                ->first();
            if($nhacungcapvattu == null){
                Session::flash('error', 'Không tìm thấy nhà cung cấp cung vật tư nào với số điện thoại trên');
                return false;
            }
            return  $nhacungcapvattu;

        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin nhà cung cấp vật tư');
            return false;
        }
    }

    public function getIdNhaCungCapVatTu(){
        $id_user = $this->commonService->getIDByToken();
        try {
            $nhacungcapvattu = NhaCungCapVatTu::where('id_user', $id_user)->first();
            if($nhacungcapvattu == null){
                Session::flash('error', 'Nhà cung cấp vật tư không tồn tại');
                return false;
            }
            return  $nhacungcapvattu->id_nhacungcapvattu;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin nhà cung cấp vật tư');
            return false;
        }   
    }

    public function getDetailNhaCungCapVatTu(){
        $id_nhacungcapvattu = $this->getIdNhaCungCapVatTu();

        try {
            $nhacungcapvattu = NhaCungCapVatTu::join('tbl_user', 'tbl_nhacungcapvattu.id_user', '=', 'tbl_user.id_user')
                ->where('id_nhacungcapvattu',$id_nhacungcapvattu)
                ->first();
            return  $nhacungcapvattu->makeHidden([
                'password',
                'wallet',
                'created_at',
                'updated_at',
                'remember_token',
            ]);
        } catch (\Exception $error) {
            Session::flash('error', 'Không thể lấy được thông tin');
            return false;
        }

    }

    public function createNhaCungCapVatTu($id_user, $name_daily){
        try {
            
            NhaCungCapVatTu::create([
                'id_user' => $id_user,
                'name_daily' => $name_daily,
                'active' => 1
            ]);
            $nhacungcapvattu = User::where('id_user', $id_user)->first();
            $account = Account::where('code', '3')->first();
            $nhacungcapvattu->account()->attach($account);
        } catch (\Exception $error) {
            Session::flash('error', 'Không thể tạo tài khoản nhà cung cấp vật tư');
            return false;
        }
        return true;
    }

    public function updateNhaCungCapVatTu($request){
        try {
            
            $id_user = $this->commonService->getIDByToken();
            $nhacungcapvattu = NhaCungCapVatTu::where('id_user', $id_user)->first();
            if($nhacungcapvattu == null){
                Session::flash('error', "Nhà cung cấp vật tư không tồn tại");
                return false;
            }
            DB::beginTransaction();
            $nhacungcapvattu->name_daily = $request->name_daily;
            $nhacungcapvattu->description = $request->description;
            if($request->has('thumbnail')){
                if($nhacungcapvattu->thumbnail != null){
                    $this->uploadImageService->delete($nhacungcapvattu->thumbnail);
            }
            $nhacungcapvattu->thumbnail = $this->uploadImageService->store($request->thumbnail);
            }
            if($request->has('img_background')){
                if($nhacungcapvattu->img_background != null){
                    $this->uploadImageService->delete($nhacungcapvattu->img_background);
            }
            $nhacungcapvattu->img_background = $this->uploadImageService->store($request->img_background);
            }
            $nhacungcapvattu->save();
            DB::commit();
            return $this->getDetailNhaCungCapVatTu();
        } catch (\Exception $error) {
            Session::flash('error', "Không cập nhật được thông tin");
            return false;
        }
        return true;
    }

    
    

}