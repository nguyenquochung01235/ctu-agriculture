<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\Account;
use App\Models\ThuongLai;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ThuongLaiService{
    
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
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

}