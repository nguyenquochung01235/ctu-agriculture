<?php

namespace App\Http\Services\ClientService;

use App\Models\MenuClient;
use Illuminate\Support\Facades\Session;

class MenuClientService{
    
    public function getMenuClientService(){
        try {
            return MenuClient::get();
        } catch (\Exception $error) {
            Session::flash('error', $error);
            return false;
        }
    }
    
    

}