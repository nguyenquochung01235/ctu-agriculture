<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\Account;
use App\Models\DanhMucQuyDinh;
use App\Models\ThuongLai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class DanhMucQuyDinhService{
    
    protected $commonService;
    protected $thuongLaiService;

    public function __construct(CommonService $commonService, ThuongLaiService $thuongLaiService)
    {
        $this->commonService = $commonService;
        $this->thuongLaiService = $thuongLaiService;
    }

    public function getDetailDanhMucQuyDinh($id_danhmucquydinh){
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
        try {
            $detailDanhMucQuyDinh = DanhMucQuyDinh::where('id_danhmucquydinh', $id_danhmucquydinh)
            ->where('id_thuonglai', $id_thuonglai)
            ->first();

            if($detailDanhMucQuyDinh == null){
                Session::flash('error', 'Danh mục không tồn tại');
                return false; 
            }

            return $detailDanhMucQuyDinh;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh mục');
            return false;
        }
    }

    public function getListDanhMucQuyDinh($request){
        $id_thuonglai =  $this->thuongLaiService->getIdThuongLai();
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
            $order = "created_at";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "desc";
        }
        
        try {
            $data =  DanhMucQuyDinh::where('id_thuonglai', $id_thuonglai)
            ->Search($request);

            $total = $data->count();
            $meta = $this->commonService->pagination($total,$page,$limit);

            $result =  $data
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->orderBy($order, $sort)
            ->get();
            
            
            if($result != []){
              return [$result,$meta];
            }
            Session::flash('error', 'Danh sách quy định rỗng !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách quy định');
              return false;
          }
    }

    
    public function createDanhMucQuyDinh($request){
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
        if($id_thuonglai == null || $id_thuonglai == false){
            Session::flash('error','Bạn không phải là thương lái, không thể tạo danh mục quy định');
            return false;
        }

        $name_danhmucquydinh = $request->name_danhmucquydinh;
        if(DanhMucQuyDinh::where('id_thuonglai', $id_thuonglai)->where('name_danhmucquydinh', $name_danhmucquydinh)->count() > 0){
            Session::flash('error','Tên danh mục đã tồn tại');
            return false;
        }

        try {
            DB::beginTransaction();
            $danhMucQuyDinh = DanhMucQuyDinh::create([
                'id_thuonglai' => $id_thuonglai,
                'name_danhmucquydinh' => $name_danhmucquydinh,
                'active' => 1,
            ]);
            DB::commit();
            return $danhMucQuyDinh->makeHidden(['id_thuonglai','active']);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error','Không thể tạo danh mục quy định');
            return false;
        }
    }
    public function updateDanhMucQuyDinh($request){
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();

        if($id_thuonglai == null || $id_thuonglai == false){
            Session::flash('error','Bạn không phải là thương lái, không thể cập nhật danh mục quy định');
            return false;
        }

        $id_danhmucquydinh = $request->id_danhmucquydinh;
        $name_danhmucquydinh = $request->name_danhmucquydinh;

        if( DanhMucQuyDinh::where('id_thuonglai', $id_thuonglai)
            ->where('id_danhmucquydinh', $id_danhmucquydinh)->first() == null)
        {
            Session::flash('error','Danh mục quy định không tồn tại hoặc bạn không phải là người sở hữu danh mục');
            return false;
        }

        if(DanhMucQuyDinh::where('id_thuonglai', $id_thuonglai)
            ->where('name_danhmucquydinh', $name_danhmucquydinh)
            ->whereNot('id_danhmucquydinh', $id_danhmucquydinh)
            ->count() > 0){
            Session::flash('error','Tên danh mục đã tồn tại');
            return false;
        }

        try {
            DB::beginTransaction();
            $danhMucQuyDinh = DanhMucQuyDinh::where('id_thuonglai', $id_thuonglai)
            ->where('id_danhmucquydinh', $id_danhmucquydinh)->first();

            $danhMucQuyDinh->name_danhmucquydinh = $name_danhmucquydinh;
            $danhMucQuyDinh->save();

            DB::commit();
            return $danhMucQuyDinh->makeHidden(['id_thuonglai','active']);
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error','Không thể tạo danh mục quy định');
            return false;
        }
    }

    public function deleteDanhMucQuyDinh($id_danhmucquydinh){
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();

        if($id_thuonglai == null || $id_thuonglai == false){
            Session::flash('error','Bạn không phải là thương lái, không thể xóa danh mục quy định');
            return false;
        }

        $danhMucQuyDinh = DanhMucQuyDinh::where('id_thuonglai', $id_thuonglai)
                        ->where('id_danhmucquydinh', $id_danhmucquydinh)->first();
        
        if($danhMucQuyDinh == null){
            Session::flash('error','Danh mục quy định không tồn tại hoặc bạn không phải là người sở hữu danh mục');
            return false;
        }

        try {
            DB::beginTransaction();
            $danhMucQuyDinh->delete();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error','Không thể xóa danh mục quy định');
            return false;
        }


    }
}