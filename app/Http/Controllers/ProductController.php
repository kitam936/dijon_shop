<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Company;
use App\Models\Area;
use App\Models\Hinban;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Hinban::with('unit')->paginate(50);
        $products_sele = DB::table('hinbans')
        ->join('units','units.id','=','hinbans.unit_id')
        ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
        ->where('units.season_id','LIKE','%'.($request->season_code).'%')
        ->where('hinbans.unit_id','LIKE','%'.($request->unit_code).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('hinbans.id','LIKE','%'.($request->hinban_code).'%')
        ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','hinbans.m_price','hinbans.price'])
        ->orderBy('hinbans.year_code','desc')
        ->orderBy('hinbans.brand_id','asc')
        ->orderBy('hinbans.id','desc')
        ->paginate(50);
        // ->get();
        $years=DB::table('hinbans')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
        ->get();
        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();
        $units=DB::table('units')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();
        $brands=DB::table('brands')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        return view('product.index',compact('products','seasons','units','years','products_sele','brands'));
    }

    public function show($id)
    {

        $product = DB::table('hinbans')
        ->join('units','units.id','=','hinbans.unit_id')
        ->where('hinbans.id',$id)
        ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price'])
        ->first();

        $sku_stocks = DB::table('stocks')
        ->join('skus','skus.id','=','stocks.sku_id')
        ->where('skus.hinban_id',$id)
        ->select('sku_id')
        ->selectRaw('SUM(pcs) as pcs')
        ->groupBy('sku_id');
        // ->get();

        $skus = DB::table('skus')
        ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
            $join->on('skus.id','=','sku_stocks.sku_id');})
        ->where('skus.hinban_id',$id)
        ->where('skus.col_id','<','99')
        ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
        ->get();

        // dd($id,$product,$skus);

        return view('product.show',compact('product','skus'));
    }

    public function sku_zaiko($id)
    {
        // $hinbans = Hinban::findOrFail($id)->first();
        $skus = DB::table('stocks')
        ->where('sku_id','=',$id)
        ->select(['sku_id'])
        // ->groupBy(['tocks.hinban_id'])
        ->orderBy('sku_id','asc')
        ->first();


        $sku_shop_stocks = DB::table('stocks')
        ->join('shops','shops.id','=','stocks.shop_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('areas','areas.id','=','shops.area_id')
        ->where('stocks.sku_id','=',$id)
        ->select(['stocks.sku_id','shops.company_id','companies.co_name','stocks.shop_id','shops.shop_name','stocks.pcs','areas.id','areas.area_name'])
        ->orderBy('stocks.pcs','desc')
        ->orderBy('areas.id','asc')
        ->orderBy('companies.id','asc')
        ->orderBy('stocks.shop_id','asc')
        ->paginate(15);

        // dd($h_shop_stocks,$hinbans);
        if(is_null($skus)){
            return to_route('product_index')
            ->with(['message'=>'在庫データがありません','status'=>'alert']);
            // dd($h_shop_stocks);
        }else{
            return view('product.sku_shop_zaiko',compact('sku_shop_stocks','skus'));
        }


    }
}
