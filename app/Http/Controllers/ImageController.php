<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InterventionImage;
use App\Models\User;
use App\Models\Image;
use App\Models\Hinban;
use App\Models\SkuImage;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{


    public function image_index(Request $request)
    {
        $login_user = DB::table('users')
        ->where('users.id',Auth::id())->first();
        $products = Hinban::with('unit')->paginate(50);

        $images = DB::table('hinbans')
        ->join('units','units.id','=','hinbans.unit_id')
        ->leftjoin('images','hinbans.id','=','images.hinban_id')
        ->where('hinbans.vendor_id','<>',8200)
        ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
        ->where('units.season_id','LIKE','%'.($request->season_code).'%')
        ->where('hinbans.unit_id','LIKE','%'.($request->unit_code).'%')
        ->where('hinbans.face','LIKE','%'.($request->face).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('hinbans.id','LIKE','%'.($request->hinban_code).'%')
        ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','hinbans.m_price','hinbans.price','hinbans.face','images.filename'])
        ->orderBy('hinbans.year_code','desc')
        ->orderBy('hinbans.brand_id','asc')
        ->orderBy('hinban_id','asc')
        ->paginate(20);
        // ->get();
        $years=DB::table('hinbans')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
        ->get();
        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();
        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();
        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();
        $brands=DB::table('brands')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();


        return view('product.image_index',compact('images','login_user','products','seasons','units','years','brands','faces'));
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
                // $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
                $resizedImage = InterventionImage::make($file)->resize(600, 600,function($constraint){$constraint->aspectRatio();})->encode();

                $isExist = Image::where('filename',$fileNameToStore)
                    ->exists();
                    // dd($fileNameToStore,$isExist);
                if($isExist)
                {
                    continue;
                }
                if(!$isExist)
                {
                    Storage::put('public/'. 'images' . '/' . $fileNameToStore, $resizedImage );

                // dd($originalName,$basename,$fileNameToStore);
                    Image::create([
                        'hinban_id'=>$basename,
                        'filename'=>$fileNameToStore,
                    ]);
                }
            }
        }

        return to_route('admin.image_create',)->with(['message'=>'品番画像情報を登録しました','status'=>'info']);
    }

    public function sku_store(Request $request)
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
                // $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
                $resizedImage = InterventionImage::make($file)->resize(600, 600,function($constraint){$constraint->aspectRatio();})->encode();

                $isExist = SkuImage::where('filename',$fileNameToStore)
                    ->exists();
                    // dd($fileNameToStore,$isExist);
                if($isExist)
                {
                    continue;
                }
                if(!$isExist)
                {
                    Storage::put('public/'. 'sku_images' . '/' . $fileNameToStore, $resizedImage );

                // dd($originalName,$basename,$fileNameToStore);
                    SkuImage::create([
                        'sku_id'=>$basename,
                        'filename'=>$fileNameToStore,
                    ]);
                }
            }
        }

        return to_route('admin.image_create',)->with(['message'=>'SKU画像情報を登録しました','status'=>'info']);
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
        ->select('images.hinban_id','hinbans.hinban_name','images.filename')
        ->first();
        $sku_images = DB::table('sku_images')
        ->join('skus','skus.id','sku_images.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('skus.hinban_id',($id))
        ->select('sku_images.sku_id','skus.col_id','sku_images.filename')
        ->get();

        // dd($login_user,$image,$sku_images);
        return view('product.image_show',compact('image','sku_images','login_user'));
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
