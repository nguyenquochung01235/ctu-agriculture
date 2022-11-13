<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\HoatDongMuaVu;
use App\Models\HopDongMuaBan;
use App\Models\HopTacXa;
use App\Models\LichMuaVu;
use App\Models\ThuaDat;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HopTacXaService{

    protected $xaVienService;
    protected $commonService;
    protected $userService;
    protected $uploadImageService;

    public function __construct(
        XaVienService $xaVienService,
        CommonService $commonService,
        UserService $userService,
        UploadImageService $uploadImageService )
    {
        $this->xaVienService = $xaVienService;
        $this->commonService = $commonService;
        $this->userService = $userService;
        $this->uploadImageService = $uploadImageService;

        
    }

    public function isNameExist($name_hoptacxa){
        $isEmailExist = HopTacXa::where('name_hoptacxa', $name_hoptacxa)->count();
        if($isEmailExist){
            return false;
        }
        return true;
    }

    public function isEmailExist($email){
        $isEmailExist = HopTacXa::where('email', $email)->count();
        if($isEmailExist){
            return false;
        }
        return true;
    }

    public function isPhoneExist($phone_number){
        $isPhoneNumberExist = HopTacXa::where('phone_number', $phone_number)->count();
        if($isPhoneNumberExist){
            return false;
        }
        return true;
    }
    public function getIDHopTacXaByToken(){
        try {
            $id_hoptacxa = null;
            $id_user = $this->commonService->getIDByToken();
            $hoptacxa = XaVien::where('id_user', $id_user)->first('id_hoptacxa');
            if($hoptacxa != null){
                $id_hoptacxa = $hoptacxa->id_hoptacxa;
            }
            return $id_hoptacxa;
       } catch (\Exception $error) {
           Session::flash('error', 'Không xác định được hợp tác xã bằng token user' . $error);
           return false;
       }
   }
    
    public function isHTXActive($id_hoptacxa){
        try {
            $result = HopTacXa::where('id_hoptacxa', $id_hoptacxa)->first('active');
            if($result->active != 1) {
                Session::flash('error','Hợp tác xã chưa được kích hoạt hoặc đã bị khóa');
                return false;
            }
        } catch (\Exception $error) {
            Session::flash('error','Hợp tác xã này không tồn tại');
            return false;
        }
        return true;
    }
    public function activeHopTacXa($id_hoptacxa){
        try {
            $hoptacxa = HopTacXa::where('id_hoptacxa', $id_hoptacxa)->first();
            if($hoptacxa == null) {
                Session::flash('error','Hợp tác xã không tồn tại');
                return false;
            }
            DB::beginTransaction();
            $hoptacxa->active = 1;
            $hoptacxa->save();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            Session::flash('error','Hợp tác xã này không tồn tại');
            return false;
        }
    }


    public function searchHopTacXaByPhoneNumber($request){
        $phone_number = $request->phone_number;
        try {
            $hoptacxa = HopTacXa::where('phone_number',$phone_number)->first();

             if($hoptacxa == null){
                Session::flash('error','Không tìm thấy kết quả');
                return false;
            }

            if($hoptacxa->active != 1){
                Session::flash('error','Hợp tác xã chưa được kích hoạt hoặc đang bị khóa');
                return false;
            }
            
           

            return $hoptacxa;

        } catch (\Exception $error) {
            Session::flash('error','Không thể lấy kết quả, có lỗi trong lúc truy xuất');
                return false;
        }
    }

    public function getInfoDashBoard(){
       try {
        $id_user = $this->commonService->getIDByToken();
        $id_hoptacxa = $this->getIDHopTacXaByToken();

        if($id_hoptacxa == false){
            Session::flash('error', 'Bạn chưa có hợp tác xã');
            return false;
        }
        $xavien_count = XaVien::where('id_hoptacxa', $id_hoptacxa)->count();
        $hoatdongmuavu_count = HoatDongMuaVu::
        join('tbl_lichmuavu', 'tbl_hoatdongmuavu.id_lichmuavu', '=', 'tbl_lichmuavu.id_lichmuavu')
        ->where('id_hoptacxa', $id_hoptacxa)->count();
        $lichmuavu_count = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)->count();
        $hopdong_count = HopDongMuaBan::where('id_hoptacxa', $id_hoptacxa)->count();
        $result = ([
            'xavien_count' => $xavien_count,
            'hoatdongmuavu_count' => $hoatdongmuavu_count,
            'lichmuavu_count' => $lichmuavu_count,
            'hopdong_count' => $hopdong_count,
        ]);
        return $result;
       } catch (\Exception $error) {
        Session::flash('error', 'Không thể lấy dữ liệu' .$error);
        return false;
       }
    }

    public function getChuNhiemHTX($id_hoptacxa){
       try {
        $chunhiem = XaVien::join('xavien_rolexavien', 'xavien_rolexavien.xavien_id_xavien', 'tbl_xavien.id_xavien')
                        ->join('tbl_rolexavien', 'tbl_rolexavien.id_role', 'xavien_rolexavien.rolexavien_id_role')
                        ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                        ->where('id_hoptacxa', $id_hoptacxa)
                        ->where('tbl_rolexavien.role','chunhiem')
                        ->select('tbl_xavien.*','tbl_user.*')
                        ->first();
        return $chunhiem;
       } catch (\Exception $error) {
        Session::flash('error', 'Không lấy được thông tin chủ nhiệm hợp tác xã');
        return false;
       }
    }

    public function getAllMemberOfHopTacXa($id_hoptacxa){
        try {
            $list_member = XaVien::where("id_hoptacxa", $id_hoptacxa)->get();
            return $list_member;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin member htx');
            return false;
        }
    }


    public function getDetail(){
        $id_hoptacxa = $this->getIDHopTacXaByToken();
        try {
            $hoptacxa = HopTacXa::where('id_hoptacxa', $id_hoptacxa)->first();
            $chunhiem = $this->getChuNhiemHTX($hoptacxa->id_hoptacxa);

            $result = ([
                "id_hoptacxa"=>  $id_hoptacxa,
                "name_hoptacxa"=> $hoptacxa->name_hoptacxa,
                "phone_number"=>  $hoptacxa->phone_number,
                "email"=>  $hoptacxa->email,
                "address"=>  $hoptacxa->address,
                "thumbnail"=>  $hoptacxa->thumbnail,
                "img_background"=>  $hoptacxa->img_background,
                "description"=>  $hoptacxa->description,
                "active"=>  $hoptacxa->active,
                "created_at"=> $hoptacxa->created_at,
                "updated_at"=>  $hoptacxa->updated_at,
                "chunhiem_name" => $chunhiem->fullname,
                "chunhiem_phone_number" => $chunhiem->phone_number,
                "chunhiem_email" => $chunhiem->email,
                "chunhiem_thumbnail" => $chunhiem->thumbnail,
            ]);
            return $result;
        }  catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin hợp tác xã');
            return false;
           }
      
        
    }

    public function updateHTX($request){
        $id_hoptacxa = $this->getIDHopTacXaByToken();

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để cập nhật thông tin cho hợp tác xã này');
            return false;
        }

        $name_hoptacxa = $request->name_hoptacxa;
        $email = $request->email;
        $phone_number = $request->phone_number;
        $address = $request->address;
        $description = $request->description;

        $isNameExist = HopTacXa::where('name_hoptacxa', $name_hoptacxa)->whereNot('id_hoptacxa',$id_hoptacxa)->first();
        $isEmailExist = HopTacXa::where('email', $email)->whereNot('id_hoptacxa',$id_hoptacxa)->first();
        $isPhoneExist = HopTacXa::where('phone_number', $phone_number)->whereNot('id_hoptacxa',$id_hoptacxa)->first();

        if($isNameExist != null){
            Session::flash('error', 'Tên hợp tác xã đã tồn tại');
                return false;
        }
        if($isPhoneExist != null){
            Session::flash('error', 'Số điện thoại hợp tác xã đã tồn tại');
                return false;
        }
        if($isEmailExist != null){
            Session::flash('error', 'Email hợp tác xã đã tồn tại');
                return false;
        }
       
        try {
            DB::beginTransaction();

            $hoptacxa = HopTacXa::where('id_hoptacxa', $id_hoptacxa)->first();
            $hoptacxa->name_hoptacxa = $name_hoptacxa;
            $hoptacxa->phone_number = $phone_number;
            $hoptacxa->email = $email;
            $hoptacxa->address = $address;
            $hoptacxa->description = $description;
            if($request->hasFile('thumbnail')){
                if($hoptacxa->thumbnail != null){
                    $this->uploadImageService->delete($hoptacxa->thumbnail);
            }
            $hoptacxa->thumbnail = $this->uploadImageService->store($request->thumbnail);
            }
            if($request->hasFile('img_background')){
                if($hoptacxa->img_background != null){
                    $this->uploadImageService->delete($hoptacxa->img_background);
            }
            $hoptacxa->img_background = $this->uploadImageService->store($request->img_background);
            }
            $hoptacxa->save();
            DB::commit();

            $hoptacxa = $this->getDetail();
            return  $hoptacxa;
        } catch (\Exception $error) {
            Session::flash('error', 'Cập nhật thông tin hợp tác xã không thành công');
            return false;
        }

    }

    public function createNewHTX($request){
            $id_user = $this->commonService->getIDByToken();
            $name_hoptacxa = $request->input('name_hoptacxa');
            $email = $request->input('email');
            $phone_number = $request->input('phone_number');
            
            $isXaVien = XaVien::where('id_user', $id_user)->first();

            if($isXaVien == null){
                Session::flash('error', 'Không thể tạo hợp tác xã mới vì bạn không thuốc loại tài khoản xã viên');
                return false;
            }

            if($this->xaVienService->isXaVienHaveHTX($id_user)){
                Session::flash('error', 'Không thể tạo hợp tác xã mới vì bạn đã có hợp tác xã.');
                return false;
            }

            if(!$this->isNameExist($name_hoptacxa)){
                Session::flash('error', 'Tên hợp tác xã đã tồn tại');
                return false;
            }

            if(!$this->isEmailExist($email)){
                Session::flash('error', 'Email đã tồn tại');
                return false;
            }

            if(!$this->isPhoneExist($phone_number)){
                Session::flash('error', 'Số điện thoại đã tồn tại');
                return false;
            }

         try {
            if(!($this->xaVienService->isXaVienHaveHTX($id_user))){
                DB::beginTransaction();
                $hoptacxa = HopTacXa::create([
                    'name_hoptacxa' => $request->input('name_hoptacxa'),
                    'phone_number' => $request->input('phone_number'),
                    'email' => $request->input('email'),
                    'address' => $request->input('address'),
                    'active' => 0
                ]);
                // Update id_hoptacxa => Xa Vien
                
                if(!$this->xaVienService->addXaVienToHopTacXa($id_user,$hoptacxa->id_hoptacxa)){
                    DB::rollBack();
                    Session::flash('error', 'Không update được id_hoptacxa cho xã viên');
                    return false;
                }
                // Update Role For Xa Vien => Chủ Nhiệm Họp Tác Xã
                $id_role_chu_nhiem_htx = 2;
                if(!$this->xaVienService->updateRoleXaVien($id_user,$id_role_chu_nhiem_htx)){
                    DB::rollBack();
                    Session::flash('error', 'Không update được role cho xã viên');
                    return false;
                }
                DB::commit();
                return true;
             }
             DB::rollBack();
             Session::flash('error', "Không tạo được hợp tác xã. ".Session::get('error'));
             return false;
         } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
    }

    public function addNewMemberToHTX($request){
        try {
            $id_user = $request->id_user;
            $id_hoptacxa = $this->getIDHopTacXaByToken();
            
            if($id_user == $this->commonService->getIDByToken()){
                Session::flash('error', 'Bạn không thể tự gán hợp tác xã mới cho mình');
                return false;
            }

            if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
                Session::flash('error', 'Bạn không có quyền quản trị để thêm xã viên vào hợp tác xã này');
                return false;
            }

            if(!$this->isHTXActive($id_hoptacxa)){
                Session::flash('error', 'Hợp tác xã chưa được kích hoạt');
                return false;
            }

            if((!$this->xaVienService->isXaVienHaveHTX($id_user))){
                DB::beginTransaction();
                // Update id_hoptacxa => Xa Vien
                if(!$this->xaVienService->addXaVienToHopTacXa($id_user,$id_hoptacxa)){
                    DB::rollBack();
                    Session::flash('error', 'Không update được hoptacxa cho xã viên');
                    return false;
                }

                // Update Role For Xa Vien => Chủ Nhiệm Họp Tác Xã
                $id_role_xavien = 1;
                if(!$this->xaVienService->updateRoleXaVien($id_user,$id_role_xavien)){
                    DB::rollBack();
                    Session::flash('error', 'Không update được role cho xã viên');
                    return false;
                }

                // De-active thua dat cua xa vien khi vao htx
                $list_thuadat_xavien = ThuaDat::join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_thuadat.id_xavien')
                ->join('tbl_user', 'tbl_xavien.id_user', '=', 'tbl_user.id_user')
                ->select('tbl_thuadat.*')
                ->where('tbl_user.id_user', $id_user)
                ->get();
                
                foreach ($list_thuadat_xavien as $key => $thuadat) {
                    $thuadat->active = 0;
                    $thuadat->save();
                }
                
                DB::commit();
                return true;
             }
             DB::rollBack();
             Session::flash('error', 'Không thêm mới được xã viên vào hợp tác xã, Xã viên này đã có hợp tác xã '.Session::get('error'));
             return false;
         } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
    }
    

    public function deleteMemberToHTX($id_user){
        $id_hoptacxa = $this->getIDHopTacXaByToken();

        if($id_user == $this->commonService->getIDByToken()){
            Session::flash('error', 'Bạn không thể tự xóa mình khỏi hợp tác xã');
            return false;
        }

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để xóa xã viên khỏi hợp tác xã này');
            return false;
        }

        if(!$this->xaVienService->isXaVienBelongToHTX($id_user, $id_hoptacxa)){
            Session::flash('error', 'Xã viên này không thuộc hợp tác xã của bạn');
            return false;
        }

        try {
            DB::beginTransaction();
            if($this->xaVienService->deleteRoleAndHopTacXaIntoXaVien($id_user)){
            DB::commit();
                return true;
            }
            DB::rollBack();
            Session::flash('error', 'Không xóa được xã viên khỏi hợp tác xã '.Session::get('error'));
            return false;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Có lỗi trong lúc xóa, ' .$error);
            return false;
        }
    }

    public function toggleActiveMemberHTX($request){
        $id_user =  $request->id_user;
        $id_hoptacxa = $this->getIDHopTacXaByToken();

        if($id_user == $this->commonService->getIDByToken()){
            Session::flash('error', 'Bạn không thể tự cập nhật trạng thái của mình');
            return false;
        }

        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền quản trị để cập nhật trạng thái');
            return false;
        }

        if(!$this->xaVienService->isXaVienBelongToHTX($id_user, $id_hoptacxa)){
            Session::flash('error', 'Xã viên này không thuộc hợp tác xã của bạn');
            return false;
        }

        try {
            DB::beginTransaction();
            $xavien = XaVien::where('id_user', $id_user)->where('id_hoptacxa',$id_hoptacxa)->first();
            if($xavien->active == 1){
                $active = 0;
            }
            if($xavien->active == 0){
                $active = 1;
            }
            $xavien->active = $active;
            $xavien->save();

            DB::commit();

            return $xavien;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Có lỗi trong lúc cập nhật trạng thái');
            return false;
        }
    }

}