<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\XaVien;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CommonService{

    public function getIDByToken(){
        try {
            $id_user = auth('api')->user()->id_user;
            return $id_user;
        } catch (\Exception $error) {
            Session::flash('error', 'Không xác định được token user');
            return false;
        }
    }

    public function getAccountTypeByToken(){
        try {
            $id_user = $this->getIDByToken();
            $user = User::join('user_account', 'user_account.user_id_user', 'tbl_user.id_user')
                        ->join('tbl_account', 'tbl_account.id_account', 'user_account.account_id_account')
                        ->select('tbl_account.path')
                        ->where('tbl_user.id_user', $id_user)
                        ->first();
            return $user->path;
            
        } catch (\Exception $error) {
            Session::flash('error', 'Không xác định được token user');
            return false;
        }
    }

    public function checkDate($start, $end){
        try {
            if($start > $end){
                Session::flash('error', 'Ngày bắt đầu lớn hơn ngày kết thúc');
                return false;
            }
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không xác định được ngày bắt đầu và ngày kết thúc');
            return false;
        }
    }
    
    public function pagination($total, $page, $limit){
        $page = 1*$page;
        // Tổng số trang
        $totalPage = ceil($total / $limit);
        // Trang hiện tại 
        if($page > $totalPage ){
            $page = $totalPage;
        }
        $currentPage = $page;

        // Trang trước
        $prePage = null;
        if($currentPage > 1) {
            $prePage = $currentPage - 1;
        }
        
        // Trang sau
        $nextPage = null;
        if($currentPage < $totalPage) {
            $nextPage = $currentPage + 1;
        }
        

        $meta = ([
            "total" => $total,
            "totalPage" => $totalPage,
            "currentPage" => $currentPage,
            "prePage" => $prePage,
            "nextPage" => $nextPage,
        ]);

        return $meta;
    }

}