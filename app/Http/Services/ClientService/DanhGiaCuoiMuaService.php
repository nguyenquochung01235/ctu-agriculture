<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Comment;
use App\Models\DanhGiaCuoiMua;
use App\Models\LichMuaVu;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DanhGiaCuoiMuaService
{

    protected $commonService;
    protected $xaVienService;
    protected $hopTacXaService;
    protected $notificationService;

    public function __construct(
        CommonService $commonService,
        XaVienService $xaVienService,
        HopTacXaService $hopTacXaService,
        NotificationService $notificationService)
    {
        $this->commonService = $commonService;
        $this->xaVienService = $xaVienService;
        $this->hopTacXaService = $hopTacXaService;
        $this->notificationService = $notificationService;
    }

   public function getDetailDanhGiaCuoiMua($request){
        $id_danhgiacuoimua = $request->id_danhgiacuoimua;
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
        try{
            $danhgiacuoimua = DanhGiaCuoiMua::where('id_danhgiacuoimua', $id_danhgiacuoimua)->where('tbl_danhgiacuoimua.id_xavien', $id_xavien)
                ->join('tbl_xavien','tbl_xavien.id_xavien', 'tbl_danhgiacuoimua.id_xavien')
                ->join('tbl_user','tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', 'tbl_danhgiacuoimua.id_lichmuavu')
                ->select(
                    'tbl_danhgiacuoimua.*',
                    'tbl_lichmuavu.name_lichmuavu',
                    'tbl_lichmuavu.date_start',
                    'tbl_lichmuavu.date_end',
                    'tbl_user.fullname',
                )
                ->first();
                if($danhgiacuoimua == null){
                    Session::flash('error', 'Không tìm thấy đánh giá cuối mùa');
                    return false;
                }
                return $danhgiacuoimua;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được chi tiết đánh giá cuối mùa !');
            return false;
        }
   
    }
   public function getListDanhGiaCuoiMua($request){
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
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
            $order = "tbl_danhgiacuoimua.created_at";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "desc";
        }

        try{
            $data = DanhGiaCuoiMua::where('tbl_danhgiacuoimua.id_xavien', $id_xavien)

                ->join('tbl_xavien','tbl_xavien.id_xavien', 'tbl_danhgiacuoimua.id_xavien')
                ->join('tbl_user','tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', 'tbl_danhgiacuoimua.id_lichmuavu')
                ->where('tbl_lichmuavu.id_hoptacxa', $id_hoptacxa)
                ->select(
                    'tbl_danhgiacuoimua.id_danhgiacuoimua',
                    'tbl_lichmuavu.name_lichmuavu',
                    'tbl_lichmuavu.date_start',
                    'tbl_lichmuavu.date_end',
                    'tbl_user.fullname',
                )
                ->LichMuaVu($request);

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
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách đánh giá cuối mùa !');
            return false;
        }
   }
   public function getListDanhGiaCuoiMuaHTX($request){
        $id_xavien = $this->xaVienService->getIdXaVienByToken();
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
            $order = "tbl_danhgiacuoimua.created_at";
        }
        if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
            $sort = "desc";
        }

        try{
            $data = DanhGiaCuoiMua::join('tbl_xavien','tbl_xavien.id_xavien', 'tbl_danhgiacuoimua.id_xavien')
                ->join('tbl_user','tbl_user.id_user', 'tbl_xavien.id_user')
                ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', 'tbl_danhgiacuoimua.id_lichmuavu')
                ->where('tbl_lichmuavu.id_hoptacxa', $id_hoptacxa)
                ->select(
                    'tbl_danhgiacuoimua.id_danhgiacuoimua',
                    'tbl_lichmuavu.name_lichmuavu',
                    'tbl_lichmuavu.date_start',
                    'tbl_lichmuavu.date_end',
                    'tbl_user.fullname',
                )
                ->LichMuaVu($request);

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
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách đánh giá cuối mùa !');
            return false;
        }
   }

   public function createDanhGiaCuoiMua($request){
    $id_xavien = $this->xaVienService->getIdXaVienByToken();
    $id_hoptacxa = $this->hopTacXaService->getIDHopTacXaByToken();
    $id_lichmuavu = $request->id_lichmuavu;
    $lichmuavu = LichMuaVu::where('id_lichmuavu',$id_lichmuavu)->first();
    if($lichmuavu == null){
        Session::flash('error', 'Không có lịch mùa vụ để tạo đánh giá !');
        return false;
    }

    $danhgiacuoimua = DanhGiaCuoiMua::where('id_xavien', $id_xavien)->where('id_lichmuavu', $lichmuavu->id_lichmuavu)->first();
    if($danhgiacuoimua != null){
        Session::flash('error', 'Bạn đã tạo đánh giá cho mùa vụ này rồi, không thể tạo thêm');
        return false;
    }

    try {
        DB::beginTransaction();
        $danhgiacuoimua = DanhGiaCuoiMua::create([
            'id_lichmuavu'=>$lichmuavu->id_lichmuavu,
            'id_xavien'=>$id_xavien,
            'giong'=>$request->giong,
            'phanbon'=>$request->phanbon,
            'xangdau'=>$request->xangdau,
            'vattukhac'=>$request->vattukhac,
            'lamdat'=>$request->lamdat,
            'gieosa'=>$request->gieosa,
            'lamco'=>$request->lamco,
            'bomtuoi'=>$request->bomtuoi,
            'thuhoach'=>$request->thuhoach,
            'rahat'=>$request->rahat,
            'phoisay'=>$request->phoisay,
            'vanchuyen'=>$request->vanchuyen,
            'thuyloiphi'=>$request->thuyloiphi,
            'tongsanluong'=>$request->tongsanluong,
            'giaban'=>$request->giaban,
            'khokhan'=>$request->khokhan,
            'kiennghi'=>$request->kiennghi,
        ]);
        DB::commit();
        return $this->getDetailDanhGiaCuoiMua($danhgiacuoimua);
    } catch (\Exception $error) {
        Session::flash('error', 'Không tạo được đánh giá cuối mùa !' . $error);
        return false;
    }
   
}
   public function updateDanhGiaCuoiMua($request){
    $id_danhgiacuoimua = $request->id_danhgiacuoimua;
    $id_xavien = $this->xaVienService->getIdXaVienByToken();

    $danhgiacuoimua = DanhGiaCuoiMua::where('id_xavien', $id_xavien)
    ->where('id_danhgiacuoimua', $id_danhgiacuoimua)->first();
    if($danhgiacuoimua == null){
        Session::flash('error', 'Dánh giá cuối mùa không tồn tại');
        return false;
    }

    try {
        DB::beginTransaction();
        $danhgiacuoimua->giong= $request->giong;
        $danhgiacuoimua->phanbon= $request->phanbon;
        $danhgiacuoimua->xangdau= $request->xangdau;
        $danhgiacuoimua->vattukhac= $request->vattukhac;
        $danhgiacuoimua->lamdat= $request->lamdat;
        $danhgiacuoimua->gieosa= $request->gieosa;
        $danhgiacuoimua->lamco= $request->lamco;
        $danhgiacuoimua->bomtuoi= $request->bomtuoi;
        $danhgiacuoimua->thuhoach= $request->thuhoach;
        $danhgiacuoimua->rahat= $request->rahat;
        $danhgiacuoimua->phoisay= $request->phoisay;
        $danhgiacuoimua->vanchuyen= $request->vanchuyen;
        $danhgiacuoimua->thuyloiphi= $request->thuyloiphi;
        $danhgiacuoimua->tongsanluong= $request->tongsanluong;
        $danhgiacuoimua->giaban= $request->giaban;
        $danhgiacuoimua->khokhan= $request->khokhan;
        $danhgiacuoimua->kiennghi= $request->kiennghi;
        $danhgiacuoimua->save();
        DB::commit();
        return $this->getDetailDanhGiaCuoiMua($danhgiacuoimua);
    } catch (\Exception $error) {
        Session::flash('error', 'Không cập nhật được đánh giá cuối mùa !' . $error);
        return false;
    }
   }

   
   public function deleteDanhGiaCuoiMua($request){
    $id_danhgiacuoimua = $request->id_danhgiacuoimua;
    $id_xavien = $this->xaVienService->getIdXaVienByToken();

    $danhgiacuoimua = DanhGiaCuoiMua::where('id_xavien', $id_xavien)
    ->where('id_danhgiacuoimua', $id_danhgiacuoimua)->first();
    if($danhgiacuoimua == null){
        Session::flash('error', 'Dánh giá cuối mùa không tồn tại');
        return false;
    }

    try {
        DB::beginTransaction();
        $danhgiacuoimua->delete();
        DB::commit();
        return true;
    } catch (\Exception $error) {
        Session::flash('error', 'Không xóa được đánh giá cuối mùa !' . $error);
        return false;
    }
   }
}
