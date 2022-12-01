<?php

namespace App\Http\Services\BlockChainService;

use App\Http\Services\ClientService\GiaoDichMuaBanLuaGiongService;
use App\Http\Services\ClientService\GiaoDichMuaBanLuaService;
use App\Http\Services\ClientService\HopTacXaService;
use App\Http\Services\ClientService\NhatKyDongRuongService;
use App\Http\Services\CommonService;
use App\Models\GiaoDichMuaBanLua;
use App\Models\HopTacXa;
use App\Models\LichMuaVu;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TruyXuatNguonGocService{

    protected $BASE_API_URL_BLOCKCHAIN = "http://45.32.55.194/api/v1/blockchain";

    protected $commonService;
    protected $giaoDichMuaBanLuaService;
    protected $giaoDichMuaBanLuaGiongService;
    protected $nhatKyDongRuongService;
    

    public function __construct(
        CommonService $commonService,
        GiaoDichMuaBanLuaService $giaoDichMuaBanLuaService,
        GiaoDichMuaBanLuaGiongService $giaoDichMuaBanLuaGiongService,
        NhatKyDongRuongService $nhatKyDongRuongService
    )
    {
        $this->commonService = $commonService;
        $this->giaoDichMuaBanLuaService = $giaoDichMuaBanLuaService;
        $this->giaoDichMuaBanLuaGiongService = $giaoDichMuaBanLuaGiongService;
        $this->nhatKyDongRuongService = $nhatKyDongRuongService;
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
                    ->select(
                        'tbl_hoptacxa.id_hoptacxa',
                        'tbl_hoptacxa.name_hoptacxa',
                        'tbl_hoptacxa.thumbnail',
                        'tbl_hoptacxa.phone_number',
                        'tbl_hoptacxa.address',
                        'tbl_hoptacxa.description',
                        'tbl_hoptacxa.active',
                        
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
            ->LichMuaVu($request)
                ->select(
                    "tbl_giaodichmuaban_lua.id_giaodichmuaban_lua",
                    "tbl_giaodichmuaban_lua.name_lohang",
                    "tbl_user.fullname as name_xavien",
                    "tbl_thuonglai.name_thuonglai",
                    "tbl_giaodichmuaban_lua.img_lohang",
                    "tbl_giaodichmuaban_lua.soluong",
                    "tbl_giaodichmuaban_lua.price",
                    "tbl_giaodichmuaban_lua.updated_at",
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

    public function truyXuatLoHangLua($request){
        try {
            $id_giaodichmuaban_lua = $request->id_giaodichmuaban_lua;
            $response = Http::get($this->BASE_API_URL_BLOCKCHAIN."/tracing/rice-product/$id_giaodichmuaban_lua");

            $data_response = json_decode($response->body())->results->hoatdong;
            
            $giaoDichMuaBanLuaResponseBlockChain = $data_response->hoatdongmuabanlua;
            $hoatDongMuaBanLuaGiongResponseBlockChain = $data_response->hoatDongMuaBanGiongLua;
            $nhatKyHoatDongResponseBlockChain = $data_response->danhsachhoatdongnhatky;

            $id_giaodichmuaban_lua = $giaoDichMuaBanLuaResponseBlockChain->id_giaodich;
            $id_giaodich_luagiong = $hoatDongMuaBanLuaGiongResponseBlockChain->id_giaodichluagiong;
            
            
            $detail_giaodichmuaban_lua = $this->giaoDichMuaBanLuaService->getDetailGiaoDichMuaBanLua($id_giaodichmuaban_lua);
            $detail_giaodich_luagiong =(object) $this->giaoDichMuaBanLuaGiongService->getDetailGiaoDichMuaBanLuaGiong($id_giaodich_luagiong);
            
            

            $giaodich_luagiong = ([
                "updated_at"=> $detail_giaodich_luagiong->updated_at,
                "name_gionglua"=> $detail_giaodich_luagiong->name_gionglua,
                "soluong"=> $detail_giaodich_luagiong->soluong,
                "price"=> $detail_giaodich_luagiong->price,
                "description_giaodich"=> $detail_giaodich_luagiong->description_giaodich,
                "name_xavien"=> $detail_giaodich_luagiong->name_xavien,
                "nhacungcapvattu_name"=> $detail_giaodich_luagiong->nhacungcapvattu_name,
            ]);

            $hoatdongnhatky = [];
            foreach ($nhatKyHoatDongResponseBlockChain as $key => $nhatKyDongRuong) {
            $nhatky = $this->nhatKyDongRuongService->getDetailNhatKyDongRuongBlockChain($nhatKyDongRuong);
                array_push( $hoatdongnhatky,$nhatky);
            }


            $giaodichmubanlua = ([
                "name_lohang"=>$detail_giaodichmuaban_lua->name_lohang,
                "img_lohang"=>$detail_giaodichmuaban_lua->img_lohang,
                "name_gionglua"=>$detail_giaodichmuaban_lua->name_gionglua,
                "name_hoptacxa"=>$detail_giaodichmuaban_lua->name_hoptacxa,
                "name_thuonglai"=>$detail_giaodichmuaban_lua->name_thuonglai,
                "name_xavien"=>$detail_giaodichmuaban_lua->name_xavien,
                "price"=>$detail_giaodichmuaban_lua->price,
                "soluong"=>$detail_giaodichmuaban_lua->soluong,
                "description_giaodich"=>$detail_giaodichmuaban_lua->description_giaodich,
                "updated_at"=>$detail_giaodichmuaban_lua->updated_at,
                "hoatdongnhatky" => $hoatdongnhatky
                
            ]);

            return ([
                "giaodichmubanlua"=>$giaodichmubanlua,
                "giaodichmubanluagiong"=>$giaodich_luagiong
            ]);
        } catch (\Exception $error) {
            Session::flash('error', 'Có lỗi trong lúc truy xuất thông tin'. $error);
            return false;
        }
    }

}