<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Report;
use App\Models\User;

class DataDownloadController extends Controller
{
    public function Report_DL(Request $request)
    {
        $reports = DB::table('reports')->get();


        // dd($request,$stints);

        return view('stints.my_stint_csv_dl',compact('tire_temps','temps','road_temps','humis','cirs','karts','tires','engines'));

    }
    public function ReportCSV_download(Request $request)
    {
        $reports = Report::where('id', '>=',0)
        ->select('reports.id','reports.shop_id','reports.image1','reports.image2','reports.image3','reports.image4',
                'reports.comment','reports.created_at','reports.updated_at','reports.user_id')
        ->orderby('reports.id','asc')
        ->get();

        // dd($stints);
        $csvHeader = [
            'reports.id','reports.shop_id','reports.image1','reports.image2','reports.image3','reports.image4',
            'reports.comment','reports.created_at','reports.updated_at','reports.user_id'];

        $csvData = $reports->toArray();

        // dd($request,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                mb_convert_variables('sjis-win','utf-8',$row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_stints.csv"',
        ]);

        return $response;

    }

    public function manual_download()
    {
        $user_role = User::findOrFail(Auth::id())->role_id;
        // dd($user_role);
        if($user_role > 3){
            $dl_filename1='メンバーマニュアル.pdf';
            $file_path1 = 'public/manual/'.$dl_filename1;
            $mimeType1 = Storage::mimeType($file_path1);
            $headers1 = [['Content-Type' =>$mimeType1]];
            // dd($dl_filename1,$file_path1,$user_role);
            return Storage::download($file_path1,  $dl_filename1, $headers1);
        }

        if($user_role <= 2){
            $dl_filename2='管理者マニュアル.pdf';
            $file_path2 = 'public/manual/'.$dl_filename2;
            $mimeType2 = Storage::mimeType($file_path2);
            $headers2 = [['Content-Type' =>$mimeType2]];
            // dd($dl_filename2,$file_path2,$mimeType2,$user_role);
            return Storage::download($file_path2,  $dl_filename2, $headers2);
        }

        // return to_route('doc_index',['event'=>$event_id])->with(['message'=>'ファイルをダウンロードしました','status'=>'info']);
    }


    // 成功したコード
    public function orderCSV_download(Request $request)
    {
        $orders = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('shops','shops.id','=','orders.shop_id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->where('orders.id',$request->id2)
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,hinbans.m_price,(hinbans.m_price * shops.rate /1000) as gedai')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai'];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        return $response;


    }

    // エラーがでたコード
    public function orderCSV_download2(Request $request)
    {
        $orders = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('shops','shops.id','=','orders.shop_id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->where('orders.id',$request->id2)
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,hinbans.m_price,(hinbans.m_price * shops.rate /1000) as gedai')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai'];

        $csvData = $orders->toArray();

        dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                mb_convert_variables('sjis-win','utf-8',$row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders.csv"',
        ]);

        return $response;

    }






}
