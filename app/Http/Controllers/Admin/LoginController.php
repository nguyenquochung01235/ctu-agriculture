<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){
        return view('administrator.user.login',[
            'title'=>'Đăng Nhập Hệ Thống Quản Trị'
        ]);
    }

    public function store(Request $request ){
      
            $admin_email = $request->input('email');
            $password = $request->input('password');
        
            
            if(
                $admin_email == "administrator@nongnghiepxanh.com.vn" 
                &&
                $password == "admin"
            ){
                
                return redirect()->route('dashboard');
            }else{
                Session::flash('error', 'Sai tài khoản hoặc mật khẩu');
                return redirect()->back();
            
            }     
    }

    public function dashboard(){
        return view('administrator.dashboard',[
            'title' => 'Trang Quảng Trị | Nông Nghiệp Xanh'
        ]);
    }



       
}
