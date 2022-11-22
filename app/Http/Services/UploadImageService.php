<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class UploadImageService{

    public $BASE_URL = 'https://ctu-agriculture.s3.ap-northeast-1.amazonaws.com/';
    
    public function store($image)
    {
        try {
            if((gettype($image) != 'string') && (gettype($image) != 'NULL') && $image != null){
                $type = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                $file_name = date('YmdHis');
                $file_path = 'image/' . $file_name.'.'.$type;
                $upload_img_result =Storage::disk('s3')->put($file_path, file_get_contents($image));
                if($upload_img_result){
                    return $this->BASE_URL.$file_path;
                }
            }
           
        } catch (\Exception $error) {
            return false;
        }
    }

    public function delete($file_path)
    {
        try {
            $file_path = str_replace($this->BASE_URL,"",$file_path);
            $delete_img_result =Storage::disk('s3')->delete($file_path);
            return true;
        } catch (\Exception $error) {
            return false;
        }
    }
    
}