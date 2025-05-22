<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\PosDetail;
use App\Models\PosHeader;
use App\Models\PosWork;
use App\Models\Hinban;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function scan() {
        // $works = PosWork::where('shop_id', Auth::user()->shop_id)
        // ->orderBy('created_at', 'desc') // 新しい順
        // ->limit(5)                      // 5件に絞る
        // ->get();

        $works = DB::table('pos_works')
        ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
        ->leftjoin('skus','skus.id','pos_works.sku_id')
        ->where('pos_works.user_id', Auth::user()->id)
        ->selectRaw('pos_works.id,pos_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,pos_works.hinban_id,hinbans.price,pos_works.price as pos_price,pos_works.pcs,pos_works.created_at')
        ->orderBy('created_at', 'desc') // 新しい順
        ->limit(5)                      // 5件に絞る
        ->get();

        $h_exist = DB::table('pos_works')
        ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
        ->leftjoin('skus','skus.id','pos_works.sku_id')
        ->where('pos_works.user_id', Auth::user()->id)
        ->limit(5)   // 5件に絞る
        ->whereNull('hinbans.id')
        ->exists();

        $s_exist = DB::table('pos_works')
        ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
        ->leftjoin('skus','skus.id','pos_works.sku_id')
        ->where('pos_works.user_id', Auth::user()->id)
        ->limit(5)   // 5件に絞る
        ->whereNull('skus.id')
        ->exists();

        $works_total = PosWork::where('user_id', Auth::user()->id)
        ->groupBy('pos_works.user_id')
        ->selectRaw('pos_works.user_id,sum(pos_works.pcs) as pcs')                            // 5件に絞る
        ->first();
        // dd($h_exist,$s_exist);
        return view('pos.scan',compact('works','works_total','s_exist','h_exist'));
    }

    public function store(Request $request)
    {
        $hinbanId = substr($request->raw_cd, 0, 6);

        // hinbansテーブルから対応するレコードを取得
        $hinban = Hinban::where('id', $hinbanId)->first();

        // m_priceを取得（該当がない場合は0やnullなどを適切に処理）
        $price = $hinban ? $hinban->m_price : 0;

        // dd($hinbanId,$hinban,$price);

        PosWork::create([
            'user_id' => Auth::user()->id ,
            'shop_id' => Auth::user()->shop_id ,
            'raw_cd' => $request->raw_cd,
            'hinban_id' => substr($request->raw_cd, 0, 6),
            'sku_id' => substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1),
            'price' => $price,
            'pcs' => 1,
        ]);
        // return redirect()->back()->with(['message'=>'バーコードを読み取りました','status'=>'info']);
        return redirect()->route('pos_scan')->with(['message'=>'バーコードを読み取りました','status'=>'info']);
    }

    public function manual(Request $request)
    {
        $hinbanId = substr($request->raw_cd, 0, 6);

        // hinbansテーブルから対応するレコードを取得
        $hinban = Hinban::where('id', $hinbanId)->first();

        // m_priceを取得（該当がない場合は0やnullなどを適切に処理）
        $price = $hinban ? $hinban->m_price : 0;

        // dd($hinbanId,$hinban,$price);
        PosWork::create([
            'user_id' => Auth::user()->id,
            'shop_id' => Auth::user()->shop_id ,
            'raw_cd' => $request->raw_cd,
            'sku_id' => substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1),
            'hinban_id' => substr($request->raw_cd, 0, 6),
            'price' => $price,
            'pcs' => 1,
        ]);
        return redirect()->back()->with(['message'=>'手入力で追加しました','status'=>'info']);
    }

    public function confirm() {
        $items = PosWork::all();
        return view('pos.confirm', compact('items'));
    }

    public function complete(Request $request)
{
    $headerId = null;

    DB::transaction(function () use ($request, &$headerId) {
        $user_id = Auth::user()->id;
        $shop_id = Auth::user()->shop_id;
        $works = PosWork::where('shop_id', $shop_id)->get();
        if ($works->isEmpty()) {
            throw new \Exception("POSデータがありません");
        }

        $header = PosHeader::create([
            'shop_id' => $shop_id,
            'user_id' => $user_id,
            'pos_date' => now(),
            'memo' => $request->memo,
        ]);

        // dd($works);

        foreach ($works as $work) {
            PosDetail::create([
                'pos_header_id' => $header->id,
                'user_id' => $user_id,
                'sku_id' => $work->sku_id,
                'price' => $work->price,
                'pcs' => $work->pcs,
            ]);
        }

        PosWork::where('user_id', $user_id)->delete();

        $headerId = $header->id;
    });
        // return redirect('/inventory/result/' . PosHeader::latest()->first()->id);
        return to_route('pos_result_index');
    }

    public function result($id) {
        $header = PosHeader::with('details')->findOrFail($id);
        $works = PosWork::where('user_id', Auth::user()->id)->paginete(5);
        return view('pos.result', compact('header','works'));
    }

    public function download($id)
    {
        $inventorys = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->leftjoin('skus','skus.id','pos_details.sku_id')
        ->where('pos_headers.id',$id)
        ->groupBy('pos_headers.id','pos_headers.pos_date','pos_headers.shop_id','shops.shop_name','users.name','pos_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','pos_details.price')
        ->selectRaw('pos_headers.id,pos_headers.pos_date,pos_headers.shop_id,shops.shop_name,users.name,pos_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,pos_details.price,sum(pos_details.pcs) as pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'id','date','shop_id','shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','uriage'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="POS実績.csv"');

         // Status変更
         $header=PosHeader::findOrFail($id);
         $header->status_id = 5;
         $header->save();

        return $response;

    }

    public function download_all()
    {
        $inventorys = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->leftjoin('skus','skus.id','pos_details.sku_id')
        ->where('pos_headers.status_id',1)
        ->groupBy('pos_headers.id','pos_headers.pos_date','pos_headers.shop_id','shops.shop_name','users.name','pos_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','pos_details.price')
        ->selectRaw('pos_headers.id,pos_headers.pos_date,pos_headers.shop_id,shops.shop_name,users.name,pos_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,pos_details.price,sum(pos_details.pcs) as pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($inventorys);

        $csvHeader = [
            'id','date','shop_id','shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','uriage'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="POS実績.csv"');

         // Status変更
         $headers=PosHeader::where('status_id',1)->get();
        //  dd($headers);
         foreach ($headers as $header) {
            $header->status_id = 5;
            $header->save();
        }

        return $response;

    }

    public function download_iyc()
    {
        $inventorys = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->leftjoin('skus','skus.id','pos_details.sku_id')
        ->where('pos_headers.status_id',1)
        ->where('shops.company_id',5200)
        ->groupBy('pos_headers.id','pos_headers.pos_date','pos_headers.shop_id','shops.shop_name','users.name','pos_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','pos_details.price')
        ->selectRaw('pos_headers.id,pos_headers.pos_date,pos_headers.shop_id,shops.shop_name,users.name,pos_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,pos_details.price,sum(pos_details.pcs) as pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($inventorys);

        $csvHeader = [
            'id','date','shop_id','shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','uriage'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="POS実績.csv"');

         // Status変更
        $headers=PosHeader::where('status_id',1)
        ->where('shop_id','>=',5200)
        ->where('shop_id','<',5300)
        ->get();
        //  dd($headers);
        foreach ($headers as $header) {
            $header->status_id = 5;
            $header->save();
        }

        return $response;

    }

    public function download_izc()
    {
        $inventorys = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->leftjoin('skus','skus.id','pos_details.sku_id')
        ->where('pos_headers.status_id',1)
        ->where('shops.company_id',5500)
        ->groupBy('pos_headers.id','pos_headers.pos_date','pos_headers.shop_id','shops.shop_name','users.name','pos_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','pos_details.price')
        ->selectRaw('pos_headers.id,pos_headers.pos_date,pos_headers.shop_id,shops.shop_name,users.name,pos_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,pos_details.price,sum(pos_details.pcs) as pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($inventorys);

        $csvHeader = [
            'id','date','shop_id','shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','uriage'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="POS実績.csv"');

         // Status変更
        $headers=PosHeader::where('status_id',1)
        ->where('shop_id','>=',5500)
        ->where('shop_id','<',5600)
        ->get();
        //  dd($headers);
        foreach ($headers as $header) {
            $header->status_id = 5;
            $header->save();
        }

        return $response;

    }




    public function result_index() {
        $pos_hs = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->join('pos_statuses','pos_statuses.id','pos_headers.status_id')
        ->where('pos_headers.shop_id',Auth::user()->shop_id)
        ->groupBy('pos_details.pos_header_id','pos_headers.shop_id','pos_headers.status_id','pos_statuses.status_name','shops.shop_name','users.name','pos_headers.pos_date')
        ->selectRaw('pos_details.pos_header_id as id,pos_headers.pos_date,pos_headers.shop_id,pos_headers.status_id,pos_statuses.status_name,shops.shop_name,users.name,sum(pos_details.pcs) as pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_header_id','desc')
        ->paginate(50);

        $pos_hs2 = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->join('pos_statuses','pos_statuses.id','pos_headers.status_id')
        ->groupBy('pos_details.pos_header_id','pos_headers.shop_id','pos_headers.status_id','pos_statuses.status_name','shops.shop_name','users.name','pos_headers.pos_date')
        ->selectRaw('pos_details.pos_header_id as id,pos_headers.pos_date,pos_headers.shop_id,pos_headers.status_id,pos_statuses.status_name,shops.shop_name,users.name,sum(pos_details.pcs) as pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_header_id','desc')
        ->paginate(50);

        $dl_new = DB::table('pos_headers') //未ＤＬ判定用
        ->where('pos_headers.status_id',1)
        ->exists();

        $user = DB::table('users')
        ->where('users.id',Auth::id())->first();
            //    dd($pos_hs,$user,$dl_new);
        return view('pos.result_index', compact('pos_hs','user','pos_hs2','dl_new'));
    }

    public function result_show($id) {
        $pos_h = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->join('pos_statuses','pos_statuses.id','pos_headers.status_id')
        ->where('pos_headers.id',$id)
        ->groupBy('pos_details.pos_header_id','pos_headers.shop_id','pos_headers.status_id','pos_statuses.status_name','shops.shop_name','users.name','pos_headers.pos_date','pos_headers.memo')
        ->selectRaw('pos_details.pos_header_id as id,pos_headers.pos_date,pos_headers.shop_id,pos_headers.status_id,pos_statuses.status_name,pos_headers.memo,shops.shop_name,users.name,sum(pos_details.pcs) as total_pcs,sum(pos_details.pcs * pos_details.price) as uriage')
        ->orderBy('pos_header_id','desc')
        ->first();

        $pos_fs = DB::table('pos_headers')
        ->join('pos_details','pos_details.pos_header_id','pos_headers.id')
        ->join('users','users.id','pos_details.user_id')
        ->join('shops','shops.id','pos_headers.shop_id')
        ->leftjoin('skus','skus.id','pos_details.sku_id')
        ->leftjoin('hinbans','hinbans.id','skus.hinban_id')
        ->where('pos_headers.id',$id)
        ->groupBy('pos_details.sku_id','skus.hinban_id','hinbans.hinban_name','skus.col_id','skus.size_id')
        ->selectRaw('pos_details.sku_id,skus.hinban_id,hinbans.hinban_name,skus.col_id,skus.size_id,sum(pos_details.pcs) as f_pcs,sum(pos_details.pcs * pos_details.price) as f_uriage')
        ->orderBy('sku_id','asc')
        ->paginate(50);

        $user = DB::table('users')
        ->where('users.id',Auth::id())->first();
            //    dd($pos_h,$user,$pos_fs);
        return view('pos.result_show', compact('pos_h','pos_fs','user'));
    }

    public function result_destroy($id)
    {
        $work = PosHeader::findOrFail($id);
        $work->delete();
        return redirect()->route('pos_result_index')->with(['message'=>'削除しました','status'=>'alert']);
    }
}

