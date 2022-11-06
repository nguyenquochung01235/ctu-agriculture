<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserService{
    
    protected $xaVienService;
    protected $thuongLaiService;
    protected $nhaCungCapVatTuService;
    protected $uploadImageService;
    protected $commonService;

    public function __construct(
        XaVienService $xaVienService,
        ThuongLaiService $thuongLaiService, 
        NhaCungCapVatTuService $nhaCungCapVatTuService,
        UploadImageService $uploadImageService,
        CommonService $commonService
        )
    {
        $this->xaVienService = $xaVienService;
        $this->thuongLaiService = $thuongLaiService;
        $this->nhaCungCapVatTuService = $nhaCungCapVatTuService;
        $this->uploadImageService = $uploadImageService;
        $this->commonService = $commonService;
    }
    
    public function getAllUser(){
        return User::get();
    }

    public function getUserWithAccountType($id_user){
        return User::with('account')->where('id_user',$id_user)->get();
    }

    public function createNewUser($request){
        try {
            $email = $request->input('email');
            $phone_number = $request->input('phone_number');
            
            if(!$this->isEmailExist($email)){
                Session::flash('error', 'Email đã tồn tại');
                return false;
            }

            if(!$this->isPhoneExist($phone_number)){
                Session::flash('error', 'Số điện thoại đã tồn tại');
                return false;
            }

            DB::beginTransaction();
            $user =  User::create([
                'fullname' => $request->input('fullname'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'password' => bcrypt($request->input('password')),
                'address' => $request->input('address'),
                'wallet' => Str::uuid()->toString(),
                'dob' => $request->input('dob'),
                'active' => 1
            ]);
            $id_user = $user->id_user;
            // START Create Account Option
            $list_account_type = json_decode($request->input('account_type'));
            if($list_account_type == null){
                $list_account_type = [1];
            }
            
            foreach ($list_account_type as $key => $role_code) {
                switch ($role_code) {
                    case 1:
                        if(!$this->xaVienService->createXaVien($id_user)){
                            Session::flash('error', 'Không tạo được account xã viên');
                            DB::rollBack();
                            return false;
                        }
                        break;
                    case 2:
                        if(!$this->thuongLaiService->createThuongLai($id_user,$request->input('fullname'))){
                            DB::rollBack();
                            Session::flash('error', 'Không tạo được account thương lái');
                            return false;
                        }
                        break;
                    case 3:
                        if(!$this->nhaCungCapVatTuService->createNhaCungCapVatTu($id_user,$request->input('fullname'))){
                            DB::rollBack();
                            Session::flash('error', 'Không tạo được account nhà cung cấp vật tư');
                            return false;
                        }
                        break;
                }
            // END Create Account Option
            }
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không Tạo Được Tài Khoản !!!');
            return false;
        }
        DB::commit();
        return true;
    }

    // public function updateUser(){
    //     $user_id = $this->commonService->getIDByToken();
    //     $user = User::where('user_id', $user_id)->first();


    // }


    public function isEmailExist($email){
        $isEmailExist = User::where('email', $email)->count();
        if($isEmailExist){
            return false;
        }
        return true;
    }
    public function isPhoneExist($phone_number){
        $isPhoneNumberExist = User::where('phone_number', $phone_number)->count();
        if($isPhoneNumberExist){
            return false;
        }
        return true;
    }

}