<?php

namespace App\Http\Services\BlockChainService;

use App\Http\Services\ClientService\HopTacXaService;
use App\Http\Services\CommonService;
use App\Models\HopTacXa;
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

}