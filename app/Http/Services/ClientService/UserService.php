<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    public function getDetail(){
        $id_user = $this->commonService->getIDByToken();
        try {
            $user = User::where('id_user', $id_user)->first();
            return $user;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin tài !!!');
            return false;
        }

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

    public function updateUser($request){
        try {
            $id_user = $this->commonService->getIDByToken();
            $user = User::where('id_user', $id_user)->first();

            $isEmailExist = User::where('email', $request->email)->whereNot('id_user', $id_user)->count();
            if($isEmailExist > 0){
                Session::flash('error', 'Email đã tồn tại');
                return false;
            }

            DB::beginTransaction();
            $user->fullname = $request->fullname;
            $user->email = $request->email;
            $user->dob = $request->dob;
            $user->address = $request->address;
            if($request->has('avatar')){
                if($user->avatar != null){
                    $this->uploadImageService->delete($user->avatar);
            }
            $user->avatar = $this->uploadImageService->store($request->avatar);
            }
            $user->save();
            DB::commit();
            return  $user;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không cập nhật được tài khoản !!!' . $error);
            return false;
        }
    }

    public function updatePassword($request){
       try {
        $id_user = $this->commonService->getIDByToken();
        $user = User::where('id_user', $id_user)->first();
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        if(!Hash::check($old_password, auth()->user()->password)){
            Session::flash("error", "Mật khẩu cũ không chính xã");
            return false;
        }
        #Update the new Password
        User::where('id_user',$id_user)->update([
            'password' => Hash::make($new_password)
        ]);
        return true;
       } catch (\Exception $error) {
        Session::flash('error', 'Không cập nhật được mật khẩu' . $error);
        return false;
       }
    }

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