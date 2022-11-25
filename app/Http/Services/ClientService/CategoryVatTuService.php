<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Models\CategoryVatTu;
use App\Models\DanhMucQuyDinh;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CategoryVatTuService{
    protected $commonService;
    protected $thuongLaiService;

    public function __construct(CommonService $commonService, ThuongLaiService $thuongLaiService)
    {
        $this->commonService = $commonService;
        $this->thuongLaiService = $thuongLaiService;
    }

    public function autoCompleteCategoryVatTu($request){
        $id_danhmucquydinh = $request->id_danhmucquydinh;
        try {
            $listCategoryVatTu = CategoryVatTu::where('id_danhmucquydinh', $id_danhmucquydinh)
            ->Search($request)
            ->take(15)
            ->get();
            return $listCategoryVatTu;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tìm thấy kết quả');
            return false; 
        }

    }


    public function getDetailCategoryVatTu($id_category_vattu){
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
        try {
            $detailCategoryVatTu = CategoryVatTu::join('tbl_danhmucquydinh', 'tbl_danhmucquydinh.id_danhmucquydinh', 'tbl_category_vattu.id_danhmucquydinh')
            ->where('id_category_vattu',$id_category_vattu)
            ->where('tbl_danhmucquydinh.id_thuonglai', $id_thuonglai)
            ->select('tbl_category_vattu.*', 'tbl_danhmucquydinh.name_danhmucquydinh')
            ->first();

            if($detailCategoryVatTu == null){
                Session::flash('error', 'Vật tư không tồn tại');
                return false; 
            }

            return $detailCategoryVatTu->makeHidden(['id_thuonglai']);
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin vật tư');
            return false;
        }
    }

    public function getListCategoryVatTu($request){
        $id_thuonglai =  $this->thuongLaiService->getIdThuongLai();
        $id_danhmucquydinh = $request->danhmucquydinh;
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
            $data =  CategoryVatTu::join('tbl_danhmucquydinh', 'tbl_danhmucquydinh.id_danhmucquydinh', 'tbl_category_vattu.id_danhmucquydinh')
            ->select('tbl_category_vattu.*', 'tbl_danhmucquydinh.name_danhmucquydinh')
            ->where('tbl_category_vattu.id_danhmucquydinh',$id_danhmucquydinh)
            ->where('tbl_danhmucquydinh.id_thuonglai', $id_thuonglai)
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
            Session::flash('error', 'Danh sách vật tư định rỗng !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách vật tư quy định');
              return false;
          }
    }


    public function createCategoryVatTu($request){

        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
        $id_danhmucquydinh = $request->id_danhmucquydinh;
        $name_category_vattu = $request->name_category_vattu;
        $active = 1;
        
        if($id_thuonglai == null || $id_thuonglai == false){
            Session::flash('error','Bạn không phải là thương lái, không thể tạo danh mục quy định');
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

            $categoryVatTu = CategoryVatTu::create([
                'id_danhmucquydinh' => $id_danhmucquydinh,
                'name_category_vattu' => $name_category_vattu,
                'active' => $active
            ]);
            
            DB::commit();
            return $categoryVatTu;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error','Không thể tạo vật tư');
            return false;
        }
    }
    public function updateCategoryVatTu($request){

        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();
        $id_danhmucquydinh = $request->id_danhmucquydinh;
        $id_category_vattu = $request->id_category_vattu;
        $name_category_vattu = $request->name_category_vattu;
        $active = $request->active;
        
        if($id_thuonglai == null || $id_thuonglai == false){
            Session::flash('error','Bạn không phải là thương lái, không thể tạo danh mục quy định');
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

            $categoryVatTu = CategoryVatTu::where('id_danhmucquydinh', $id_danhmucquydinh)
                                        ->where('id_category_vattu', $id_category_vattu)->first();

            if($categoryVatTu == null){
                Session::flash('error','Vật tư không tồn tại hoặc bạn không phải là người sở hữu danh mục');
            return false;
            }
            $categoryVatTu->name_category_vattu = $name_category_vattu;
            $categoryVatTu->active = $active;
            $categoryVatTu->save();
            DB::commit();
            return $categoryVatTu;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error','Không cập tạo vật tư');
            return false;
        }
    }

    public function deleteCategoryVatTu($id_category_vattu){
        $id_thuonglai = $this->thuongLaiService->getIdThuongLai();

        if($id_thuonglai == null || $id_thuonglai == false){
            Session::flash('error','Bạn không phải là thương lái, không thể xóa danh mục quy định');
            return false;
        }

        $categoryVatTu = CategoryVatTu::join('tbl_danhmucquydinh', 'tbl_danhmucquydinh.id_danhmucquydinh', 'tbl_category_vattu.id_danhmucquydinh')
            ->where('id_category_vattu',$id_category_vattu)
            ->where('tbl_danhmucquydinh.id_thuonglai', $id_thuonglai)
            ->first();
        
        if($categoryVatTu == null){
            Session::flash('error','Vật tư thuộc danh mục quy định không tồn tại hoặc bạn không phải là người sở hữu danh mục');
            return false;
        }

        try {
            DB::beginTransaction();
            $categoryVatTu->delete();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error','Không thể xóa vật tư khỏi danh mục quy định');
            return false;
        }


    }
    
}