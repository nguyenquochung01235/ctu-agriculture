<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class UploadImageService{
    
    public function store($request)
    {
        
        try {
            $type = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_name = date('YmdHis');
            $request->file('image')->storeAs(
                'public/img', $file_name .'.'.$type
            );
            return '/storage/img/' . $file_name .'.'.$type;
        } catch (\Exception $error) {
            return false;
        }
        // try {
        // $file_name = date('YmdHis');
        // $image = base64_decode($request->image);
        // $type = $request->type;
        // $storage = Storage::disk('public');
        // $storage->put('/img/'.$file_name.'.'.$type,  $image);

        // } catch (\Exception $error) {
        //     return false;
        // }
    }
}