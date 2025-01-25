<?php

namespace App\Services;

use InterventionImage;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function upload($imageFile,$folderName)
    {
        // dd($imageFile['image']);
        // if(is_array($imageFile))
        // {
        //     $file = $imageFile['image'];
        // }else{
        //     $file = $imageFile;
        //     }
        $file = $imageFile;
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
        $fileName = uniqid(rand().'_');
        $extension = $file->extension();
        $fileNameToStore = $fileName. '.' . $extension;
        Storage::put('public/'. $folderName . '/' . $fileNameToStore, $resizedImage );
        return $fileNameToStore;
    }

    public static function report_upload($imageFile1,$imageFile2,$imageFile3,$imageFile4,$folderName)
    {
        // dd($imageFile['image']);
        // if(is_array($imageFile))
        // {
        //     $file = $imageFile['image'];
        // }else{
        //     $file = $imageFile;
        //     }
        $fileName1 = uniqid(rand().'_');
        $extension = $imageFile1->extension();
        $fileNameToStore1 = $fileName1. '.' . $extension;
        Storage::put('public/'. $folderName . '/' . $fileNameToStore1, $imageFile1 );
        $fileName2 = uniqid(rand().'_');
        $extension = $imageFile2->extension();
        $fileNameToStore2 = $fileName2. '.' . $extension;
        Storage::put('public/'. $folderName . '/' . $fileNameToStore2, $imageFile2 );
        $fileName3 = uniqid(rand().'_');
        $extension = $imageFile3->extension();
        $fileNameToStore3 = $fileName3. '.' . $extension;
        Storage::put('public/'. $folderName . '/' . $fileNameToStore3, $imageFile3 );
        $fileName4 = uniqid(rand().'_');
        $extension = $imageFile3->extension();
        $fileNameToStore4 = $fileName4. '.' . $extension;
        Storage::put('public/'. $folderName . '/' . $fileNameToStore4, $imageFile4 );
        return $fileNameToStore1;
    }

    public static function image_upload($imageFile)
    {
       if(is_array($imageFile))
        {
            $file = $imageFile['image'];
        }else{
            $file = $imageFile;
        }
        $originalName = $file->getClientOriginalName();
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $fileNameToStore = 'images/'.$originalName;
        $file = $imageFile;
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
        Storage::put('public/'. 'images' . '/' . $fileNameToStore, $resizedImage );
        return $fileNameToStore;
    }


}
