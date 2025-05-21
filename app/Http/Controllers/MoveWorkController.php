<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoveWork;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoveWorkController extends Controller
{
    public function index(Request $request)
    {
        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',5000)
        ->where('id','<',7000)
        ->orWhere('id',106)
        ->get();

        if($request->ng == "e"){
            if($request->order == "h"){
                $works = DB::table('move_works')
                ->join('users','users.id','move_works.user_id')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->selectRaw('move_works.id,move_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,move_works.hinban_id,hinbans.price,move_works.price as move_price,move_works.pcs,move_works.created_at')
                ->orderby('move_works.sku_id','asc')
                ->paginate(30);

                $h_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = MoveWork::where('user_id', Auth::user()->id)
                ->groupBy('move_works.user_id')
                ->selectRaw('move_works.user_id,sum(move_works.pcs) as pcs')
                ->first();
                // dd($works_total);
                return view('move.index', compact('works','works_total','s_exist','h_exist','shops'));
            }else{
                $works = DB::table('move_works')
                ->join('users','users.id','move_works.user_id')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->selectRaw('move_works.id,move_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,move_works.hinban_id,hinbans.price,move_works.price as move_price,move_works.pcs,move_works.created_at')
                ->orderby('id','desc')
                ->paginate(30);

                $h_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = MoveWork::where('user_id', Auth::user()->id)
                ->groupBy('move_works.user_id')
                ->selectRaw('move_works.user_id,sum(move_works.pcs) as pcs')
                ->first();
                // dd($works);
                return view('move.index', compact('works','works_total','s_exist','h_exist','shops'));
            };

        }else{
            if($request->order == "h"){
                $works = DB::table('move_works')
                ->join('users','users.id','move_works.user_id')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->selectRaw('move_works.id,move_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,move_works.hinban_id,hinbans.price,move_works.price as move_price,move_works.pcs,move_works.created_at')
                ->orderby('move_works.sku_id','asc')
                ->paginate(30);

                $h_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = MoveWork::where('user_id', Auth::user()->id)
                ->groupBy('move_works.user_id')
                ->selectRaw('move_works.user_id,sum(move_works.pcs) as pcs')
                ->first();
                // dd($works);
                return view('move.index', compact('works','works_total','s_exist','h_exist','shops'));
            }else{
                $works = DB::table('move_works')
                ->join('users','users.id','move_works.user_id')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->selectRaw('move_works.id,move_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,move_works.hinban_id,hinbans.price,move_works.price as move_price,move_works.pcs,move_works.created_at')
                ->orderby('id','desc')
                ->paginate(30);

                $h_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('move_works')
                ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
                ->leftjoin('skus','skus.id','move_works.sku_id')
                ->where('move_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = MoveWork::where('user_id', Auth::user()->id)
                ->groupBy('move_works.user_id')
                ->selectRaw('move_works.user_id,sum(move_works.pcs) as pcs')
                ->first();
                // dd($works);
                return view('move.index', compact('works','works_total','s_exist','h_exist','shops'));
        };
    }
}


    public function update(Request $request, $id)
    {
        $work = MoveWork::findOrFail($id);
        // $work->raw_cd = $request->raw_cd;
        // $work->hinban_id = substr($request->raw_cd, 0, 6);
        // $work->sku_id = substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1);
        $work->pcs = $request->pcs;
        $work->price = $request->move_price2;
        $work->save();
        return redirect()->route('move_index')->with(['message'=>'Dataを更新しました','status'=>'info']);
    }

    public function destroy($id)
    {
        $work = MoveWork::findOrFail($id);
        $work->delete();
        return redirect()->route('move_index')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function destroy2($id)
    {
        $work = MoveWork::findOrFail($id);
        $work->delete();
        return redirect()->route('move_scan')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function update2(Request $request, $id)
    {
        $work = MoveWork::findOrFail($id);

        // dd($work,$request->move_price2);
        // $work->raw_cd = $request->raw_cd;
        // $work->hinban_id = substr($request->raw_cd, 0, 6);
        // $work->sku_id = substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1);
        $work->price = $request->move_price2;
        $work->pcs = $request->pcs;
        $work->save();
        return redirect()->route('move_scan')->with(['message'=>'Dataを更新しました','status'=>'info']);
    }

}
