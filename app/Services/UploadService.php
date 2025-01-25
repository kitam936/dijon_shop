<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class UploadService
{
    public static function upload($DocFile,$event_id)
    {
        // dd($DocFile['doc']);
        if(is_array($DocFile))
        {
            $file = $DocFile['doc'];
        }else{
            $file = $DocFile;
        }
        // dd($file);

        // $fileName = $file->getClientOriginalName();
        // $extension = $file->extension();
        // dd($fileName, $extension);

        $fileNameToStore = 'event'.$event_id.'_'.$file->getClientOriginalName();
        // dd($fileNameToStore);
        Storage::putFileAs('public/documents',  $file, $fileNameToStore );
        return $fileNameToStore;
    }
}
