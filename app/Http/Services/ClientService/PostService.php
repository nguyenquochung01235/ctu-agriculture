<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PostService{

  protected $commentService;
  protected $commonService;
  protected $uploadImageService;

  public function __construct(
    CommentService $commentService,
    CommonService $commonService,
    UploadImageService $uploadImageService
    )
  {
    $this->commentService = $commentService;
    $this->commonService = $commonService;
    $this->uploadImageService = $uploadImageService;
  }

  public function getDetailPost($request){
    try {
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
        'tbl_post.updated_at',
        'tbl_user.id_user',
        'tbl_user.fullname',
        'tbl_user.avatar',
        'tbl_user.phone_number',
        )
      ->first();


      if($post == null){
        Session::flash('error', 'Bài viết không tồn tại');
        return false;
      }
      DB::beginTransaction();
      $post->timestamps = false;
      $post->view = $post->view + 1;
      $post->save();
      DB::commit();


      $list_comment = $this->commentService->getListCommentOfPost($id_post);
      return ([
        "post"=>$post,
        "list_comment"=>$list_comment
      ]);

    } catch (\Exception $error) {
      DB::rollBack();
        Session::flash('error', 'Không lấy được chi tiết bài viết');
        return false;
    }
  }

  public function getListPost($request){
    $page = $request->page;
    $limit =  $request->limit;
    $search = $request->search;
    $order = $request->order;
    $sort = $request->sort;

    if($page == null || $page == 0 || $page < 0){
        $page = 1;
    }
    if($limit == null || $limit == 0 || $limit < 0){
        $limit = 15;
    }
    if($search == null){
        $search = "";
    }
    if($order == null || $order == ""){
        $order = "updated_at";
    }
    if($sort == null || $sort == "" || ($sort != "desc" && $sort != "asc")){
        $sort = "desc";
    }
    
    try {
        $data =  Post::join('tbl_user', 'tbl_user.id_user', 'tbl_post.id_user')
        ->select(
          'tbl_post.id_post',
          'tbl_post.title_post',
          'tbl_post.short_description',
          'tbl_post.description',
          'tbl_post.image',
          'tbl_post.view',
          'tbl_post.updated_at',
          'tbl_user.fullname',
          'tbl_user.avatar',
          )
        ->Search($request);

        $total = $data->count();
        $meta = $this->commonService->pagination($total,$page,$limit);

        $result =  $data
        ->skip(($page-1)*$limit)
        ->take($limit)
        ->orderBy($order, $sort)
        ->get();
        
        
        if($result != []){
          return [$result,$meta];
        }
      } catch (\Exception $error) {
          Session::flash('error', 'Không lấy được danh sách bài viết');
          return false;
      }
}

  public function createPost($request){
    try {
      $id_user = $this->commonService->getIDByToken();
      $title_post = $request->title_post;
      $short_description = $request->short_description;
      $description = $request->description;
      $content = $request->content;
      $image = null;
      if($request->hasFile('image')){
        $image = $this->uploadImageService->store($request->image);
      }

      DB::beginTransaction();
      $post = Post::create([
        "title_post" =>$title_post,
        "short_description" =>$short_description,
        "description" =>$description,
        "image" => $image,
        "content" =>$content,
        "id_user" =>$id_user,
        "view" =>0,
        "type" =>'new',
        "status" =>0,
      ]);
      DB::commit();
     return  $post;
    } catch (\Exception $error) {
      DB::rollBack();
        Session::flash('error', 'Không tạo được bài viết');
        return false;
    }
  }
  public function updatePost($request){
    try {
      $id_post = $request->id_post;

      $id_user = $this->commonService->getIDByToken();
      $title_post = $request->title_post;
      $short_description = $request->short_description;
      $description = $request->description;
      $content = $request->content;
      $image = null;

      $post = Post::where('id_post', $id_post)->where('id_user', $id_user)->first();
      if($post == null){
        Session::flash('error', 'Bài viết không tồn tại');
        return false;
      }
      if($request->hasFile('image')){
        if($post->image != null){
          $this->uploadImageService->delete($post->image);
        }
        $image = $this->uploadImageService->store($request->image);
      }

      DB::beginTransaction();
      $post->title_post = $title_post;
      $post->short_description = $short_description;
      $post->description = $description;
      $post->content = $content;
      $post->image = $image;
      $post->status = 1;
      $post->type = "update";
      $post->save();
      DB::commit();
     return  $this->getDetailPost($post);
    } catch (\Exception $error) {
      DB::rollBack();
        Session::flash('error', 'Không cập nhật được bài viết');
        return false;
    }
  }

  public function deletePost($request){
    try {
      $id_post = $request->id_post;

      $id_user = $this->commonService->getIDByToken();
    
      $post = Post::where('id_post', $id_post)->where('id_user', $id_user)->first();
      if($post == null){
        Session::flash('error', 'Bài viết không tồn tại');
        return false;
      }
     

      DB::beginTransaction();
      $post->delete();
      DB::commit();
     return  true;
    } catch (\Exception $error) {
      DB::rollBack();
        Session::flash('error', 'Không xóa được bài viết');
        return false;
    }
  }

}