<?php

namespace App\Http\Services\ClientService;

use App\Models\Account;
use App\Models\NhaCungCapVatTu;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class NhaCungCapVatTuService{
    
    public function createNhaCungCapVatTu($id_user, $name_daily){
        try {
            
            NhaCungCapVatTu::create([
                'id_user' => $id_user,
                'name_daily' => $name_daily,
                'active' => 1
            ]);
            $nhacungcapvattu = User::where('id_user', $id_user)->first();
            $account = Account::where('code', '3')->first();
            $nhacungcapvattu->account()->attach($account);
        } catch (\Exception $error) {
            Session::flash('error', $error);
            return false;
        }
        return true;
    }
    
    

}