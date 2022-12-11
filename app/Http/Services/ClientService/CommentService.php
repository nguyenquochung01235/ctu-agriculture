<?php

namespace App\Http\Services\ClientService;

use App\Http\Services\CommonService;
use App\Http\Services\UploadImageService;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CommentService
{

    protected $commonService;
    protected $uploadImageService;
    protected $notificationService;

    public function __construct(
        CommonService $commonService,
        UploadImageService $uploadImageService,
        NotificationService $notificationService)
    {
        $this->commonService = $commonService;
        $this->uploadImageService = $uploadImageService;
        $this->notificationService = $notificationService;
    }

    public function getListCommentOfPost($id_post)
    {
        try {
            $list_comment = Comment::with('replies')
            ->where('id_post', $id_post)
            ->where('parent_id', null)
            ->join('tbl_user', 'tbl_user.id_user', 'tbl_comment.id_user')
            ->select(
                'tbl_comment.*',
                'tbl_user.avatar',
                'tbl_user.fullname',
            )
            ->orderBy('tbl_comment.created_at', 'desc')
            ->get();
            return  $list_comment;
        } catch (\Exception $error) {
            Session::flash('error', 'Không lấy được danh sách bình luận' . $error);
            return false;
        }
    }
    public function createComment($request)
    {
        try {
            $id_user = $this->commonService->getIDByToken();
            $id_post = $request->id_post;
            $parent_id = $request->parent_id;
            $content = $request->content;
            $image = null;
            if ($request->hasFile('image')) {
                $image = $this->uploadImageService->store($request->image);
            }

            $post = Post::where('id_post', $id_post)->first();
            if($post == null){
                Session::flash('error', 'Bài viết không tồn tại');
                return false;
            }

            DB::beginTransaction();

            $comment = Comment::create([
                "id_post" => $id_post,
                "id_user" => $id_user,
                "parent_id" => $parent_id,
                "image" => $image,
                "content" => $content,
                "type" => "new",
            ]);
            // Send notify to user_create & all user comment
            $user_comment = User::where('id_user', $id_user)->first();
            $list_user_in_post = Comment::where('id_post', $id_post)->whereNot('id_user',  $user_comment->id_user)->distinct()->get('id_user');

            $message = "$user_comment->fullname đã bình luận bài viết $post->title_post";
            $status_notify = 0;
            $link = "/post/$id_post";

            //Send to create user 
            $notify = $this->notificationService->createNotificationService($message, $status_notify,$post->id_user,$link);
            $this->notificationService->sendNotificationService($notify->id);
            // Send to comment list user
            foreach ($list_user_in_post as $key => $user) {
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$user->id_user,$link);
                $this->notificationService->sendNotificationService($notify->id);
            }

            if($parent_id != null){
                $message = "$user_comment->fullname đã trả lời bình luận của bạn ở bài viết $post->title_post";
                $user_comment_parent = Comment::where('parent_id', $parent_id)->first();
                $notify = $this->notificationService->createNotificationService($message, $status_notify,$user_comment_parent->id_user,$link);
                $this->notificationService->sendNotificationService($notify->id);
            }
            DB::commit();

            return  $comment;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không tạo được bình luận');
            return false;
        }
    }

    public function updateComment($request)
    {
        try {
            $id_comment = $request->id_comment;
            $id_user = $this->commonService->getIDByToken();
            $content = $request->content;
            $image = null;
            if ($request->hasFile('image')) {
                $image = $this->uploadImageService->store($request->image);
            }

            $comment = Comment::where('id_comment', $id_comment)->first();
            if($comment == null){
                Session::flash('error', 'Bình luận không tồn tại');
                return false;
            }
            if($comment->id_user != $id_user){
                Session::flash('error', 'Bạn không thể chỉnh sửa bình luận của người khác');
                return false;
            }
            DB::beginTransaction();
            $comment->content = $content;
            $comment->image = $image;
            $comment->type = "update";
            $comment->save();
            DB::commit();
            return $comment;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không chỉnh sửa được bình luận');
            return false;
        }
    }

    public function deleteComment($request)
    {
        try {
            $id_comment = $request->id_comment;
            $id_user = $this->commonService->getIDByToken();
           

            $comment = Comment::where('id_comment', $id_comment)->first();
            $post =  Post::where('id_post', $comment->id_post)->first();
            if($comment == null){
                Session::flash('error', 'Bình luận không tồn tại');
                return false;
            }

            if($comment->id_user != $id_user){
                if($post->id_user != $id_user){
                    Session::flash('error', 'Bạn không thể chỉnh sửa bình luận của người khác');
                    return false;
                }
            }

            DB::beginTransaction();
            $comment->delete();
            DB::commit();
            return true;
        } catch (\Exception $error) {
            DB::rollBack();
            Session::flash('error', 'Không thể xóa bình luận');
            return false;
        }
    }
}
