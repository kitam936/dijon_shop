<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SalesData;
use App\Models\StockData;
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

class AnalysisController extends Controller
{
    public function analysis_index()
    {
        return view('analysis.analysis_menu');
    }

    public function sales_transition(Request $request)
    {
        // $subquery = SalesData::paginate(50);
        // $subquery_stock = StockData::paginate(50);
        // dd($subquery,$subquery_stock);

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();


        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',1000)
        ->where('id','<',7000)
        ->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
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

        // $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
        // ->where('YM','<=',($request->YM2 ?? $max_YM));

        // $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
        // ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

        if($request->type2 == ''){
            // 初回アクセス時には最大月を表示する
            $subQuery = SalesData::where('YM','>=', $max_YM)
            ->where('YM','<=', $max_YM);

            $prev_subQuery = SalesData::where('YM','>=', $max_YM-100)
            ->where('YM','<=', $max_YM-100);

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
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
            return view('analysis.sales_transition',
             compact('YMs','max_YM','companies','shops','areas',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'd'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
            ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
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
            return view('analysis.sales_transition',
             compact('YMs','max_YM','companies','shops','areas',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'w'){

            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
            ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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

            $query2 = $prev_subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
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

            // dd($total);
            return view('analysis.sales_transition',
             compact('YMs','max_YM','companies','shops','areas',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

        if($request->type2 == 'm'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
            ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
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
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
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
            return view('analysis.sales_transition',
             compact('YMs','max_YM','companies','shops','areas',
                'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
            ));
        }

    }

    public function sales_transition_reset()
    {
        // $subquery = SalesData::paginate(50);
        // $subquery_stock = StockData::paginate(50);
        // dd($subquery,$subquery_stock);

        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();


        $units=DB::table('units')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('id','>',1000)
        ->where('id','<',7000)->get();


        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
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

        // $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
        // ->where('YM','<=',($request->YM2 ?? $max_YM));

        // $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
        // ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));


        // 初回アクセス時には最大月を表示する
        $subQuery = SalesData::where('YM','>=', $max_YM)
        ->where('YM','<=', $max_YM);

        $prev_subQuery = SalesData::where('YM','>=', $max_YM-100)
        ->where('YM','<=', $max_YM-100);

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
        return view('analysis.sales_transition',
            compact('YMs','max_YM','companies','shops','areas',
            'brands','datas','merged_data','total', 'seasons','pv_total','units','faces'
        ));
    }

    public function sales_total(Request $request)
    {
        // $subquery = SalesData::paginate(50);
        // $subquery_stock = StockData::paginate(50);
        // dd($subquery,$subquery_stock);
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

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();

        $max_YM=SalesData::max('YM');
        $max_YW=SalesData::max('YW');
        $min_YM=SalesData::min('YM');
        $min_YW=SalesData::min('YW');


        // $subQuery = SalesData::where('YW','>=',($request->YW1 ?? $max_YW))
        // ->where('YW','<=',($request->YW2 ?? $max_YW));

        // $prev_subQuery = SalesData::where('YW','>=',($request->YW1-100 ?? $max_YW-100))
        // ->where('YW','<=',($request->YW2-100 ?? $max_YW-100));



        if($request->type1 == ''){
            $subQuery = SalesData::where('YW','>=', $max_YW)
            ->where('YW','<=', $max_YW);

            $prev_subQuery = SalesData::where('YW','>=', $max_YW-100)
            ->where('YW','<=', $max_YW-100);

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id')
            ->selectRaw('shop_id,shop_name, sum(kingaku) as totalPerPurchase');

            $datas = DB::table($query)
            ->groupBy('shop_id','shop_name')
            ->selectRaw('shop_id ,shop_name as name,sum(totalPerPurchase) as total');
            // ->orderBy('total','desc');
            // ->get();

            $shops = DB::table('shops')->get();
            // 前年同月データを取得

            $query2 = $prev_subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id')
            ->selectRaw('shop_id,shop_name, sum(kingaku) as totalPerPurchase');

            $prev_datas = DB::table($query2)
            ->groupBy('shop_id','shop_name')
            ->selectRaw('shop_id ,shop_name as name,sum(totalPerPurchase) as pv_total');
            // ->orderBy('total','desc');
            // ->get();

            $merged_data = DB::table('shops')
            ->where('shops.company_id','>',1000)
            ->leftjoinSub($datas, 'cr_data', 'shops.id', '=', 'cr_data.shop_id')
            ->leftjoinSub($prev_datas, 'pv_data', 'shops.id', '=', 'pv_data.shop_id')
            ->select('shops.id','shops.shop_name as name','cr_data.total','pv_data.pv_total')
            ->orderBy('total','desc')
            ->get();

            // dd($YM,$datas,$prev_datas);
        // 比較データを作成
            $total = DB::table($query)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();

            $pv_total = DB::table($query2)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();

            // dd($shops,$merged_data);
            // dd($date_table,$datas,$prev_datas,$merged_data);
            // dd($shops,$datas,$prev_datas);
            return view('analysis.sales_total', compact('YMs','max_YM','YWs','max_YW','brands','datas','seasons','units','faces','total','pv_total','merged_data'));
        }

        if($request->type1 == 'co'){
            $subQuery = SalesData::where('YW','>=',($request->YW1 ?? $max_YW))
            ->where('YW','<=',($request->YW2 ?? $max_YW));

            $prev_subQuery = SalesData::where('YW','>=',($request->YW1-100 ?? $max_YW-100))
            ->where('YW','<=',($request->YW2-100 ?? $max_YW-100));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('company_id')
            ->selectRaw('company_id,co_name, sum(kingaku) as totalPerPurchase');

            $datas = DB::table($query)
            ->groupBy('company_id','co_name')
            ->selectRaw('company_id, co_name as name,sum(totalPerPurchase) as total');
            // ->orderBy('total','desc')
            // ->get();

            $query2 = $prev_subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('company_id')
            ->selectRaw('company_id,co_name, sum(kingaku) as totalPerPurchase');

            $prev_datas = DB::table($query2)
            ->groupBy('company_id','co_name')
            ->selectRaw('company_id ,co_name as name,sum(totalPerPurchase) as pv_total');
            // ->orderBy('total','desc');
            // ->get();

            $merged_data = DB::table('companies')
            ->where('companies.id','>',1000)
            ->leftjoinSub($datas, 'cr_data', 'companies.id', '=', 'cr_data.company_id')
            ->leftjoinSub($prev_datas, 'pv_data', 'companies.id', '=', 'pv_data.company_id')
            ->select('companies.id','companies.co_name as name','cr_data.total','pv_data.pv_total')
            ->orderBy('total','desc')
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            $pv_total = DB::table($query2)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();


            // dd($datas);
            return view('analysis.sales_total',compact('YMs','max_YM','YWs','max_YW','brands','datas','seasons','units','faces','total','pv_total','merged_data'));
        }

        if($request->type1 == 'sh'){
            $subQuery = SalesData::where('YW','>=',($request->YW1 ?? $max_YW))
            ->where('YW','<=',($request->YW2 ?? $max_YW));

            $prev_subQuery = SalesData::where('YW','>=',($request->YW1-100 ?? $max_YW-100))
            ->where('YW','<=',($request->YW2-100 ?? $max_YW-100));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id')
            ->selectRaw('shop_id,shop_name, sum(kingaku) as totalPerPurchase');

            $datas = DB::table($query)
            ->groupBy('shop_id','shop_name')
            ->selectRaw('shop_id ,shop_name as name,sum(totalPerPurchase) as total');
            // ->orderBy('total','desc');
            // ->get();

            // 前年同月データを取得

            $query2 = $prev_subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id')
            ->selectRaw('shop_id,shop_name, sum(kingaku) as totalPerPurchase');

            $prev_datas = DB::table($query2)
            ->groupBy('shop_id','shop_name')
            ->selectRaw('shop_id ,shop_name as name,sum(totalPerPurchase) as pv_total');
            // ->orderBy('total','desc');
            // ->get();

            $merged_data = DB::table('shops')
            ->where('shops.company_id','>',1000)
            ->leftjoinSub($datas, 'cr_data', 'shops.id', '=', 'cr_data.shop_id')
            ->leftjoinSub($prev_datas, 'pv_data', 'shops.id', '=', 'pv_data.shop_id')
            ->select('shops.id','shops.shop_name as name','cr_data.total','pv_data.pv_total')
            ->orderBy('total','desc')
            ->get();

            // dd($YM,$datas,$prev_datas);
        // 比較データを作成
            $total = DB::table($query)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();

            $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($shops,$merged_data);
            // dd($date_table,$datas,$prev_datas,$merged_data);
            // dd($shops,$datas,$prev_datas);
            return view('analysis.sales_total', compact('YMs','max_YM','YWs','max_YW','brands','datas','seasons','units','faces','total','pv_total','merged_data'));
        }

    }

    public function sales_total_reset()
    {
        // $subquery = SalesData::paginate(50);
        // $subquery_stock = StockData::paginate(50);
        // dd($subquery,$subquery_stock);
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
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();

        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc')
        ->get();

        $max_YM=SalesData::max('YM');
        $max_YW=SalesData::max('YW');
        $min_YM=SalesData::min('YM');
        $min_YW=SalesData::min('YW');



        $subQuery = SalesData::where('YW','>=', $max_YW)
        ->where('YW','<=', $max_YW);

        $prev_subQuery = SalesData::where('YW','>=', $max_YW-100)
        ->where('YW','<=', $max_YW-100);

        $query = $subQuery
        ->groupBy('shop_id')
        ->selectRaw('shop_id,shop_name, sum(kingaku) as totalPerPurchase');

        $datas = DB::table($query)
        ->groupBy('shop_id','shop_name')
        ->selectRaw('shop_id ,shop_name as name,sum(totalPerPurchase) as total');
        // ->orderBy('total','desc');
        // ->get();

        $shops = DB::table('shops')->get();
        // 前年同月データを取得

        $query2 = $prev_subQuery
        ->groupBy('shop_id')
        ->selectRaw('shop_id,shop_name, sum(kingaku) as totalPerPurchase');

        $prev_datas = DB::table($query2)
        ->groupBy('shop_id','shop_name')
        ->selectRaw('shop_id ,shop_name as name,sum(totalPerPurchase) as pv_total');
        // ->orderBy('total','desc');
        // ->get();

        $merged_data = DB::table('shops')
        ->where('shops.company_id','>',1000)
        ->leftjoinSub($datas, 'cr_data', 'shops.id', '=', 'cr_data.shop_id')
        ->leftjoinSub($prev_datas, 'pv_data', 'shops.id', '=', 'pv_data.shop_id')
        ->select('shops.id','shops.shop_name as name','cr_data.total','pv_data.pv_total')
        ->orderBy('total','desc')
        ->get();

        // dd($YM,$datas,$prev_datas);
    // 比較データを作成
        $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

        $pv_total = DB::table($query2)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

        // dd($shops,$merged_data);
        // dd($date_table,$datas,$prev_datas,$merged_data);
        // dd($shops,$datas,$prev_datas);
        return view('analysis.sales_total', compact('YMs','max_YM','YWs','max_YW','brands','datas','seasons','units','faces','total','pv_total','merged_data'));

    }

    public function sales_product(Request $request)
    {
        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',1000)
        ->where('id','<',7000)
        ->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
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
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->orderBy('pcs_total','desc')
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.sales_product',
            compact('YMs','max_YM','companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'h'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->where('vendor_id','<>',8200)
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.sales_product',
            compact('YMs','max_YM','companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 's'){

            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->where('vendor_id','<>',8200)
            ->groupBy('sku_id','hinban_id','col_id','size_id')
            ->selectRaw('sku_id, hinban_id ,col_id,size_id,sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('sku_id','hinban_id','hinban_name','m_price','col_id','size_id')
            // ->selectRaw('hinban_id, sku_id as code,hinban_name, price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->selectRaw('sku_id,hinban_id, CONCAT(hinban_id, "-", col_id, "-", size_id) as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->orderBy('pcs_total','desc')
            ->orderBy('total','desc')
            ->get();

            // ->selectRaw("CONCAT(first_name, ' ', last_name) AS full_name")

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.sales_product',
             compact('YMs','max_YM','companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }
    }

    public function sales_product_reset()
    {
        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
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
        ->where('YM','<=', $max_YM);

        $query = $subQuery
        ->groupBy('hinban_id')
        ->selectRaw('hinban_id, sum(kingaku) as totalPerPurchase,hinban_name,m_price,sum(pcs) as subtotal_pcs');
    // dd($query);
        $datas = DB::table($query)
        ->groupBy('hinban_id','hinban_name','m_price')
        ->selectRaw('hinban_id, hinban_id as code,hinban_name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
        ->orderBy('pcs_total','desc')
        ->get();

        $total = DB::table($query)
        ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerPurchase) as total')
        ->first();

        // dd($datas,$total);
        return view('analysis.sales_product',
        compact('YMs','max_YM','companies','shops',
            'brands','datas','total', 'seasons','units','faces'
        ));

    }

    public function stocks_product(Request $request)
    {
        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->where('units.season_id','LIKE','%'.$request->season_code.'%')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $companies = Company::Where('id','>=',106)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',1000)
        ->where('id','<',7000)
        ->orWhere('id','=',106)
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
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('hinban_id')
            ->selectRaw('hinban_id, sum(zaikogaku) as totalPerZaiko,hinban_name,m_price,sum(pcs) as subtotal_pcs');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('hinban_id','hinban_name','m_price')
            ->selectRaw('hinban_id, hinban_id as code,hinban_name as name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->orderBy('pcs_total','desc')
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.stocks_product',
            compact('companies','shops',
            'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'h'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.stocks_product',
            compact('companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 's'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.stocks_product',
            compact('companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'u'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.stocks_product',
            compact('companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
        }

        if($request->type3 == 'f'){
            $subQuery = StockData::where('company_id','>=', 106)
            ->where('company_id','<', 7000);

            $query = $subQuery
            ->where('unit_id','LIKE','%'.$request->unit_id.'%')
            ->where('face','LIKE','%'.$request->face.'%')
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
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
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
            ->first();

            // dd($datas,$total);
            return view('analysis.stocks_product',
            compact('companies','shops',
                'brands','datas','total', 'seasons','units','faces'
            ));
    }
    }
    public function stocks_product_reset()
    {
        $faces=DB::table('hinbans')
        ->whereNotNull('face')
        ->select(['face'])
        ->groupBy(['face'])
        ->orderBy('face','asc')
        ->get();

        $units=DB::table('units')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();

        $companies = Company::Where('id','>=',106)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('id','>=',106)
        ->where('id','<',7000)->get();

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
        ->where('company_id','<', 7000);

        $query = $subQuery
        ->groupBy('hinban_id')
        ->selectRaw('hinban_id, sum(zaikogaku) as totalPerZaiko,hinban_name,m_price,sum(pcs) as subtotal_pcs');
    // dd($query);
        $datas = DB::table($query)
        ->groupBy('hinban_id','hinban_name','m_price')
        ->selectRaw('hinban_id, hinban_id as code,hinban_name as name, m_price,sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
        ->orderBy('pcs_total','desc')
        ->get();

        $total = DB::table($query)
        ->selectRaw('sum(subtotal_pcs) as pcs_total,sum(totalPerZaiko) as total')
        ->first();

        // dd($datas,$total);
        return view('analysis.stocks_product',
        compact('companies','shops',
        'brands','datas','total', 'seasons','units','faces'
        ));
    }

}
