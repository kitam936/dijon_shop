<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SalesData;
use App\Models\StockData;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Company;
use App\Models\Area;
use App\Models\Ym;
use App\Models\Yms;
use App\Models\Ymd;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Services\AnalysisService;
use Illuminate\Http\Response;

class MyAnalysisController extends Controller
{
    public function analysis_index()
    {
        return view('my_analysis.analysis_menu');
    }

    public function sales_transition(Request $request)
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $my_shop = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name')
        ->first();

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id','unit_code'])
        ->groupBy(['id','unit_code'])
        ->orderBy('id','asc')
        ->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();

        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();

        $max_YMD=SalesData::max('YMD');
        $max_YW=SalesData::max('YW');
        $max_YM=SalesData::max('YM');
        $max_Y=SalesData::max('Y');
        $min_YMD=SalesData::min('YMD');
        $min_YW=SalesData::min('YW');
        $min_YM=SalesData::min('YM');
        $min_Y=SalesData::min('Y');


        if($request->type2 == ''){
            // 初回アクセス時には最大月を表示する
            $subQuery = SalesData::where('YM','>=', $max_YM)
            ->where('YM','<=', $max_YM);

            $prev_subQuery = SalesData::where('YM','>=', $max_YM-100)
            ->where('YM','<=', $max_YM-100);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id','YMD')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase,
            YMD as date');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total');
            // ->orderBy('date','desc')
            // ->get();

