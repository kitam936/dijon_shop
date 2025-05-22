<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryWork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryWorkController extends Controller
{
    public function index(Request $request)
    {
        if($request->ng == "e"){
            if($request->order == "h"){
                $works = DB::table('inventory_works')
                ->join('users','users.id','inventory_works.user_id')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('skus.id')
                ->selectRaw('inventory_works.id as id,users.name,inventory_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,inventory_works.hinban_id,inventory_works.pcs,inventory_works.created_at')
                ->orderby('inventory_works.sku_id','asc')
                ->paginate(30);

                $h_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = InventoryWork::where('shop_id', Auth::user()->shop_id)
                ->groupBy('inventory_works.shop_id')
                ->selectRaw('inventory_works.shop_id,sum(inventory_works.pcs) as pcs')                            // 5件に絞るMMMnndd

                ->first();
                // dd($works);
                return view('inventory.index', compact('works','works_total','s_exist','h_exist'));
            }else{
                $works = DB::table('inventory_works')
                ->join('users','users.id','inventory_works.user_id')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('skus.id')
                ->selectRaw('inventory_works.id as id,users.name,inventory_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,inventory_works.hinban_id,inventory_works.pcs,inventory_works.created_at')
                ->orderby('id','desc')
                ->paginate(30);

                $h_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = InventoryWork::where('shop_id', Auth::user()->shop_id)
                ->groupBy('inventory_works.shop_id')
                ->selectRaw('inventory_works.shop_id,sum(inventory_works.pcs) as pcs')                            // 5件に絞る
                ->first();
                // dd($works);
                return view('inventory.index', compact('works','works_total','s_exist','h_exist'));
            };

        }else{
            if($request->order == "h"){
                $works = DB::table('inventory_works')
                ->join('users','users.id','inventory_works.user_id')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->selectRaw('inventory_works.id as id,users.name,inventory_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,inventory_works.hinban_id,inventory_works.pcs,inventory_works.created_at')
                ->orderby('inventory_works.sku_id','asc')
                ->paginate(30);

                $h_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = InventoryWork::where('shop_id', Auth::user()->shop_id)
                ->groupBy('inventory_works.shop_id')
                ->selectRaw('inventory_works.shop_id,sum(inventory_works.pcs) as pcs')                            // 5件に絞る
                ->first();
                // dd($works);
                return view('inventory.index', compact('works','works_total','s_exist','h_exist'));
            }else{
                $works = DB::table('inventory_works')
                ->join('users','users.id','inventory_works.user_id')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->selectRaw('inventory_works.id as id,users.name,inventory_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,inventory_works.hinban_id,inventory_works.pcs,inventory_works.created_at')
                ->orderby('id','desc')
                ->paginate(30);

                $h_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('inventory_works')
                ->leftjoin('hinbans','hinbans.id','inventory_works.hinban_id')
                ->leftjoin('skus','skus.id','inventory_works.sku_id')
                ->where('inventory_works.shop_id', Auth::user()->shop_id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = InventoryWork::where('shop_id', Auth::user()->shop_id)
                ->groupBy('inventory_works.shop_id')
                ->selectRaw('inventory_works.shop_id,sum(inventory_works.pcs) as pcs')                            // 5件に絞る
                ->first();
                // dd($works);
                return view('inventory.index', compact('works','works_total','s_exist','h_exist'));
        };
    }
}


    public function update(Request $request, $id)
    {
        $work = InventoryWork::findOrFail($id);
        $work->raw_cd = $request->raw_cd;
        $work->hinban_id = substr($request->raw_cd, 0, 6);
        $work->sku_id = substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1);
        $work->pcs = $request->pcs;
        $work->save();
        return redirect()->route('inventory_index')->with(['message'=>'Dataを更新しました','status'=>'info']);
    }

    public function destroy($id)
    {
        $work = InventoryWork::findOrFail($id);
        $work->delete();
        return redirect()->route('inventory_index')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function destroy2($id)
    {
        $work = InventoryWork::findOrFail($id);
        $work->delete();
        return redirect()->route('inventory_scan')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function update2(Request $request, $id)
    {
        $work = InventoryWork::findOrFail($id);
        $work->raw_cd = $request->raw_cd;
        $work->hinban_id = substr($request->raw_cd, 0, 6);
        $work->sku_id = substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1);
        $work->pcs = $request->pcs;
        $work->save();
        return redirect()->route('inventory_scan')->with(['message'=>'Dataを更新しました','status'=>'info']);
    }

}
