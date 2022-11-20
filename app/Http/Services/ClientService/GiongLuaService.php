<?php

namespace App\Http\Services\ClientService;

use App\Models\GiongLua;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GiongLuaService{


  public function getListGiongLua($request){
    try {
      $name_gionglua = $request->search;
      $result =  GiongLua::where('name_gionglua','like', '%'.$name_gionglua.'%')->take(10)->get();
      if($result != null){
        return $result;
      }
      Session::flash('error', 'Danh sách giống lúa rỗng !');
      return false;
    } catch (\Exception $error) {
        Session::flash('error', 'Không lấy được danh sách giống lúa');
        return false;
    }
  }

}