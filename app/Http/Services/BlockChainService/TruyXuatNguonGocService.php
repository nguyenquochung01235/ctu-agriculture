<?php

namespace App\Http\Services\BlockChainService;

use App\Http\Services\ClientService\HopTacXaService;
use App\Http\Services\CommonService;
use App\Models\GiaoDichMuaBanLua;
use App\Models\HopTacXa;
use App\Models\LichMuaVu;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TruyXuatNguonGocService{

    protected $commonService;

    public function __construct(
        CommonService $commonService
    )
    {
        $this->commonService = $commonService;
    }
    
    public function autoCompleteSearchHopTacXa($request){
        try {
        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;

        if ($page == null || $page == 0 || $page < 0) {
            $page = 1;
        }
        if ($limit == null || $limit == 0 || $limit < 0) {
            $limit = 15;
        }
        if ($search == null) {
            $search = "";
        }
        if ($order == null || $order == "") {
            $order = "id_hoptacxa";
        }
        if ($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")) {
            $sort = "desc";
        }

        $data = HopTacXa::where('name_hoptacxa', 'LIKE', "%$search%")
                    ->where('tbl_hoptacxa.active', 1)
                    ->join('tbl_lichmuavu', 'tbl_lichmuavu.id_hoptacxa','tbl_hoptacxa.id_hoptacxa')
                    ->select(
                        'tbl_hoptacxa.id_hoptacxa',
                        'tbl_hoptacxa.name_hoptacxa',
                        'tbl_hoptacxa.thumbnail',
                        'tbl_hoptacxa.phone_number',
                        'tbl_hoptacxa.address',
                        'tbl_lichmuavu.name_lichmuavu',
                        'tbl_lichmuavu.status',
                    );
        $total = $data->count();
        $meta = $this->commonService->pagination($total, $page, $limit);

        $result = $data
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->orderBy($order, $sort)
            ->get();

  
            return [$result, $meta];
        } catch (\Exception $error) {
            Session::flash('error', 'Có lỗi trong lúc truy xuất thông tin'. $error);
            return false;
        }
    }
    
    public function autoCompleteSearchLichMuaVu($request){
        try {
            $id_hoptacxa = $request->id_hoptacxa;
            $lichmuavu = LichMuaVu::where('id_hoptacxa', $id_hoptacxa)
            ->Search($request)
            ->join('tbl_gionglua','tbl_gionglua.id_gionglua', 'tbl_lichmuavu.id_gionglua')
            ->select(
                "tbl_lichmuavu.id_lichmuavu",
                "tbl_lichmuavu.name_lichmuavu",
                "tbl_lichmuavu.date_start",
                "tbl_lichmuavu.date_end",
                "tbl_gionglua.name_gionglua",
            )
            ->limit(10)
            ->get();
            return $lichmuavu;
        } catch (\Exception $error) {
            Session::flash('error', 'Có lỗi trong lúc truy xuất thông tin'. $error);
            return false;
        }
    }


    public function getListLoHangLua($request){
        try {
        $id_hoptacxa = $request->id_hoptacxa;
        $page = $request->page;
        $limit =  $request->limit;
        $search = $request->search;
        $order = $request->order;
        $sort = $request->sort;

        if ($page == null || $page == 0 || $page < 0) {
            $page = 1;
        }
        if ($limit == null || $limit == 0 || $limit < 0) {
            $limit = 15;
        }
        if ($search == null) {
            $search = "";
        }
        if ($order == null || $order == "") {
            $order = "id_giaodichmuaban_lua";
        }
        if ($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")) {
            $sort = "desc";
        }

        $data = GiaoDichMuaBanLua::join('tbl_lichmuavu', 'tbl_lichmuavu.id_lichmuavu', '=', 'tbl_giaodichmuaban_lua.id_lichmuavu')
            ->join('tbl_xavien', 'tbl_xavien.id_xavien', '=', 'tbl_giaodichmuaban_lua.id_xavien')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->join('tbl_thuonglai', 'tbl_thuonglai.id_thuonglai', '=', 'tbl_giaodichmuaban_lua.id_thuonglai')
            ->where('tbl_giaodichmuaban_lua.id_hoptacxa', $id_hoptacxa)
            ->Lichmuavu($request)
                ->select(
                    "tbl_giaodichmuaban_lua.id_giaodichmuaban_lua",
                    "tbl_giaodichmuaban_lua.name_lohang",
                    "tbl_user.fullname as name_xavien",
                    "tbl_thuonglai.name_thuonglai",
                    "tbl_giaodichmuaban_lua.img_lohang",
                    "tbl_giaodichmuaban_lua.soluong",
                    "tbl_giaodichmuaban_lua.price",
                );
        $total = $data->count();
        $meta = $this->commonService->pagination($total, $page, $limit);

        $result = $data
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->orderBy($order, $sort)
            ->get();

  
            return [$result, $meta];
        } catch (\Exception $error) {
            Session::flash('error', 'Có lỗi trong lúc truy xuất thông tin'. $error);
            return false;
        }
    }


}