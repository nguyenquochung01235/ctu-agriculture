<?php

namespace App\Http\Services\BlockChainService;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BlockChainAPIService{
    
    protected $BASE_API_URL_BLOCKCHAIN = "http://198.13.38.141:8000/api/v1/blockchain";
    // protected $BASE_API_URL_BLOCKCHAIN = "http://45.32.55.194/api/v1/blockchain";

    public $BASE_ADDRESS = "0x90F8bf6A479f320ead074411a4B0e7944Ea8c9C1"; //if blockchain not return address
    public $BASE_PASSWORD = "1234"; //if blockchain not REQ PASSWORD

    public function getAPICreateUserWallet(){
        
        try {
            $response = Http::get($this->BASE_API_URL_BLOCKCHAIN.'/account');
            $wallet = json_decode($response->body())->results->address;
            if($wallet == null){
                Session::flash('error', 'Không lấy được địa chỉ ví');
                return false;
            }
            return $wallet;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tạo được địa chỉ ví');
            return false;
        }
    }


    public function createBlockChainNhatKyDongRuong(
        $id_xavien,
        $id_nhatkydongruong,
        $id_lichmuavu,
        $id_thuadat,
        $date_start,
        $id_hoatdongmuavu,
        $wallet,
        $password
    ){
        try {
            $response = Http::post($this->BASE_API_URL_BLOCKCHAIN.'/activity-log', [
                "id_XaVien"               =>$id_xavien,
                "id_NhatKyDongRuong"      =>$id_nhatkydongruong,
                "id_LichMuaVu"            =>$id_lichmuavu,
                "id_ThuaDat"              =>$id_thuadat,
                "ThoiGian"                =>$date_start,
                "id_HoatDongMuaVu"        =>$id_hoatdongmuavu,
                "xaVienXacNhan"           =>true,
                "hopTacXaXacNhan"         =>true,
                "wallet_XaVien"           =>$wallet,
                "password_Wallet"         =>$password
            ]);
            if($response->status() == 500){
                Session::flash('error', 'Không tạo được data ở blockchain');
                return false;
            }
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tạo được data ở blockchain');
            return false;
        }

    }

    public function createBlockChainVatTuSuDung(
        $id_vattusudung,
        $id_nhatkydongruong,
        $id_category_vatu,
        $id_giaodichmuaban_vattu,
        $timeuse,
        $soluong,
        $name_category_vattu,
        $wallet,
        $password
    ){
        try {
            $response = Http::post($this->BASE_API_URL_BLOCKCHAIN.'/supplies-using', [
                "id_VatTuSuDung"=>$id_vattusudung,
                "id_NhatKyHoatDong"=> $id_nhatkydongruong,
                "id_VatTu" => $id_category_vatu,
                "id_GiaoDichVatTu"=>$id_giaodichmuaban_vattu,
                "ThoiGianVatTu"=> $timeuse,
                "soLuong"=>$soluong,
                "TenVatTu"=>$name_category_vattu,
                "wallet_XaVien"=>$wallet,
                "password_Wallet"=>$password
            ]);

            if($response->status() == 500){
                Session::flash('error', 'Không tạo được data ở blockchain');
                return false;

            }
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tạo được data ở blockchain');
            return false;
        }

    }

    public function createBlockChainGiaoDichMuaBanLuaGiong(
        $id_giaodich_luagiong,
        $id_xavien,
        $id_nhacungcapvattu,
        $id_lichmuavu,
        $id_gionglua,
        $soluong,
        $name_gionglua,
        $timeuse,
        $wallet,
        $password
    ){
        try {
            $response = Http::post($this->BASE_API_URL_BLOCKCHAIN.'/seed-rice-transaction', [
                "id_GiaoDichLuaGiong"=>$id_giaodich_luagiong,
                "id_XaVien"=>  $id_xavien,
                "id_NhaCungCapVatTu"=> $id_nhacungcapvattu,
                "id_LichMuaVu"=> $id_lichmuavu,
                "id_LuaGiong"=> $id_gionglua,
                "SoLuong"=>  $soluong,
                "TenLuaGiong"=> $name_gionglua,
                "ThoiGian"=> $timeuse,
                "wallet_XaVien"=> $wallet,
                "password_Wallet"=> $password
            ]);
            if($response->status() == 500){
                Session::flash('error', 'Không tạo được data ở blockchain');
                return false;
            }
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tạo được data ở blockchain');
            return false;
        }

    }



    public function createBlockChainGiaoDichMuaBanVatTu(
        $id_giaodichmuaban_vattu,
        $id_xavien,
        $id_nhacungcapvattu,
        $id_lohangvattu,
        $id_category_vattu,
        $time_giaodich,
        $price,
        $id_lichmuavu,
        $name_category_vattu,
        $time_lohang,
        $soluong,
        $wallet,
        $password
    ){
        try {
            $response = Http::post($this->BASE_API_URL_BLOCKCHAIN.'/supplies-transaction', [
                "id_GiaoDich"=> $id_giaodichmuaban_vattu,
                "id_XaVien"=> $id_xavien,
                "id_NhaCungCap"=> $id_nhacungcapvattu,
                "id_LoHangVatTu"=> $id_lohangvattu,
                "id_VatTu"=> $id_category_vattu,
                "thoigianGiaoDich"=> $time_giaodich,
                "giaLoHang"=> $price,
                "id_MuaVu"=>$id_lichmuavu,
                "tenVatTu"=>  $name_category_vattu,
                "thoigianLoHang"=>$time_lohang,
                "soluong"=>  $soluong,
                "xacnhanXaVien"=>      true,
                "xacnhanNhaCungCap"=>  true,
                "xacnhanHTX"=>         true,
                "wallet_NguoiTao"=> $wallet,
                "password"=> $password
            ]);
            if($response->status() == 500){
                Session::flash('error', 'Không tạo được data ở blockchain');
                return false;
            }
            // return dd($response->body());
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tạo được data ở blockchain');
            return false;
        }

    }


    public function createBlockChainGiaoDichMuaBanLua(
        $id_giaodichmuaban_lua,
        $id_lichmuavu,
        $id_lohanglua,
        $id_xavien,
        $id_thuonglai,
        $created_at,
        $price,
        $id_gionglua,
        $updated_at,
        $name_gionglua,
        $soluong,
        $wallet,
        $password
    ){
        try {
            $response = Http::post($this->BASE_API_URL_BLOCKCHAIN.'/rice-transaction', [
                "id_GiaoDich"=> $id_giaodichmuaban_lua,
                "id_LichMuaVu"=> $id_lichmuavu,
                "id_LoHangLua"=> $id_lohanglua,
                "id_XaVien"=> $id_xavien,
                "id_ThuongLai"=> $id_thuonglai,
                "thoigianGiaoDich"=> $created_at,
                "giaLoHang"=> $price,
                "id_GiongLua"=> $id_gionglua,
                "thoigianLoHang"=> $updated_at,
                "tenGiongLua"=> $name_gionglua,
                "soluong"=> $soluong,
                "xacnhanXaVien"=>      true,
                "xacnhanThuongLai"=>   true,
                "xacnhanHTX"=>         true,
                "wallet_NguoiTao"=> $wallet,
                "password"=> $password
            ]);

            if($response->status() == 500){
                Session::flash('error', 'Không tạo được data ở blockchain');
                return false;
            }
            return true;
        } catch (\Exception $error) {
            Session::flash('error', 'Không tạo được data ở blockchain');
            return false;
        }

    }

}