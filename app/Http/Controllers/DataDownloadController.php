<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Jobs\SendOrderResponseMail;
use App\Models\Report;
use App\Models\User;
use App\Models\Order;

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
            $dl_filename1='マニュアル.pdf';
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
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,hinbans.m_price,FLOOR(hinbans.m_price * shops.rate /1000) as gedai,order_items.pcs')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai','pcs'];

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

        // Status変更
        $order=Order::findOrFail($request->id2);
        $order->status = 3;
        $order->save();

        // Status変更Mail

        $users = Order::Where('orders.id',$request->id2)
        ->join('users','users.id','orders.user_id')
        ->where('mailService','=',1)
        ->select('orders.user_id','users.name','users.email')
        ->get()
        ->toArray();


        $order_info = Order::Where('orders.id',$request->id2)
        ->join('shops','shops.id','orders.shop_id')
        ->join('users','users.id','orders.user_id')
        ->select('orders.id as order_id','users.name','users.email','orders.shop_id','shops.shop_name')
        ->first()
        ->toArray();

        // dd($users,$order_info);

        foreach($users as $user){

            // dd($request,$user,$order_info);
            SendOrderResponseMail::dispatch($order_info,$user);
        }

        return $response;
    }

    public function orderCSV_download_all()
    {
        $orders = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('shops','shops.id','=','orders.shop_id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->where('orders.status',1)
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,hinbans.m_price,FLOOR(hinbans.m_price * shops.rate /1000) as gedai,order_items.pcs')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai','pcs'];

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

        // Status変更
        $orders=Order::where('status',1)->get();
        foreach ($orders as $order) {
            $order->status = 3;
            $order->save();
        }

        return $response;
    }

    public function orderCSV_download_shop()
    {
        $orders = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('shops','shops.id','=','orders.shop_id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->where('orders.status',1)
        ->where('shops.company_id','>=',5000)
        ->where('shops.company_id','<',7000)
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,hinbans.m_price,FLOOR(hinbans.m_price * shops.rate /1000) as gedai,order_items.pcs')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai','pcs'];

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

        // Status変更
        $orders=Order::where('status',1)
        ->where('shop_id','>=',5000)
        ->where('shop_id','<',7000)
        ->orwhere('shop_id',1104)
        ->get();
        foreach ($orders as $order) {
            $order->status = 3;
            $order->save();
        }

        return $response;
    }

    public function orderCSV_download_ws()
    {
        $orders = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('shops','shops.id','=','orders.shop_id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->where('orders.status',1)
        ->where('shops.company_id',400)
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,hinbans.m_price,FLOOR(hinbans.m_price * shops.rate /1000) as gedai,order_items.pcs')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($orders);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai','pcs'];

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

        // Status変更
        $orders=Order::where('status',1)
        ->where('shop_id','>',4000)
        ->where('shop_id','<',5000)->get();
        // dd($orders);
        foreach ($orders as $order) {
            $order->status = 3;
            $order->save();
        }

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

    public function HinbanImageCheck_CSV_download()
    {

        $images = DB::table('hinbans')
        ->join('units','units.id','=','hinbans.unit_id')
        ->leftjoin('images','hinbans.id','images.hinban_id')
        ->where('hinbans.vendor_id','<>',8200)
        ->where('hinbans.year_code','>=',25)
        ->whereNull('images.hinban_id')
        ->select('hinbans.unit_id as unit','hinbans.id as hinban','hinbans.hinban_name')
        ->get();


        // dd($stints);
        $csvHeader = [
            'unit','hinban','hinmei'];

        $csvData = $images->toArray();

        // dd($request,$csvHeader,$csvData);

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
        $response->headers->set('Content-Disposition', 'attachment; filename="HinbanImageCheck.csv"');

        return $response;

    }

    public function SkuImageCheck_CSV_download()
    {
        $sku_images = DB::table('skus')
        ->leftjoin('sku_images','skus.id','sku_images.sku_id')
        ->join('sizes','skus.size_id','sizes.id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->join('units','units.id','=','hinbans.unit_id')
        ->where('hinbans.vendor_id','<>',8200)
        ->whereNull('sku_images.sku_id')
        ->where('skus.col_id','<>',99)
        ->where('hinbans.year_code','>=',25)
        ->select('hinbans.unit_id as unit','hinbans.id as hinban','skus.col_id','skus.size_id','hinbans.hinban_name')
        ->get();


        // dd($stints);
        $csvHeader = [
            'unit','hinban','col','size','hinmei'];

        $csvData = $sku_images->toArray();

        // dd($request,$csvHeader,$csvData);

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
        $response->headers->set('Content-Disposition', 'attachment; filename="SkuImageCheck.csv"');

        return $response;

    }


}
