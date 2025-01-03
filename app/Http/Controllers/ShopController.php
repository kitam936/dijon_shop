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

use App\Models\Report;

class ShopController extends Controller
{


    public function index(Request $request)
    {
        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',7000)->get();
        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();
        $shops = DB::table('shops')
        ->join('areas','areas.id','=','shops.area_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->select('shops.id','shops.shop_name','shops.company_id','shops.area_id','areas.area_name','companies.co_name','shop_info')
        ->where('shops.company_id','>','1000')
        ->where('shops.company_id','<','7000')
        ->where('shops.is_selling','=',1)
        ->where('shops.company_id','LIKE','%'.($request->co_id).'%')
        ->where('shops.area_id','LIKE','%'.($request->area_id).'%')
        ->where('shops.shop_name','LIKE','%'.($request->info).'%')
        // ->orWhere('shops.shop_name','LIKE','%'.($request->info).'%')
        ->paginate(50);


        // dd($request,$companies,$areas,$shops);

        return view('shop.index',compact('shops','areas','companies'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $reports=DB::table('reports')
        ->join('users','users.id','=','reports.user_id')
        ->join('shops','shops.id','=','reports.shop_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('areas','areas.id','=','shops.area_id')
        ->where('reports.shop_id',$id)
        ->select(['reports.id','shops.company_id','companies.co_name','reports.shop_id','shops.shop_name','areas.area_name','shops.shop_info','reports.report','reports.image1','reports.created_at','reports.updated_at','users.name'])
        ->orderBy('updated_at','desc')
        ->paginate(50);

        $shop = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('areas','areas.id','=','shops.area_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name','areas.area_name','shops.shop_info'])
        ->first();
        // dd($shops,$reports);

        return view('shop.show',compact('shop','reports'));
        // return view('User.shop.show',compact('shops'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }




    public function s_search_form_m_sales(Request $request)
    {
        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',4000)->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();


        $m_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('YM')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy('YM')
        ->orderBy('YM','desc')
        ->get();


        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        // dd($m_sales,$s_stocks,$shops);
        return view('User.shop.search_m_sales',compact('m_sales','s_stocks','companies','all_stocks','brands'));
    }

    public function s_search_form_w_sales(Request $request)
    {
        $companies = Company::with('shop')
        ->where('id','>',1000)
        ->where('id','<',4000)->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        // $w_sales = Sale::where('shop_id','LIKE','%'.($request->sh_id).'%')
        // ->select('YW','YM')
        // ->selectRaw('SUM(kingaku) as kingaku')
        // ->groupBy('YW','YM')
        // ->orderBy('YW','desc')
        // ->orderBy('YM','desc')
        // ->get();

        $w_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('YW','YM')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy('YW','YM')
        ->orderBy('YW','desc')
        ->orderBy('YM','desc')
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        // dd($companies,$m_sales,$m_sales_all);
        return view('User.shop.search_w_sales',compact('w_sales','s_stocks','companies','all_stocks','brands'));
    }

    public function s_search_form_u_sales(Request $request)
    {
        $companies = Company::with('shop')
        ->where('id','>',1000)
        ->where('id','<',4000)
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $u_sales_all = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->select(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();

        $u_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shops.id','LIKE','%'.($request->sh_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();
        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();
        $max_YW=Sale::max('YW');
        $max_YM=Sale::max('YM');
        $min_YW=Sale::max('YW');
        // dd($companies,$u_sales,$u_sales_all,$c_stocks,$all_stocks,$max_YW,$max_YW,$YWs,$YWs);
        return view('User.shop.search_u_sales',compact('companies','u_sales_all','u_sales','s_stocks','all_stocks','max_YW','min_YW','YWs','brands'));

    }

    public function s_search_form_s_sales(Request $request)
    {
        $companies = Company::with('shop')
        ->where('id','>',1000)
        ->where('id','<',4000)
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $s_sales_all = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->select(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();

        $s_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shops.id','LIKE','%'.($request->sh_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();
        $max_YW=Sale::max('YW');
        $max_YM=Sale::max('YM');
        $min_YW=Sale::max('YW');
        // dd($companies,$s_sales,$s_sales_all,$c_stocks,$all_stocks,$YWWs,$max_YW,$max_YW,$YWs,$YWs);
        return view('User.shop.search_s_sales',
        compact('companies','s_sales_all','s_sales','s_stocks','all_stocks','max_YW','min_YW','YWs','brands'));
    }

    public function s_search_form_h_sales(Request $request)
    {
        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',4000)->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $h_sales_all = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->select(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->orderBy('pcs','desc')
        ->paginate(20);

        $h_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shops.id','LIKE','%'.($request->sh_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->selectRaw('SUM(pcs) as pcs')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->orderBy('pcs','desc')
        ->paginate(20);

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();
        $max_YW=Sale::max('YW');
        $max_YM=Sale::max('YM');

        // $YWs=Sale::YWs()->get();

        $max_YW=Sale::max('YW');
        $max_YW=Sale::max('YW');
        $min_YW=Sale::max('YW');
        // dd($h_sales,$h_sales_all);
        return view('User.shop.search_h_sales',
        compact('companies','h_sales_all','h_sales','s_stocks','all_stocks','max_YW','min_YW','YWs','brands'));
    }

    public function s_search_form_hz_stocks(Request $request)
    {
        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',4000)->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $h_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shops.id','LIKE','%'.($request->sh_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->orderBy('pcs','desc')
        ->get(['stocks.hinban_id','hinbans.hinmei','stocks.pcs','stocks.zaikogaku','hinbans.unit_id']);

        $h_stocks_all = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shop_id','>',1000)->where('shop_id','<',4000)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->orderBy('pcs','desc')
        ->get(['stocks.hinban_id','hinbans.hinmei','stocks.pcs','stocks.zaikogaku','hinbans.unit_id']);

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();
        // dd($h_stocks,$h_stocks_all,$all_stocks,$c_stocks);
        return view('User.shop.search_hz_stocks',
        compact('companies','h_stocks','all_stocks','h_stocks_all','s_stocks','brands'));
    }

    public function s_search_form_uz_stocks(Request $request)
    {
        $companies = Company::where('id','>',1000)->where('id','<',4000)->select('id','co_name')->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $u_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shops.id','LIKE','%'.($request->sh_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['hinbans.unit_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['hinbans.unit_id','units.season_name'])
        ->get();

        $u_stocks_all = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shop_id','>',1000)->where('shop_id','<',4000)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['hinbans.unit_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['hinbans.unit_id','units.season_name'])
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        // dd($h_sales,$h_sales_all);
        return view('User.shop.search_uz_stocks',
        compact('companies','s_stocks','all_stocks','u_stocks','u_stocks_all','brands'));
    }

    public function s_search_form_sz_stocks(Request $request)
    {
        $companies = Company::where('id','>',1000)->where('id','<',4000)->select('id','co_name')->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();


        $season_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shops.id','LIKE','%'.($request->sh_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['units.season_id','units.season_name'])
        ->get();

        $season_stocks_all = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('shop_id','>',1000)->where('shop_id','<',4000)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['units.season_id','units.season_name'])
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('shops.id','LIKE','%'.$request->sh_id.'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();


        // dd($h_sales,$h_sales_all);
        return view('User.shop.search_sz_stocks',
        compact('companies','season_stocks','all_stocks','season_stocks_all','s_stocks','brands'));
    }

    public function s_form_m_sales(Request $request,$id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $m_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->where('sales.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('YM')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy('YM')
        ->orderBy('YM','desc')
        ->get();


        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();


        // dd($m_sales,$s_stocks,$companies);
        return view('User.shop.m_sales',compact('m_sales','s_stocks','shops','all_stocks','brands'));
    }


    public function s_form_w_sales(Request $request,$id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $w_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->where('sales.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('YW','YM')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy('YW','YM')
        ->orderBy('YW','desc')
        ->orderBy('YM','desc')
        ->get();


        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();


        // dd($m_sales,$s_stocks,$companies);
        return view('User.shop.w_sales',compact('w_sales','s_stocks','shops','all_stocks','brands'));
    }


    public function s_form_u_sales(Request $request,$id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $u_sales_all = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->select(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();

        $u_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('sales.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();
        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();
        $max_YW=Sale::max('YW');
        $max_YM=Sale::max('YM');
        $min_YW=Sale::max('YW');
        // dd($companies,$u_sales,$u_sales_all,$c_stocks,$all_stocks,$max_YW,$max_YW,$YWs,$YWs);
        return view('User.shop.u_sales',compact('shops','u_sales_all','u_sales','s_stocks','all_stocks','max_YW','min_YW','YWs','brands'));

    }


    public function s_form_s_sales(Request $request, $id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $s_sales_all = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();
        $s_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('sales.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','units.season_id','units.season_name'])
        ->orderBy('pcs','desc')
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();
        $max_YW=Sale::max('YW');
        $max_YM=Sale::max('YM');
        $min_YW=Sale::max('YW');
        // dd($companies,$s_sales,$s_sales_all,$c_stocks,$all_stocks,$YWWs,$max_YW,$max_YW,$YWs,$YWs);
        return view('User.shop.s_sales',compact('shops','s_sales_all','s_sales','s_stocks','all_stocks','max_YW','min_YW','YWs','brands'));
    }




    public function s_form_h_sales(Request $request, $id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $h_sales_all = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('sales.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->orderBy('pcs','desc')
        ->get();

        $h_sales = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('sales.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->select(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->selectRaw('SUM(pcs) as pcs')
        ->groupBy(['hinbans.yearea_code','hinbans.unit_id','sales.hinban_id','hinbans.hinmei'])
        ->orderBy('pcs','desc')
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();
        $max_YW=Sale::max('YW');
        $max_YM=Sale::max('YM');

        // $YWs=Sale::YWs()->get();

        $max_YW=Sale::max('YW');
        $max_YW=Sale::max('YW');
        $min_YW=Sale::max('YW');
        // dd($h_sales,$h_sales_all);
        return view('User.shop.h_sales',compact('shops','h_sales_all','h_sales','s_stocks','all_stocks','max_YW','min_YW','YWs','brands'));
    }



    public function s_form_hz_stocks(Request $request,$id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $h_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->orderBy('pcs','desc')
        ->get(['stocks.hinban_id','hinbans.hinmei','stocks.pcs','stocks.zaikogaku','hinbans.unit_id']);

        $h_stocks_all = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy('stocks.hinban_id','hinbans.hinmei','hinbans.unit_id')
        ->orderBy('pcs','desc')
        ->get(['stocks.hinban_id','hinbans.hinmei','stocks.pcs','stocks.zaikogaku','hinbans.unit_id']);

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        // dd($h_stocks,$h_stocks_all,$all_stocks,$c_stocks);
        return view('User.shop.hz_stocks',compact('shops','h_stocks','all_stocks','h_stocks_all','s_stocks','brands'));
    }


    public function s_form_uz_stocks(Request $request,$id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $u_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['hinbans.unit_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['hinbans.unit_id','units.season_name'])
        ->get();

        $u_stocks_all = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['hinbans.unit_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['hinbans.unit_id','units.season_name'])
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();


        // dd($h_sales,$h_sales_all);
        return view('User.shop.uz_stocks',compact('shops','s_stocks','all_stocks','u_stocks','u_stocks_all','brands'));
    }



    public function s_form_sz_stocks(Request $request,$id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();


        $season_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['units.season_id','units.season_name'])
        ->get();

        $season_stocks_all = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['units.season_id','units.season_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->groupBy(['units.season_id','units.season_name'])
        ->get();

        $s_stocks = DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('stocks.shop_id',$id)
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();

        $all_stocks = DB::table('stocks')
        ->join('hinbans','stocks.hinban_id','=','hinbans.id')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->selectRaw('SUM(zaikogaku) as zaikogaku')
        ->selectRaw('SUM(pcs) as pcs')
        ->get();
        // dd($h_sales,$h_sales_all);
        return view('User.shop.sz_stocks',compact('shops','season_stocks','all_stocks','season_stocks_all','s_stocks','brands'));
    }

    public function s_sales_rank(Request $request)
    {
        $s_ranks = DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('companies','shops.company_id','=','companies.id')
        ->join('hinbans','sales.hinban_id','=','hinbans.id')
        ->where('sales.shop_id','>',1000)->where('sales.shop_id','<',4000)
        ->where('sales.YW','>=',($request->YW1 ?? Sale::max('YW')))
        ->where('sales.YW','<=',($request->YW2 ?? Sale::max('YW')))
        ->where('shops.company_id','LIKE','%'.($request->co_id).'%')
        ->where('shops.area_id','LIKE','%'.($request->area_id).'%')
        ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->selectRaw('SUM(pcs) as pcs')
        ->selectRaw('SUM(kingaku) as kingaku')
        ->groupBy(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->orderBy('kingaku','desc')
        // ->get();
        ->paginate(20);

        $brands=DB::table('brands')
        ->select(['id','br_name'])
        ->groupBy(['id','br_name'])
        ->orderBy('id','asc')
        ->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();

        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',4000)->get();


        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();


        $max_YM=Sale::max('YM');
        $max_YW=Sale::max('YW');
        $min_YW=Sale::max('YW');
        // dd($companies,$u_sales,$u_sales_all,$c_stocks,$all_stocks,$YMWs,$max_YM,$max_YW,$YMs,$YWs);
        return view('User.shop.s_sales_rank',compact('companies','s_ranks','max_YM','max_YW','YWs','min_YW','areas','brands'));
    }




}
