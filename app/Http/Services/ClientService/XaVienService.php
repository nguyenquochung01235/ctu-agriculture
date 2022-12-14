<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Account;
use App\Models\LichMuaVu;
use App\Models\RoleXaVien;
use App\Models\User;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class XaVienService{

    protected $commonService;
    protected $uploadImageService;


    public function __construct(CommonService $commonService, UploadImageService $uploadImageService)
    {
        $this->commonService = $commonService;
        $this->uploadImageService = $uploadImageService;
    }

    public function getIdXaVienByToken(){
        try {
            $id_user = $this->commonService->getIDByToken();
            $xavien = XaVien::where('id_user', $id_user)->first();
            if($xavien == null){
                Session::flash('error', 'Xã viên không tồn tại');
                return false;
            }
            return $xavien->id_xavien;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin xã viên');
            return false;
        }
    }

    public function checkXaVienIsChuNhiemHTX($id_hoptacxa){
        try {
            $id_user = $this->commonService->getIDByToken();
            $xavien = XaVien::with('role')->where('id_user', $id_user)->where('id_hoptacxa', $id_hoptacxa)->first();
            if($xavien == null){
                Session::flash('error', 'Xã viên này không thuộc hợp tác xã');
                return false;
            }

            $role_xavien = $xavien->role[0]->role;
            if($role_xavien != "chunhiem"){
                Session::flash('error', 'Xã viên này không có quyền quản trị của hợp tác xã');
                return false;
            }
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không xác định được xã viên hoặc hợp tác xã');
            return false;
        }
      
    }

    public function isXaVienBelongToHTX($id_user, $id_hoptacxa){
        
        try {
            $result = XaVien::where('id_user', $id_user)->where('id_hoptacxa', $id_hoptacxa)->first('id_hoptacxa');
            if($result->id_hoptacxa == null) {
                Session::flash('error','Xã viên này không thuộc hợp tác xã !');
                return false;
            }
        } catch (\Exception $error) {
            Session::flash('error','Xã viên này không tồn tại');
            return false;
        }
        return true;
    }

    public function isXaVienHaveHTX($id_user){
        
        try {
            $result = XaVien::where('id_user', $id_user)->first('id_hoptacxa');
            if($result->id_hoptacxa == null) {
                Session::flash('error','Xã viên này chưa có hợp tác xã');
                return false;
            }
        } catch (\Exception $error) {
            Session::flash('error','Xã viên này không tồn tại');
            return false;
        }
        return true;
    }

    public function isXaVienExist($id_user){
        
        try {
            $result = XaVien::where('id_user', $id_user)->count();
            if($result == 0) {
                Session::flash('error','Xã viên không tồn tại !');
                return false;
            }
        } catch (\Exception $error) {
            Session::flash('error','Xã viên này không tồn tại');
            return false;
        }
        return true;
    }

    public function getDetail($id_user){
        if($id_user == null){
            $id_user = $this->commonService->getIDByToken();
        }else{
            $id_hoptacxa = XaVien::where('id_user', $id_user)->first()->id_hoptacxa;
            if(!$this->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
                Session::flash('error','Bạn không có quyền xem thông tin chi tiết xã viên');
                return false;
            }
        }

        try {
            $xavien = XaVien::join('tbl_user', 'tbl_xavien.id_user', '=', 'tbl_user.id_user')
            ->join('tbl_hoptacxa', 'tbl_hoptacxa.id_hoptacxa', '=', 'tbl_xavien.id_hoptacxa')
            ->select(
                'tbl_user.fullname',
                'tbl_user.email',
                'tbl_user.phone_number',
                'tbl_user.address',
                'tbl_user.dob',
                'tbl_xavien.thumbnail',
                'tbl_xavien.img_background',
                'tbl_xavien.description',
                'tbl_hoptacxa.name_hoptacxa',
            )
            ->where('tbl_xavien.id_user', $id_user)
            ->first();
            if($xavien == null){
                Session::flash('error','Xã viên không tồn tại');
            return false;
            }
            return $xavien;
        } catch (\Exception $error) {
            Session::flash('error','Lấy thông tin không thành công');
            return false;
        }
    }

    public function getListXaVienOfHTX($request){
        $id_user = $this->commonService->getIDByToken();
        $id_hoptacxa = XaVien::where('id_user',$id_user)->first()->id_hoptacxa;
        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;

        if($page == null || $page == 0 || $page < 0){
            $page = 1;
        }
        if($limit == null || $limit == 0 || $limit < 0){
            $limit = 15;
        }
        if($search == null){
            $search = "";
        }
        if($order == null || $order == ""){
            $order = "id_xavien";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "desc";
        }

        if(!$this->isXaVienBelongToHTX($this->commonService->getIDByToken(), $id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền xem danh sách xã viên của hợp tác xã này');
            return false;
        }

        try {

            $data =  XaVien::join('tbl_user', 'tbl_xavien.id_user', '=', 'tbl_user.id_user')
            ->select('*', 'tbl_xavien.active as xavien_active', 'tbl_user.active as user_active')            
            ->where('id_hoptacxa',$id_hoptacxa)
            ->XaVien($request)
            ->User($request)
            ->Search($request);

            $total = $data->count();
            $meta = $this->commonService->pagination($total,$page,$limit);
            $result = $data->skip(($page-1)*$limit)
                ->take($limit)
                ->orderBy($order, $sort)
                ->get();
            
            if($result != []){
             return [ $result, $meta];
            }
            Session::flash('error', 'Chưa có xã viên trong hợp tác xã của bạn');
             return false;
         } catch (\Exception $error) {
             Session::flash('error', 'Không lấy được danh sách');
             return false;
         }
    }

    public function getRoleXaVien($request){        
        try {
            $account = $request->type;
            $id_user = $this->commonService->getIDByToken();
            $xavien =  XaVien::with('role')->with('hop_tac_xa')->where('id_user',$id_user)->first();
            $hoptacxa_active = false;
            if($xavien->hop_tac_xa->active == 1){
                $hoptacxa_active = true;
            }
            $role = $xavien->role[0]->role;
            if($account != null){
                switch ($account) {
                    case 'xavien':
                        $role = 'xavien';
                        break;
               
                    case 'chunhiem':
                        if($role != 'chunhiem'){
                        Session::flash('error', 'Tài khoản không có phân quyền chủ nhiệm');
                            return false;
                        }
                        $role = 'chunhiem';
                        break;
                    
                    default:
                        Session::flash('error', 'Không xác nhận được thông tin phân quyền');
                        return false;
                        break;
                }
            }else{
                $role = $xavien->role[0]->role;
            }
            
            $result = ([
                'id_hoptacxa'=> $xavien->id_hoptacxa,
                'name_hoptacxa'=> $xavien->hop_tac_xa->name_hoptacxa,
                'role'=> $role,
                'active'=>$hoptacxa_active
            ]);
            return  $result;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được role của user');
            return false;
        }
    }

    public function searchXaVienByPhoneNumber($request){
        
        try {
            $phone_number = $request->phone_number;
            $type = $request->type;
          
            $xavien = XaVien::join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
                    ->leftJoin('tbl_hoptacxa', 'tbl_hoptacxa.id_hoptacxa', 'tbl_xavien.id_hoptacxa')
                    ->where('tbl_user.phone_number', $phone_number);

            if($xavien->first() == null){
                Session::flash('error', 'Không tìm thấy xã viên !');
                return false;
            }
            
            if($type != null){
                switch ($type) {
                    case 'giaodichmuabanluagiong':
                        if($xavien->first()->id_hoptacxa == null){
                            Session::flash('error', 'Xã viên không thuộc hợp tác xã, không thể tạo giao dịch !');
                            return false;
                        }
                        $result = $xavien->join('tbl_lichmuavu', 'tbl_lichmuavu.id_hoptacxa', 'tbl_xavien.id_hoptacxa')
                                ->whereIn('tbl_lichmuavu.status', ['start', 'upcoming'])
                                ->select(
                                    'tbl_xavien.id_xavien',
                                    'tbl_user.id_user',
                                    'tbl_xavien.id_hoptacxa',
                                    'tbl_user.fullname',
                                    'tbl_user.phone_number as xavien_phone_number',
                                    'tbl_user.address',
                                    'id_lichmuavu',
                                    'name_lichmuavu',
                                    'name_hoptacxa',
                                    'tbl_hoptacxa.phone_number as hoptacxa_phone_number',
                                )
                                ->first();
                        
                        if($result->id_lichmuavu == null){
                            Session::flash('error', 'Xã viên này không có lịch mùa vụ sẵn sàng để tạo giao dịch !');
                            return false;
                        }
                        
    
                        break;

                    case 'giaodichmuabanvattu':
                        if($xavien->first()->id_hoptacxa == null){
                            Session::flash('error', 'Xã viên không thuộc hợp tác xã, không thể tạo giao dịch !');
                            return false;
                        }
                        $result = $xavien->join('tbl_lichmuavu', 'tbl_lichmuavu.id_hoptacxa', 'tbl_xavien.id_hoptacxa')
                                ->join('tbl_hopdongmuaban', 'tbl_hopdongmuaban.id_lichmuavu', 'tbl_lichmuavu.id_lichmuavu')
                                ->join('tbl_danhmucquydinh', 'tbl_hopdongmuaban.id_danhmucquydinh', 'tbl_danhmucquydinh.id_danhmucquydinh')
                                ->whereIn('tbl_lichmuavu.status', ['start', 'upcoming'])
                                ->select(
                                    'tbl_xavien.id_xavien',
                                    'tbl_user.id_user',
                                    'tbl_xavien.id_hoptacxa',
                                    'tbl_user.fullname',
                                    'tbl_user.phone_number as xavien_phone_number',
                                    'tbl_user.address',
                                    'tbl_lichmuavu.id_lichmuavu as id_lichmuavu',
                                    'tbl_lichmuavu.name_lichmuavu as name_lichmuavu',
                                    'tbl_hoptacxa.name_hoptacxa',
                                    'tbl_hoptacxa.phone_number as hoptacxa_phone_number',
                                    'tbl_hopdongmuaban.id_hopdongmuaban',
                                    'tbl_hopdongmuaban.title_hopdongmuaban',
                                    'tbl_hopdongmuaban.id_danhmucquydinh',
                                    'tbl_danhmucquydinh.name_danhmucquydinh',
                                )
                                ->first();
                 

                        if($result == null){
                            Session::flash('error', 'Xã viên này không có lịch mùa vụ sẵn sàng để tạo giao dịch hoặc chưa có hợp đồng quy định vật tư sử dụng với thương lái!');
                            return false;
                        }
                        
    
                        break;
                    
                    default:
                        Session::flash('error', 'Tìm kiếm không thành công, định dạng không khớp dữ liệu');
                        return false;
                        break;
                }
            }else{
                $result = $xavien
                    ->select(
                        'tbl_user.id_user',
                        'tbl_xavien.id_xavien',
                        'tbl_user.fullname',
                        'tbl_user.phone_number',
                        'tbl_user.address',
                        'tbl_user.dob',
                        'tbl_xavien.thumbnail',
                        'tbl_xavien.id_hoptacxa',
                        'tbl_hoptacxa.name_hoptacxa',
                        'tbl_hoptacxa.phone_number as hoptacxa_phone_number',
                    )
                    ->first();
            }


            if($result == null){
                Session::flash('error', 'Không tim thấy xã viên');
             return false;
            }
            return $result;
            
         } catch (\Exception $error) {
             Session::flash('error', 'Tìm kiếm không thành công' . $error);
             return false;
         }
    }
    
    public function createXaVien($id_user){
        try {
            XaVien::create([
                'id_user' => $id_user,
                'active' => 1
            ]);
            $xavien = User::where('id_user', $id_user)->first();
            $account = Account::where('code', '1')->first();
            $xavien->account()->attach($account);
        } catch (\Exception $error) {
            Session::flash('error', $error);
            return false;
        }
        return true;
    }

    public function updateXavien($request){
        try {
            
            $id_user = $this->commonService->getIDByToken();
            $xavien = XaVien::where('id_user', $id_user)->first();
            if($xavien == null){
                Session::flash('error', "Xã viên không tồn tại");
                return false;
            }
            DB::beginTransaction();
            $xavien->description = $request->description;
            if($request->hasFile('thumbnail')){
                if($xavien->thumbnail != null){
                    $this->uploadImageService->delete($xavien->thumbnail);
            }
            return dd($xavien->thumbnail);
            $xavien->thumbnail = $this->uploadImageService->store($request->thumbnail);
            }
            if($request->hasFile('img_background')){
                if($xavien->img_background != null){
                    $this->uploadImageService->delete($xavien->img_background);
            }
            $xavien->img_background = $this->uploadImageService->store($request->img_background);
            }
            $xavien->save();
            DB::commit();
            return $this->getDetail(null);
        } catch (\Exception $error) {
            Session::flash('error', "Không cập nhật được thông tin $error");
            return false;
        }
        return true;
    }
    
    public function addXaVienToHopTacXa($id_user,$id_hoptacxa){

        if(!$this->isXaVienExist($id_user)){
            Session::flash('error', 'Xã viên không tồn tại.');
            return false;
        }

        try {
            $xavien = XaVien::where('id_user', $id_user)->first();
            DB::beginTransaction();
            if($xavien->id_hoptacxa == null){
                $xavien->id_hoptacxa = $id_hoptacxa;
                $xavien->save();
                DB::commit();
                return true;
            }
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
        DB::rollBack();
        Session::flash('error', 'Xã viên này đã thuộc về một hợp tác xã nào đó');
        return false;
    }

    public function updateRoleXaVien($id_user,$id_role){
        try {
            $xavien = XaVien::where('id_user', $id_user)->first();
            $xavien->role()->detach();
            $role = RoleXaVien::where('id_role', $id_role)->first();
            $xavien->role()->attach($role);
        } catch (\Exception $error) {
            Session::flash('error', $error);
            return false;
        }
        return true;
    }
    

    public function deleteRoleAndHopTacXaIntoXaVien($id_user){
        try {
        DB::beginTransaction();
            $xavien = XaVien::where('id_user', $id_user)->first();
            $xavien->role()->detach();
            $xavien->id_hoptacxa = null;
            $xavien->save();
        DB::commit();
        } catch (\Exception $error) {
            Session::flash('error', $error);
            return false;
        DB::rollBack();
        }
        return true;
    }

}