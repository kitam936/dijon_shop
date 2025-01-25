<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Hinban;
use App\Models\Sku;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $carts = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->select('carts.user_id','users.shop_id','shops.shop_name','carts.id','carts.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name','hinbans.m_price','carts.pcs')
        ->get();

        $cart_total = DB::table('carts')
        ->where('carts.user_id',Auth::id())
        ->groupBy('carts.user_id')
        ->selectRaw('carts.user_id,sum(carts.pcs) as total_pcs')
        ->first();

        $user = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name','users.name','users.id')
        ->first();
        // dd($carts,$user);
        return view('order.cart_index', compact('carts','user','cart_total'));
    }

    public function edit()
    {
        $carts = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->select('carts.user_id','users.shop_id','shops.shop_name','carts.id','carts.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name','hinbans.m_price','carts.pcs')
        ->get();

        $cart_total = DB::table('carts')
        ->where('carts.user_id',Auth::id())
        ->groupBy('carts.user_id')
        ->selectRaw('carts.user_id,sum(carts.pcs) as total_pcs')
        ->first();

        $user = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name','users.name','users.id')
        ->first();
        // dd($carts,$user);
        return view('order.cart_edit', compact('carts','user','cart_total'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pcs' => 'required|integer|min:1|max:9',
        ]);

        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cartItem->update(['pcs' => $request->pcs]);

        return back()->with(['message'=>'カートが修正されました','status'=>'info']);
    }

    public function create(Request $request)
    {
        // $products = Sku::with('hinban')->paginate(50); // 商品一覧を取得

        $carts = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->groupBy('carts.sku_id')
        ->selectRaw('carts.sku_id,sum(carts.pcs) as pcs');
        // ->get();

        $products = DB::table('skus')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->join('units','units.id','=','hinbans.unit_id')
        ->leftjoinSub($carts, 'carts', 'carts.sku_id', '=', 'skus.id')
        ->where('hinbans.vendor_id','<>',8200)
        ->where('skus.col_id','<>',99)
        ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
        ->where('units.season_id','LIKE','%'.($request->season_code).'%')
        ->where('hinbans.unit_id','LIKE','%'.($request->unit_code).'%')
        ->where('hinbans.face','LIKE','%'.($request->face).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('hinbans.id','LIKE','%'.($request->hinban_code).'%')
        ->select(['skus.id','skus.col_id','size_id','hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','hinbans.m_price','hinbans.price','hinbans.face','carts.pcs'])
        ->orderBy('hinbans.year_code','desc')
        ->orderBy('hinbans.brand_id','asc')
        ->orderBy('hinban_id','desc')
        ->paginate(50);
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
        // dd($products);
        return view('order.cart_create',
        compact('products','years','faces',
                'seasons','units','brands'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'sku_id' => 'required|exists:skus,id',
            'pcs' => 'required|integer|min:1|max:9',
        ]);

        Cart::create([
            'user_id' => Auth::id(),
            'sku_id' => $request->sku_id,
            'pcs' => $request->pcs,
        ]);

        // return to_route('cart_index')->with(['message'=>'Cartに追加されました','status'=>'info']);
        return back()->with(['message'=>'カートに追加されました','status'=>'info']);
    }

    public function destroy($id)
{
    $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    $cartItem->delete();

    return back()->with(['message'=>'カートから削除されました','status'=>'alert']);
}
}