            $query2 = $prev_subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YMD')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YMD');

            $prev_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YMD')
            ->selectRaw('YMD as prev_date, sum(totalPerPurchase) as prev_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('ymds')
            ->where('ymds.YMD','<=',$max_YMD)
            ->leftjoinSub($datas, 'cr_data', 'ymds.YMD', '=', 'cr_data.date')
            ->leftjoinSub($prev_datas, 'pv_data', 'ymds.prev_YMD', '=', 'pv_data.prev_date')
            ->select('ymds.YMD as date','ymds.prev_YMD','cr_data.total','pv_data.prev_total')
            ->where('cr_data.total','>',0)
            ->orWhere('pv_data.prev_total','>',0)
            ->orderBy('ymds.YMD','desc')
            ->get();


            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($merged_data,$total);
            return view('my_analysis.sales_transition',
             compact('YMs','max_YM','my_shop',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'd'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
            ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id','YMD')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase,
            YMD as date');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total');
            // ->orderBy('date','desc')
            // ->get();

            $query2 = $prev_subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YMD')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YMD');

            $prev_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YMD')
            ->selectRaw('YMD as prev_date, sum(totalPerPurchase) as prev_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('ymds')
            ->where('ymds.YMD','<=',$max_YMD)
            ->leftjoinSub($datas, 'cr_data', 'ymds.YMD', '=', 'cr_data.date')
            ->leftjoinSub($prev_datas, 'pv_data', 'ymds.prev_YMD', '=', 'pv_data.prev_date')
            ->select('ymds.YMD as date','ymds.prev_YMD','cr_data.total','pv_data.prev_total')
            ->where('cr_data.total','>',0)
            ->orWhere('pv_data.prev_total','>',0)
            ->orderBy('ymds.YMD','desc')
            ->get();


            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($total);
            return view('my_analysis.sales_transition',
             compact('YMs','max_YM','my_shop',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'w'){

            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
            ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id','YW')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase,
            YW as date');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total');
            // ->orderBy('date','desc')
            // ->get();
            // dd($datas);

            $query2 = $prev_subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YW')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YW');

            $prev_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YW')
            ->selectRaw('YW as prev_date, sum(totalPerPurchase) as prev_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();
            // dd($prev_datas);
            $merged_data = DB::table('yws')
            ->where('yws.YW','<=',$max_YW)
            ->leftjoinSub($datas, 'cr_data', 'yws.YW', '=', 'cr_data.date')
            ->leftjoinSub($prev_datas, 'pv_data', 'yws.prev_YW', '=', 'pv_data.prev_date')
            ->select('yws.YW as date','yws.prev_YW','cr_data.total','pv_data.prev_total')
            ->where('cr_data.total','>',0)
            ->orWhere('pv_data.prev_total','>',0)
            ->orderBy('yws.YW','desc')
            ->get();



            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($merged_data,$total,$pv_total);
            return view('my_analysis.sales_transition',
             compact('YMs','max_YM','my_shop',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'm'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
            ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YM')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YM as date');

            $datas = DB::table($query)
            // $datas = $query
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total');
            // ->orderBy('date', 'desc')
            // ->get();


            $date_table = DB::table('sales')
            ->groupBy('YM')
            ->selectRaw('YM ,YM-100 as prev_date');
            // ->orderBy('YM', 'desc')->get();

            // 前年同月データを取得

            $query2 = $prev_subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YM')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YM');

            $prev_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YM')
            ->selectRaw('YM as prev_date, sum(totalPerPurchase) as prev_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('yms')
            ->where('yms.YM','<=',$max_YM)
            ->leftjoinSub($datas, 'cr_data', 'yms.YM', '=', 'cr_data.date')
            ->leftjoinSub($prev_datas, 'pv_data', 'yms.prev_YM', '=', 'pv_data.prev_date')
            ->select('yms.YM as date','yms.prev_YM','cr_data.total','pv_data.prev_total')
            ->where('cr_data.total','>',0)
            ->orWhere('pv_data.prev_total','>',0)
            ->orderBy('yms.YM','desc')
            ->get();


            // dd($merged_data);

            $total = DB::table($query)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();

            $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$previousYearData);
            // dd($merged_data);
            // dd($date_table,$datas,$prev_datas);
            return view('my_analysis.sales_transition',
             compact('YMs','max_YM','my_shop',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'y'){
            $subQuery = SalesData::where('Y','>=', $min_Y)
            ->where('Y','<=',$max_Y);


            $prev_subQuery = SalesData::where('Y','>=', $min_Y-1)
            ->where('Y','<=', $max_Y-1);


            // dd($min_Y-100,$prev_subQuery);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'Y')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, Y as date');

            $datas = DB::table($query)
            // $datas = $query
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total');
            // ->orderBy('date', 'desc')
            // ->get();


            $date_table = DB::table('sales')
            ->groupBy('Y')
            ->selectRaw('Y ,Y-1 as prev_date');
            // ->orderBy('YM', 'desc')->get();

            // 前年同月データを取得

            $query2 = $prev_subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'Y')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, Y');

            $prev_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('Y')
            ->selectRaw('Y as prev_date, sum(totalPerPurchase) as prev_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('yys')
            // ->where('yys.Y','<=',$max_YM)
            ->leftjoinSub($datas, 'cr_data', 'yys.Y', '=', 'cr_data.date')
            ->leftjoinSub($prev_datas, 'pv_data', 'yys.prev_Y', '=', 'pv_data.prev_date')
            ->select('yys.Y as date','yys.prev_Y','cr_data.total','pv_data.prev_total')
            ->where('cr_data.total','>',0)
            ->orWhere('pv_data.prev_total','>',0)
            ->orderBy('yys.Y','desc')
            ->get();


            // dd($merged_data);

            $total = DB::table($query)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();

            $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$previousYearData);
            // dd($merged_data);
            // dd($date_table,$datas,$prev_datas);
            return view('my_analysis.sales_transition',
             compact('YMs','max_YM','my_shop',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

    }

    public function sales_transition_reset()
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $my_shop = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name')
        ->first();

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();


        $units=DB::table('units')
        ->select(['id','unit_code'])
        ->groupBy(['id','unit_code'])
        ->orderBy('id','asc')
        ->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();

        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();

        $max_YMD=SalesData::max('YMD');
        $max_YW=SalesData::max('YW');
        $max_YM=SalesData::max('YM');
        $min_YMD=SalesData::min('YMD');
        $min_YW=SalesData::min('YW');
        $min_YM=SalesData::min('YM');


        // 初回アクセス時には最大月を表示する
        $subQuery = SalesData::where('YM','>=', $max_YM)
        ->where('YM','<=', $max_YM)
        ->where('shop_id',$logIn_user->shop_id);

        $prev_subQuery = SalesData::where('YM','>=', $max_YM-100)
        ->where('YM','<=', $max_YM-100)
        ->where('shop_id',$logIn_user->shop_id);

        $query = $subQuery
        ->groupBy('shop_id','YMD')
        ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase,
        YMD as date');
    // dd($query);
        $datas = DB::table($query)
        ->groupBy('date')
        ->selectRaw('date, sum(totalPerPurchase) as total');
        // ->orderBy('date','desc')
        // ->get();

        $query2 = $prev_subQuery
        ->groupBy('shop_id', 'YMD')
        ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YMD');

        $prev_datas = DB::table($query2)
        // $prev_datas = $query2
        ->groupBy('YMD')
        ->selectRaw('YMD as prev_date, sum(totalPerPurchase) as prev_total');
        // ->orderBy('prev_date', 'desc')
        // ->get();

        $merged_data = DB::table('ymds')
        ->where('ymds.YMD','<=',$max_YMD)
        ->leftjoinSub($datas, 'cr_data', 'ymds.YMD', '=', 'cr_data.date')
        ->leftjoinSub($prev_datas, 'pv_data', 'ymds.prev_YMD', '=', 'pv_data.prev_date')
        ->select('ymds.YMD as date','ymds.prev_YMD','cr_data.total','pv_data.prev_total')
        ->where('cr_data.total','>',0)
        ->orWhere('pv_data.prev_total','>',0)
        ->orderBy('ymds.YMD','desc')
        ->get();


        $total = DB::table($query)
        ->selectRaw('sum(totalPerPurchase) as total')
        ->first();

        $pv_total = DB::table($query2)
        ->selectRaw('sum(totalPerPurchase) as total')
        ->first();

        // dd($total);
        return view('my_analysis.sales_transition',
            compact('YMs','max_YM','my_shop',
            'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
        ));
    }


    public function sales_product(Request $request)
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $my_shop = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name')
        ->first();

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id','unit_code'])
        ->groupBy(['id','unit_code'])
        ->orderBy('id','asc')
        ->get();


        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();

        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();

        $max_YMD=SalesData::max('YMD');
        $max_YW=SalesData::max('YW');
        $max_YM=SalesData::max('YM');
        $min_YMD=SalesData::min('YMD');
        $min_YW=SalesData::min('YW');
        $min_YM=SalesData::min('YM');

        if($request->type3 == ''){
            // 初回アクセス時には最大月を表示する
            $subQuery = SalesData::where('YM','>=', $max_YM)
            ->where('YM','<=', $max_YM);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs,hinban_image');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price','hinban_image')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total,hinban_image as filename')
            ->orderBy('pcs_total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.sales_product',
            compact('YMs','max_YM','my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'h'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->where('vendor_id','<>',8200)
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs,hinban_image');
            // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price','hinban_image')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total,hinban_image as filename')
            ->orderBy('pcs_total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.sales_product',
            compact('YMs','max_YM','my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 's'){

            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->where('vendor_id','<>',8200)
            ->groupBy('sku_id','hinban_id','col_id','size_id')
            ->selectRaw('sku_id,col_id,size_id,hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs,hinban_image,sku_image');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('sku_id','hinban_id','hinban_name','m_price','col_id','size_id','sku_image')
            // ->selectRaw('hinban_id, sku_id as code,hinban_name, price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->selectRaw('sku_id,hinban_id, CONCAT(hinban_id, "-", col_id, "-", size_id) as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total,sku_image as filename')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->paginate(100);

            // ->selectRaw("CONCAT(first_name, ' ', last_name) AS full_name")

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.sales_product',
             compact('YMs','max_YM','my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }
    }

    public function sales_product_reset()
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $my_shop = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name')
        ->first();

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->select(['id','unit_code'])
        ->groupBy(['id','unit_code'])
        ->orderBy('id','asc')
        ->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();

        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();

        $max_YMD=SalesData::max('YMD');
        $max_YW=SalesData::max('YW');
        $max_YM=SalesData::max('YM');
        $min_YMD=SalesData::min('YMD');
        $min_YW=SalesData::min('YW');
        $min_YM=SalesData::min('YM');


        // 初回アクセス時には最大月を表示する
        $subQuery = SalesData::where('YM','>=', $max_YM)
        ->where('YM','<=', $max_YM)
        ->where('shop_id',$logIn_user->shop_id);

        $query = $subQuery
        ->groupBy('hinban_id')
        ->selectRaw('hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs,hinban_image');
    // dd($query);
        $datas = DB::table($query)
        ->groupBy('hinban_id','hinban_name','m_price','hinban_image')
        ->selectRaw('hinban_id, hinban_id as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total,hinban_image as filename')
        ->orderBy('pcs_total','desc')
        ->paginate(100);

        $total = DB::table($query)
        ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
        ->first();

        // dd($datas,$total);
        return view('my_analysis.sales_product',
        compact('YMs','max_YM','my_shop',
            'brands','datas','total', 'seasons','units','faces'
        ));

    }

    public function stocks_product(Request $request)
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $my_shop = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name')
        ->first();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',1000)
        ->where('id','<',7000)
        ->get();

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id','unit_code'])
        ->groupBy(['id','unit_code'])
        ->orderBy('id','asc')
        ->get();


        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();


        if($request->type3 == ''){
            // 初回アクセス時には最大月を表示する
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(zaikogaku) as totalPerZaiko,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name as name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->orderBy('pcs_total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.stocks_product',
            compact('companies','shops',
            'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'h'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->where('vendor_id','<>',8200)
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(zaikogaku) as totalPerZaiko,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name as name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.stocks_product',
            compact('my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 's'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            // ->where('vendor_id','<>',8200)
            ->groupBy('season_id')
            ->selectRaw('season_id,season_name, sum(zaikogaku) as totalPerZaiko,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('season_id','season_name','m_price')
            ->selectRaw('season_id, season_id as code,season_name as name, sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.stocks_product',
            compact('my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'u'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            // ->where('vendor_id','<>',8200)
            ->groupBy('unit_id')
            ->selectRaw('unit_id, sum(zaikogaku) as totalPerZaiko,season_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('unit_id','season_name','m_price')
            ->selectRaw('unit_id, unit_id as code,season_name as name, sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.stocks_product',
            compact('my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'f'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_code','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id',$logIn_user->shop_id)
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            // ->where('vendor_id','<>',8200)
            ->groupBy('face')
            ->selectRaw('face,sum(zaikogaku) as totalPerZaiko,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('face')
            ->selectRaw('face, face as code,face as name, sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->paginate(100);

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('my_analysis.stocks_product',
            compact('my_shop',
                'brands','datas','total', 'seasons','units','faces'
            ));
    }
    }
    public function stocks_product_reset()
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $my_shop = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name')
        ->first();

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->select(['id','unit_code'])
        ->groupBy(['id','unit_code'])
        ->orderBy('id','asc')
        ->get();


        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $seasons=DB::table('units')
        ->select(['season_id','season_name'])
        ->groupBy(['season_id','season_name'])
        ->orderBy('season_id','asc')
        ->get();



        // 初回アクセス時には最大月を表示する
        $subQuery = StockData::where('company_id','>=', 106)
        ->where('company_id','<', 7000)
        ->where('shop_id',$logIn_user->shop_id);

        $query = $subQuery
        ->groupBy('hinban_id')
        ->selectRaw('hinban_id, sum(zaikogaku) as totalPerZaiko,hinban_name,m_price,sum(pcs) as subtotal_pcs');
    // dd($query);
        $datas = DB::table($query)
        ->groupBy('hinban_id','hinban_name','m_price')
        ->selectRaw('hinban_id, hinban_id as code,hinban_name as name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
        ->orderBy('pcs_total','desc')
        ->paginate(100);

        $total = DB::table($query)
        ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
        ->first();

        // dd($datas,$total);
        return view('my_analysis.stocks_product',
        compact('my_shop',
        'brands','datas','total', 'seasons','units','faces'
        ));
    }

}
