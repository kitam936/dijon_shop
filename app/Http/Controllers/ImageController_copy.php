<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InterventionImage;
use App\Models\User;
use App\Models\Image;
use App\Models\Hinban;
use Illuminate\Support\Facades\Storage;

class ImageController_copy extends Controller
{


    public function image_index(Request $request, $id)
    {
        $hinban = DB::table('hinbans')
        ->leftjoin('images','hinbans.id','=','images.hinban_id')
        ->where('hinbans.id',($id))
        ->select('hinbans.id as hinban_id','hinbans.hinban_name','images.filename')
        ->first();
        $images = DB::table('images')
        ->join('hinbans','hinbans.id','=','iamges.hinban_id')
        ->where('images.hinban_id',($id))
        ->select('images.hinban_id' ,'images.filename')
        ->orderBy('images.hinban_id', 'asc')
        ->paginate(20);
        // $resv_id = $request->resv_id;
        // dd($resv_id);
        return view('product.image_index',compact('images','hinban'));
    }


    public function create()
    {
        return view('product.image_create');
    }

    public function image_create()
    {
        $hinban = DB::table('hinbans')
        ->leftjoin('images','hinbans.id','=','images.hinban_id')
        // ->where('hinbans.id',($id))
        ->select('hinbans.id as hinban_id','hinbans.hinban_name','images.filename')
        ->first();

        return view('product.image_create',compact('hinban'));
    }


    public function store(Request $request)
    {

        $imageFiles = $request->file('files');


        if(!is_null($imageFiles))
        {
            foreach($imageFiles as $imageFile){
                if(is_array($imageFile))
                {
                    $file = $imageFile['image'];
                }else{
                    $file = $imageFile;
                }
                $originalName = $file->getClientOriginalName();
                $basename = pathinfo($originalName, PATHINFO_FILENAME);
                $fileNameToStore = $originalName;
                // $file = $imageFile;
                $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
                Storage::put('public/'. 'images' . '/' . $fileNameToStore, $resizedImage );

                // dd($originalName,$basename,$fileNameToStore);
                Image::create([
                    'hinban_id'=>$basename,
                    'filename'=>$fileNameToStore,
            ]);
            }
        }

        return to_route('admin.image_create',)->with(['message'=>'画像情報を登録しました','status'=>'info']);
    }



    public function show(string $id)
    {
        //
    }

    public function image_show($id)
    {
        $login_user =  DB::table('users')
        ->where('users.id',Auth::id())
        ->first();
        $image = DB::table('images')
        ->join('hinbans','hinbans.id','images.hinban_id')
        ->where('images.hinban_id',($id))
        ->select('images.hinban_id','images.filename')
        ->first();

        // dd($login_user,$image);
        return view('product.image_show',compact('image','login_user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function image_edit($id)
    {
        $image = DB::table('images')
        ->where('images.filename',($id))
        ->select('images.hinban_id','images.filename')
        ->first();

        return view('product.image_edit',compact('image'));
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function image_destroy($id)
    {

        $image = DB::table('images')
        ->where('images.hinban_id',($id))
        ->select('images.hinban_id','images.filename')
        ->first();
        $hinban = $image->hinban_id;
        $filePath = 'public/images/' . $image->filename;
        // dd($image,$hinban,$filePath);
        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Image::Where('hinban_id','=',$id)->delete();

        return to_route('product_index')->with(['message'=>'画像を削除しました',
        'status'=>'alert']);
    }
}
