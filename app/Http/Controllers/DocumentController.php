<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;
use App\Models\Hinban;
use App\Models\User;


class DocumentController extends Controller
{
    public function index(Request $request, $id)
    {
        $documents = DB::table('events')
        ->leftjoin('documents','documents.event_id','=','events.id')
        ->where('events.id','=',$id)
        ->select('events.id','events.event_name','events.place','events.is_visible','documents.id','documents.doc_information','documents.doc_title','documents.doc_filename')
        ->orderBy('documents.updated_at','desc')
        ->get();

        $event = DB::table('events')
        ->where('events.id','=',$id)
        ->first();

        $login_user = User::findorfail(Auth::id());

        $resv_id = $request->reserve_id;
        // dd($documents,$request->resv_id2,$login_user);

        return view('events.documents.doc_index',compact('documents','event','resv_id','login_user'));

    }

    public function create($id)
    {
        $event = DB::table('events')
        ->where('events.id','=',$id)
        ->first();
        // dd($documents);

        return view('events.documents.doc_create',compact('event'));

    }

    public function list(Request $request)
    {
        $documents = DB::table('documents')
        ->join('events','documents.event_id','=','events.id')
        ->join('areas','events.place_area_id','=','areas.id')
        ->where('events.place_area_id','LIKE','%'.($request->ar_id).'%')
        ->where('events.event_name','LIKE','%'.($request->event_name).'%')
        ->select('events.id as evt_id','areas.area_name','events.start_date','events.event_name','events.place','events.is_visible','documents.id as doc_id','documents.doc_information','documents.doc_title','documents.doc_filename')
        ->orderBy('documents.updated_at','desc')
        ->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();

        // dd($documents);

        return view('events.documents.doc_list',compact('documents','areas'));

    }

    public function doc_upload(Request $request)
    {
        $event_id = $request->evt_id2;
        $DocFiles = $request->file('files');
        if(!is_null($DocFiles)){
            foreach($DocFiles as $DocFile){
                $fileNameToStore = UploadService::upload($DocFile, $event_id);
                $isExist = Document::where('doc_filename',$fileNameToStore)
                    ->exists();
                    // dd($fileNameToStore,$isExist);
                if(!$isExist)
                {
                    Document::create([
                    'event_id'=>$request->evt_id2,
                    'doc_title'=>$request->doc_title,
                    'doc_information'=>$request->doc_information,
                    'doc_filename'=>$fileNameToStore,
                ]);
                }else{
                    return to_route('doc_create',['event'=>$event_id])->with(['message'=>'そのファイルは既にアップロードされています','status'=>'alert']);
                };
            }
        }

        return to_route('doc_index',['event'=>$event_id])->with(['message'=>'ファイルをアップロードしました','status'=>'info']);
    }

    public function doc_download($id)
    {
        $dl_filename = Document::findorfail($id)->doc_filename;
        $event_id = Document::findorfail($id)->event_id;
        $file_path = 'public/documents/'.$dl_filename;
        $mimeType = Storage::mimeType($file_path);
        $headers = [['Content-Type' =>$mimeType]];
        // dd($dl_filename,$file_path,$event_id);

        return Storage::download($file_path,  $dl_filename,$headers);


        // return to_route('doc_index',['event'=>$event_id])->with(['message'=>'ファイルをダウンロードしました','status'=>'info']);
    }

    public function edit($id)
    {
        $document = Document::findorfail($id);
        $event = DB::table('events')
        ->join('documents','events.id','documents.event_id')
        ->where('documents.id','=',$id)
        ->first();


        // dd($document,$event);

        return view('events.documents.doc_edit',compact('document','event'));

    }

    public function doc_update(Request $request)
    {
        $event_id = $request->evt_id;
        $DocFiles = $request->file('files');
        if(!is_null($DocFiles)){
            foreach($DocFiles as $DocFile){
                $fileNameToStore = UploadService::upload($DocFile, $event_id);
                $isExist = Document::where('doc_filename',$fileNameToStore)
                    ->exists();
                    // dd($fileNameToStore,$isExist);
                if(!$isExist)
                {
                    $document = Document::findorfail($request->doc_id);
                    $document->event_id = $request->evt_id;
                    $document->doc_title = $request->doc_title;
                    $document->doc_information = $request->doc_information;
                    $document->doc_filename=$fileNameToStore;
                    // dd($document->event_id,$document->doc_title,$document->doc_information,$document->doc_filename);
                    $document->save();
                    return to_route('doc_index',['event'=>$event_id])->with(['message'=>'資料を更新しました','status'=>'info']);
                }else{
                    return to_route('doc_edit',['doc'=>$request->doc_id])->with(['message'=>'そのファイルは既にアップロードされています','status'=>'alert']);
                };
            }
        }else{
            $document = Document::findorfail($request->doc_id);
            $document->event_id = $request->evt_id;
            $document->doc_title = $request->doc_title;
            $document->doc_information = $request->doc_information;
            // dd($document->event_id,$document->doc_title,$document->doc_information,$document->doc_filename);
            $document->save();
            return to_route('doc_index',['event'=>$event_id])->with(['message'=>'資料を更新しました','status'=>'info']);
        }
    }

    public function doc_destroy(Request $request, $id)
    {
        $event_id = DB::table('documents')
        ->where('documents.id','=',$id)
        ->first();
        $event_id2 = $request->evt_id2;
        $filename = Document::findorfail($id)->doc_filename;
        $file_path = 'public/documents/'.$filename;
        // dd($file_path,$event_id,$event_id2,$filename);
        if(Storage::exists($file_path)){
            Storage::delete($file_path);
        }
        Document::findOrFail($id)->delete();

        return to_route('doc_index',['event'=>$event_id2])->with(['message'=>'資料が削除されました','status'=>'alert']);
    }

    public function manual_download()
    {
        $user_role = User::findOrFail(Auth::id())->role_id;
        // dd($user_role);
        if($user_role > 7){
            $dl_filename1='メンバーマニュアル.pdf';
            $file_path1 = 'public/manual/'.$dl_filename1;
            $mimeType1 = Storage::mimeType($file_path1);
            $headers1 = [['Content-Type' =>$mimeType1]];
            // dd($dl_filename1,$file_path1,$user_role);
            return Storage::download($file_path1,  $dl_filename1, $headers1);
        }

        if($user_role <= 7){
            $dl_filename2='スタッフマニュアル.pdf';
            $file_path2 = 'public/manual/'.$dl_filename2;
            $mimeType2 = Storage::mimeType($file_path2);
            $headers2 = [['Content-Type' =>$mimeType2]];
            // dd($dl_filename2,$file_path2,$mimeType2,$user_role);
            return Storage::download($file_path2,  $dl_filename2, $headers2);
        }

        // return to_route('doc_index',['event'=>$event_id])->with(['message'=>'ファイルをダウンロードしました','status'=>'info']);
    }
}
