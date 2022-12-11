<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\GiongLua;
use App\Models\ThuaDat;
use App\Models\XaVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ThuaDatService{

    protected $xaVienService;
    protected $commonService;
    protected $hopTacXaService;
    protected $uploadImageService;

    public function __construct(
        XaVienService $xaVienService,
        CommonService $commonService,
        HopTacXaService $hopTacXaService,
        UploadImageService $uploadImageService)
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->uploadImageService = $uploadImageService;
    }

    public function getListThuaDatOfXaVien($request){
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
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
            $order = "id_thuadat";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "asc";
        }

        if($request->id_xavien != null){

            $id_xavien = $request->id_xavien;
        }
        try {
            $thuadat = ThuaDat::where('id_xavien', $id_xavien)->get();
            if($thuadat == []){
                Session::flash('error', 'Bạn chưa có thửa đất nào');
                return false;
            }
            return $thuadat;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được thông tin danh sách thửa đất');
            return false;
        }
    }

    public function getDetailThuaDat($request){
        try{
            $id_thuadat =$request->id_thuadat;
            $thuadat = ThuaDat::where('id_thuadat', $id_thuadat)
            ->join('tbl_xavien', 'tbl_xavien.id_xavien', 'tbl_thuadat.id_xavien')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->select("tbl_thuadat.*", "tbl_user.fullname")
            ->first();
            if($thuadat == null){
                Session::flash('error', 'Không tồn tại thông tin thửa đất');
                return false;
            }
            return $thuadat;
        }catch(\Exception $error){
            Session::flash('error', 'Không lấy được thông tin thửa đất');
            return false;
        }
    }

    public function getListThuaDatOfHTX($request){
        $id_user = $this->commonService->getIDByToken();
        $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
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
            $order = "id_thuadat";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "asc";
        }

        
        if(!$this->xaVienService->checkXaVienIsChuNhiemHTX($id_hoptacxa)){
            Session::flash('error', 'Bạn không có quyền danh sách thửa đất !');
            return false;
        }

        try {
            $data = ThuaDat::join('tbl_xavien','tbl_xavien.id_xavien','=','tbl_thuadat.id_xavien')
            ->join('tbl_user','tbl_user.id_user','=','tbl_xavien.id_user')
            ->select("tbl_thuadat.*", "tbl_user.fullname","tbl_user.phone_number")
            ->where('tbl_xavien.id_hoptacxa', $id_hoptacxa)
            ->Search($request);

            $total = $data->count();
            $meta = $this->commonService->pagination($total,$page,$limit);
            $result = $data
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->orderBy($order, $sort)
            ->get();
            

            if($result != []){
              return [$result,$meta];
            }
            Session::flash('error', 'Danh sách thửa đất rỗng !');
            return false;
          } catch (\Exception $error) {
              Session::flash('error', 'Không lấy được danh sách thửa đất');
              return false;
          }
    }


    public function createThuaDat($request){
        $id_user = $this->commonService->getIDByToken();
        $xavien = XaVien::where('id_user', $id_user)->first('id_xavien');
        $thumbnail = "";
        $location = "";
        $dientich = null;
        try {
            DB::beginTransaction();
            try {
                if($request->has('thumbnail')){
                    $thumbnail = $this->uploadImageService->store($request->thumbnail);
                    }
            } catch (\Exception $error) {
                Session::flash('error',"Lỗi ở upload hình ảnh");
                return false;
            }
            if($request->location != null){
                $location = $request->location;
            }
            if($request->dientich != null){
                $dientich = $request->dientich;
            }
            $thuadat = ThuaDat::create([
                "id_xavien" => $xavien->id_xavien,
                "address" => $request->address,
                "location"=> $location,
                "dientich" => $dientich,
                "thumbnail" => $thumbnail,
                "description" => $request->description,
                "active" => 1,
            ]);
            DB::commit();
            return $thuadat;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', "Không thể tạo thửa đất" . $error);
            return false;
        }
    }
    public function updateThuaDat($request){
        try {
            $id_user = $this->commonService->getIDByToken();
            $xavien = XaVien::where('id_user', $id_user)->first();
            if($xavien == null){
                Session::flash('error',"Bạn không phải là xã viên, khồng có thể chỉnh sửa thửa đất");
                return false;
            }

            $thuadat = ThuaDat::where('id_thuadat', $request->id_thuadat)->where('id_xavien', $xavien->id_xavien)->first();
            if($thuadat == null){
                Session::flash('error',"Thửa đất không tồn tại");
                return false;
            }

            DB::beginTransaction();
            $thuadat->address = $request->address;
            $thuadat->location = $request->location;
            $thuadat->dientich = $request->dientich;
            $thuadat->description = $request->description;
            try {
                if($request->hasFile('thumbnail')){
                    if($thuadat->thumbnail != null){
                    $this->uploadImageService->delete($thuadat->thumbnail);
                    }
                    $thuadat->thumbnail = $this->uploadImageService->store($request->thumbnail);
                    }
            } catch (\Exception $error) {
                Session::flash('error',"Lỗi ở upload hình ảnh" );
                return false;
            }
            $thuadat->save();
            DB::commit();
            return $thuadat;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
    }

    public function activeThuaDat($request){
        $id_thuadat = $request->id_thuadat;
        try {
            DB::beginTransaction();
            $thuadat = ThuaDat::where('id_thuadat', $id_thuadat)->first();
            if($thuadat->active == 1){
                $active = 0;
            }
            if($thuadat->active == 0){
                $active = 1;
            }
            $thuadat->active = $active;
            $thuadat->save();
            DB::commit();
            return $thuadat;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
    }

    public function deleteThuaDat($id_thuadat){
        try {
            $id_xavien = $this->xaVienService->getIdXaVienByToken();
            DB::beginTransaction();
            $thuadat = ThuaDat::where('id_thuadat', $id_thuadat)->where('id_xavien', $id_xavien)->first();

            if($thuadat == null){
                Session::flash('error', 'Thửa đất không tồn tại');
                return false;
            }
            $thuadat->delete();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', $error);
            return false;
        }
    }
    

}