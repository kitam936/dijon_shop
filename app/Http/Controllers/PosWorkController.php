<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PosWork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosWorkController extends Controller
{
    public function index(Request $request)
    {
        if($request->ng == "e"){
            if($request->order == "h"){
                $works = DB::table('pos_works')
                ->join('users','users.id','pos_works.user_id')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->selectRaw('pos_works.id,pos_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,pos_works.hinban_id,hinbans.price,pos_works.price as pos_price,pos_works.pcs,pos_works.created_at')
                ->orderby('pos_works.sku_id','asc')
                ->paginate(30);

                $h_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = PosWork::where('user_id', Auth::user()->id)
                ->groupBy('pos_works.user_id')
                ->selectRaw('pos_works.user_id,sum(pos_works.pcs) as pcs,sum(pos_works.pcs * pos_works.price) as uriage')                            // 5件に絞る
                ->first();
                // dd($works_total);
                return view('pos.index', compact('works','works_total','s_exist','h_exist'));
            }else{
                $works = DB::table('pos_works')
                ->join('users','users.id','pos_works.user_id')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->selectRaw('pos_works.id,pos_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,pos_works.hinban_id,hinbans.price,pos_works.price as pos_price,pos_works.pcs,pos_works.created_at')
                ->orderby('id','desc')
                ->paginate(30);

                $h_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = PosWork::where('user_id', Auth::user()->id)
                ->groupBy('pos_works.user_id')
                ->selectRaw('pos_works.user_id,sum(pos_works.pcs) as pcs,sum(pos_works.pcs * pos_works.price) as uriage')                         // 5件に絞る
                ->first();
                // dd($works);
                return view('pos.index', compact('works','works_total','s_exist','h_exist'));
            };

        }else{
            if($request->order == "h"){
                $works = DB::table('pos_works')
                ->join('users','users.id','pos_works.user_id')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->selectRaw('pos_works.id,pos_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,pos_works.hinban_id,hinbans.price,pos_works.price as pos_price,pos_works.pcs,pos_works.created_at')
                ->orderby('pos_works.sku_id','asc')
                ->paginate(30);

                $h_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = PosWork::where('user_id', Auth::user()->id)
                ->groupBy('pos_works.user_id')
                ->selectRaw('pos_works.user_id,sum(pos_works.pcs) as pcs,sum(pos_works.pcs * pos_works.price) as uriage')
                ->first();
                // dd($works);
                return view('pos.index', compact('works','works_total','s_exist','h_exist'));
            }else{
                $works = DB::table('pos_works')
                ->join('users','users.id','pos_works.user_id')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->selectRaw('pos_works.id,pos_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,pos_works.hinban_id,hinbans.price,pos_works.price as pos_price,pos_works.pcs,pos_works.created_at')
                ->orderby('id','desc')
                ->paginate(30);

                $h_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('hinbans.id')
                ->exists();

                $s_exist = DB::table('pos_works')
                ->leftjoin('hinbans','hinbans.id','pos_works.hinban_id')
                ->leftjoin('skus','skus.id','pos_works.sku_id')
                ->where('pos_works.user_id', Auth::user()->id)
                ->whereNull('skus.id')
                ->exists();

                $works_total = PosWork::where('user_id', Auth::user()->id)
                ->groupBy('pos_works.user_id')
                ->selectRaw('pos_works.user_id,sum(pos_works.pcs) as pcs,sum(pos_works.pcs * pos_works.price) as uriage')
                ->first();
                // dd($works);
                return view('pos.index', compact('works','works_total','s_exist','h_exist'));
        };
    }
}


    public function update(Request $request, $id)
    {
        $work = PosWork::findOrFail($id);
        // $work->raw_cd = $request->raw_cd;
        // $work->hinban_id = substr($request->raw_cd, 0, 6);
        // $work->sku_id = substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1);
        // $work->pcs = $request->pcs;
        $work->price = $request->pos_price2;
        $work->save();
        return redirect()->route('pos_index')->with(['message'=>'Dataを更新しました','status'=>'info']);
    }

    public function destroy($id)
    {
        $work = PosWork::findOrFail($id);
        $work->delete();
        return redirect()->route('pos_index')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function destroy2($id)
    {
        $work = PosWork::findOrFail($id);
        $work->delete();
        return redirect()->route('pos_scan')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function update2(Request $request, $id)
    {
        $work = PosWork::findOrFail($id);

        // dd($work,$request->pos_price2);
        // $work->raw_cd = $request->raw_cd;
        // $work->hinban_id = substr($request->raw_cd, 0, 6);
        // $work->sku_id = substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1);
        $work->price = $request->pos_price2;
        // $work->pcs = $request->pcs;
        $work->save();
        return redirect()->route('pos_scan')->with(['message'=>'Dataを更新しました','status'=>'info']);
    }


}
