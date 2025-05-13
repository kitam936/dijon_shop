<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\InventoryDetail;
use App\Models\InventoryHeader;
use App\Models\InventoryWork;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function scan() {
        // $works = InventoryWork::where('shop_id', Auth::user()->shop_id)
        // ->orderBy('created_at', 'desc') // 新しい順
        // ->limit(5)                      // 5件に絞る
        // ->get();

        $works = DB::table('inventory_works')
        ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
        ->leftjoin('skus','skus.id','inventory_works.sku_id')
        ->where('inventory_works.shop_id', Auth::user()->shop_id)
        ->selectRaw('inventory_works.id,inventory_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,inventory_works.hinban_id,inventory_works.pcs,inventory_works.created_at')
        ->orderBy('created_at', 'desc') // 新しい順
        ->limit(5)                      // 5件に絞る
        ->get();

        $works_total = InventoryWork::where('shop_id', Auth::user()->shop_id)
        ->groupBy('inventory_works.shop_id')
        ->selectRaw('inventory_works.shop_id,sum(inventory_works.pcs) as pcs')                            // 5件に絞る
        ->first();
        // dd($works,$works_total);
        return view('inventory.scan',compact('works','works_total'));
    }

    public function store(Request $request) {
        InventoryWork::create([
            'user_id' => Auth::user()->id ,
            'shop_id' => Auth::user()->shop_id ,
            'raw_cd' => $request->raw_cd,
            'hinban_id' => substr($request->raw_cd, 0, 6),
            'sku_id' => substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1),
            'pcs' => 1,
        ]);
        // return redirect()->back()->with(['message'=>'バーコードを読み取りました','status'=>'info']);
        return redirect()->route('inventory_scan')->with(['message'=>'バーコードを読み取りました','status'=>'info']);
    }

    public function manual(Request $request) {
        InventoryWork::create([
            'user_id' => Auth::user()->id,
            'shop_id' => Auth::user()->shop_id ,
            'raw_cd' => $request->raw_cd,
            'sku_id' => substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1),
            'hinban_id' => substr($request->raw_cd, 0, 6),
            'pcs' => $request->pcs,
        ]);
        return redirect()->back()->with(['message'=>'手入力で追加しました','status'=>'info']);
    }

    public function confirm() {
        $items = InventoryWork::all();
        return view('inventory.confirm', compact('items'));
    }

    public function complete(Request $request)
{
    $headerId = null;

    DB::transaction(function () use ($request, &$headerId) {
        $user_id = Auth::user()->id;
        $shop_id = Auth::user()->shop_id;
        $works = InventoryWork::where('shop_id', $shop_id)->get();
        if ($works->isEmpty()) {
            throw new \Exception("棚卸データがありません");
        }

        $header = InventoryHeader::create([
            'shop_id' => $shop_id,
            'inventory_date' => now(),
            'memo' => $request->memo,
        ]);

        foreach ($works as $work) {
            InventoryDetail::create([
                'inventory_header_id' => $header->id,
                'user_id' => $user_id,
                'sku_id' => $work->sku_id,
                'pcs' => $work->pcs,
            ]);
        }

        InventoryWork::where('shop_id', $shop_id)->delete();

        $headerId = $header->id;
    });
        // return redirect('/inventory/result/' . InventoryHeader::latest()->first()->id);
        return to_route('inventory_result_index');
    }

    public function result($id) {
        $header = InventoryHeader::with('details')->findOrFail($id);
        $works = InventoryWork::where('shop_id', Auth::user()->shop_id)->paginete(5);
        return view('inventory.result', compact('header','works'));
    }

    public function download($id)
    {
        $inventorys = DB::table('inventory_headers')
        ->join('inventory_details','inventory_details.inventory_header_id','inventory_headers.id')
        ->join('users','users.id','inventory_details.user_id')
        ->join('shops','shops.id','inventory_headers.shop_id')
        ->leftjoin('skus','skus.id','inventory_details.sku_id')
        ->where('inventory_headers.id',$id)
        ->groupBy('inventory_headers.id','inventory_headers.inventory_date','inventory_headers.shop_id','inventory_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id')
        ->selectRaw('inventory_headers.id,inventory_headers.inventory_date,inventory_headers.shop_id,inventory_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,sum(inventory_details.pcs) as pcs')
        ->orderBy('sku_id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'id','date','shop_id','sku_id','hinban_id','col_id','size_id','pcs'];

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
        $response->headers->set('Content-Disposition', 'attachment; filename="棚卸実績.csv"');

         // Status変更
         $header=InventoryHeader::findOrFail($id);
         $header->status_id = 5;
         $header->save();

        return $response;

    }


    public function result_index() {
        $inventory_hs = DB::table('inventory_headers')
        ->join('inventory_details','inventory_details.inventory_header_id','inventory_headers.id')
        ->join('users','users.id','inventory_details.user_id')
        ->join('shops','shops.id','inventory_headers.shop_id')
        ->join('iv_statuses','iv_statuses.id','inventory_headers.status_id')
        ->where('inventory_headers.shop_id',Auth::user()->shop_id)
        ->groupBy('inventory_details.inventory_header_id','inventory_headers.shop_id','inventory_headers.status_id','iv_statuses.status_name','shops.shop_name','users.name','inventory_headers.inventory_date')
        ->selectRaw('inventory_details.inventory_header_id as id,inventory_headers.inventory_date,inventory_headers.shop_id,inventory_headers.status_id,iv_statuses.status_name,shops.shop_name,users.name,sum(inventory_details.pcs) as pcs')
        ->orderBy('inventory_header_id','desc')
        ->paginate(50);

        $inventory_hs2 = DB::table('inventory_headers')
        ->join('inventory_details','inventory_details.inventory_header_id','inventory_headers.id')
        ->join('users','users.id','inventory_details.user_id')
        ->join('shops','shops.id','inventory_headers.shop_id')
        ->join('iv_statuses','iv_statuses.id','inventory_headers.status_id')
        ->groupBy('inventory_details.inventory_header_id','inventory_headers.shop_id','inventory_headers.status_id','iv_statuses.status_name','shops.shop_name','users.name','inventory_headers.inventory_date')
        ->selectRaw('inventory_details.inventory_header_id as id,inventory_headers.inventory_date,inventory_headers.shop_id,inventory_headers.status_id,iv_statuses.status_name,shops.shop_name,users.name,sum(inventory_details.pcs) as pcs')
        ->orderBy('inventory_header_id','desc')
        ->paginate(50);

        $user = DB::table('users')
        ->where('users.id',Auth::id())->first();
            //    dd($inventory_hs,$user);
        return view('inventory.result_index', compact('inventory_hs','user','inventory_hs2'));
    }

    public function result_show($id) {
        $inventory_h = DB::table('inventory_headers')
        ->join('inventory_details','inventory_details.inventory_header_id','inventory_headers.id')
        ->join('users','users.id','inventory_details.user_id')
        ->join('shops','shops.id','inventory_headers.shop_id')
        ->join('iv_statuses','iv_statuses.id','inventory_headers.status_id')
        ->where('inventory_headers.id',$id)
        ->groupBy('inventory_details.inventory_header_id','inventory_headers.shop_id','inventory_headers.status_id','iv_statuses.status_name','shops.shop_name','users.name','shops.shop_name','inventory_headers.inventory_date','inventory_headers.memo')
        ->selectRaw('inventory_details.inventory_header_id as id,inventory_headers.inventory_date,inventory_headers.shop_id,inventory_headers.status_id,iv_statuses.status_name,inventory_headers.memo,shops.shop_name,sum(inventory_details.pcs) as total_pcs')
        ->orderBy('inventory_header_id','desc')
        ->first();

        $inventory_fs = DB::table('inventory_headers')
        ->join('inventory_details','inventory_details.inventory_header_id','inventory_headers.id')
        ->join('users','users.id','inventory_details.user_id')
        ->join('shops','shops.id','inventory_headers.shop_id')
        ->leftjoin('skus','skus.id','inventory_details.sku_id')
        ->leftjoin('hinbans','hinbans.id','skus.hinban_id')
        ->where('inventory_headers.id',$id)
        ->groupBy('inventory_details.sku_id','skus.hinban_id','hinbans.hinban_name','skus.col_id','skus.size_id')
        ->selectRaw('inventory_details.sku_id,skus.hinban_id,hinbans.hinban_name,skus.col_id,skus.size_id,sum(inventory_details.pcs) as f_pcs')
        ->orderBy('sku_id','asc')
        ->paginate(50);

        $user = DB::table('users')
        ->where('users.id',Auth::id())->first();
            //    dd($inventory_h,$user,$inventory_fs);
        return view('inventory.result_show', compact('inventory_h','inventory_fs','user'));
    }
}

