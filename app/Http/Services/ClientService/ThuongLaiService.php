<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Account;
use App\Models\DanhMucQuyDinh;
use App\Models\GiaoDichMuaBanLua;
use App\Models\HopDongMuaBan;
use App\Models\ThuongLai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ThuongLaiService{
    
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
            $thuonglai = ThuongLai::where('id_user', $id_user)->first();
            $lohang_count = GiaoDichMuaBanLua::where('id_thuonglai',$thuonglai->id_thuonglai)->count();
            $hopdong_count = HopDongMuaBan::where('id_thuonglai',$thuonglai->id_thuonglai)->count();
            $danhmuc_count = DanhMucQuyDinh::where('id_thuonglai',$thuonglai->id_thuonglai)->count();
            return $result = ([
                'lohang_count' => $lohang_count,
                'hopdong_count' => $hopdong_count,
                'sanluong_ount' => 968484,
                'danhmuc_count' => $danhmuc_count,
            ]);
            } catch (\Exception $error) {
            Session::flash('error', 'Không thể lấy dữ liệu');
            return false;
            }
    }

    public function getIdThuongLai(){
        $id_user = $this->commonService->getIDByToken();
        try {
            $thuonglai = ThuongLai::where('id_user', $id_user)->first();
            if($thuonglai == null){
                Session::flash('error', 'Thương lái không tồn tại');
                return false;
            }
            return  $thuonglai->id_thuonglai;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin thương lái');
            return false;
        }
        
    }

    public function createThuongLai($id_user, $name_thuonglai){
        try {
            ThuongLai::create([
                'id_user' => $id_user,
                'name_thuonglai' => $name_thuonglai,
                'active' => 1
            ]);
            $thuonglai = User::where('id_user', $id_user)->first();
            $account = Account::where('code', '2')->first();
            $thuonglai->account()->attach($account);
        } catch (\Exception $error) {
            Session::flash('error', 'Không thể tạo được hợp đồng');
            return false;
        }
        return true;
    }

    public function getDetailThuongLai(){
        $id_thuonglai = $this->getIdThuongLai();

        try {
            $thuonglai = ThuongLai::join('tbl_user', 'tbl_thuonglai.id_user', '=', 'tbl_user.id_user')
                ->where('id_thuonglai',$id_thuonglai)
                ->first();
            return  $thuonglai->makeHidden([
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

    public function updateThuongLai($request){
        try {
            
            $id_user = $this->commonService->getIDByToken();
            $thuonglai = ThuongLai::where('id_user', $id_user)->first();
            if($thuonglai == null){
                Session::flash('error', "Thương lái không tồn tại");
                return false;
            }
            DB::beginTransaction();
            $thuonglai->name_thuonglai = $request->name_thuonglai;
            $thuonglai->description = $request->description;
            if($request->has('thumbnail')){
                if($thuonglai->thumbnail != null){
                    $this->uploadImageService->delete($thuonglai->thumbnail);
            }
            $thuonglai->thumbnail = $this->uploadImageService->store($request->thumbnail);
            }
            if($request->has('img_background')){
                if($thuonglai->img_background != null){
                    $this->uploadImageService->delete($thuonglai->img_background);
            }
            $thuonglai->img_background = $this->uploadImageService->store($request->img_background);
            }
            $thuonglai->save();
            DB::commit();
            return $this->getDetailThuongLai();
        } catch (\Exception $error) {
            Session::flash('error', "Không cập nhật được thông tin");
            return false;
        }
        return true;
    }

}