<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopTacXa;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
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

    public function hoptacxa(){
        
        $hoptacxa = HopTacXa::get();

        return view('administrator.hoptacxa.list',[
            'title' => 'Quản lý danh sách hợp tác xã',
            'hoptacxa' => $hoptacxa
        ]);
    }

    public function detailHopTacXa(Request $request){
        $id_hoptacxa = $request->id_hoptacxa;
        
           
            $hoptacxa = HopTacXa::where('tbl_hoptacxa.id_hoptacxa', $id_hoptacxa)
            ->join('tbl_xavien', 'tbl_xavien.id_hoptacxa', 'tbl_hoptacxa.id_hoptacxa')
            ->join('xavien_rolexavien', 'xavien_rolexavien.xavien_id_xavien', 'tbl_xavien.id_xavien')
            ->join('tbl_rolexavien', 'tbl_rolexavien.id_role', 'xavien_rolexavien.rolexavien_id_role')
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_xavien.id_user')
            ->where('tbl_rolexavien.role','chunhiem')
            ->select(
                'tbl_hoptacxa.*',
                'tbl_user.fullname',
                'tbl_user.phone_number as user_phone_number',
            )
            ->first();
        // return dd($hoptacxa);

        return view('administrator.hoptacxa.update',[
            'title' => 'Chi tiết hợp tác xã',
            'hoptacxa' => $hoptacxa
        ]);
    }

    public function activeHopTacXa(Request $request){
        $id_hoptacxa = $request->id_hoptacxa;
        
           
        $hoptacxa = HopTacXa::where('tbl_hoptacxa.id_hoptacxa', $id_hoptacxa)->first();
        if($hoptacxa->active == 1){
            $hoptacxa->active = 0;
        }else{
            $hoptacxa->active = 1;
        }

        $hoptacxa->save();
        Session::flash('success', 'Thay đổi trạng thái hoạt động của hợp tác xã thành công ');
        return redirect()->back();
    }


    public function post(){
        
        $post =  Post::join('tbl_user', 'tbl_user.id_user', 'tbl_post.id_user')
        ->select(
          'tbl_post.id_post',
          'tbl_post.title_post',
          'tbl_post.short_description',
          'tbl_post.description',
          'tbl_post.image',
          'tbl_post.view',
          'tbl_post.status',
          'tbl_post.updated_at',
          'tbl_user.fullname',
          'tbl_user.avatar',
          )
          ->orderBy('updated_at','desc')
          ->get();

        return view('administrator.post.list',[
            'title' => 'Quản lý danh sách bài viết',
            'post' => $post
        ]);
    }

    public function detailPost(Request $request){
        $id_post = $request->id_post;
        $post = Post::where('id_post', $id_post)
        ->join('tbl_user', 'tbl_user.id_user', 'tbl_post.id_user')
        ->select(
            'tbl_post.id_post',
            'tbl_post.title_post',
            'tbl_post.short_description',
            'tbl_post.description',
            'tbl_post.image',
            'tbl_post.content',
            'tbl_post.type',
            'tbl_post.view',
            'tbl_post.status',
            'tbl_post.updated_at',
            'tbl_user.id_user',
            'tbl_user.fullname',
            'tbl_user.avatar',
            'tbl_user.phone_number',
            )
        ->first();

        return view('administrator.post.update',[
            'title' => 'Chi tiết bài viết',
            'post' => $post
        ]);
    }

    public function activePost(Request $request){
        $id_post = $request->id_post;

        $post = Post::where('id_post', $id_post)->first();
        if($post->status == 1){
            $post->status = 0;
        }else{
            $post->status = 1;
        }

        $post->save();
        Session::flash('success', 'Thay đổi trạng thái hoạt động của bài viết thành công ');
        return redirect()->back();
    }



       
}
