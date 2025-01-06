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
        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
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

        $max_YWD=SalesData::max('YMD');
        $max_YW=SalesData::max('YW');
        $max_YM=SalesData::max('YM');
        $min_YWD=SalesData::min('YMD');
        $min_YW=SalesData::min('YW');
        $min_YM=SalesData::min('YM');

        $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
        ->where('YM','<=',($request->YM2 ?? $max_YM));

        $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
        ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

        if($request->type2 == ''){
            $query = $subQuery
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
                ->selectRaw('date, sum(totalPerPurchase) as total')
                ->orderBy('date','desc')
                ->get();

            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($total);
            return view('analysis.sales_transition',compact('YMs','max_YM','companies','shops','areas','brands','datas','total','seasons'));
        }


        if($request->type2 == 'm2'){
            $query = $subQuery
            ->where('shop_id','LIKE','%'.$request->sh_id.'%')
            ->where('company_id','LIKE','%'.$request->co_id.'%')
            ->where('brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('season_id','LIKE','%'.($request->season_code).'%')
            ->groupBy('shop_id','YM')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase,
            YM as date');

            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->orderBy('date','desc')
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($total);
            return view('analysis.sales_transition',compact('YMs','max_YM','companies','shops','areas','brands','datas','total','seasons'));
        }

        if($request->type2 == 'd'){
            $query = $subQuery
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
                ->selectRaw('date, sum(totalPerPurchase) as total')
                ->orderBy('date','desc')
                ->get();

            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

                // dd($total);
            return view('analysis.sales_transition',compact('YMs','max_YM','companies','shops','areas','brands','datas','total','seasons'));
        }

        if($request->type2 == 'w'){
            $query = $subQuery
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
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->orderBy('date','desc')
            ->get();

            $total = DB::table($query)
            ->selectRaw('sum(totalPerPurchase) as total')
            ->first();

            // dd($total);
            return view('analysis.sales_transition',compact('YMs','max_YM','companies','shops','areas','brands','datas','total','seasons'));
        }

        if($request->type2 == 'm'){
            $query = $subQuery
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YM')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YM as date');

            $datas = DB::table($query)
            // $datas = $query
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->orderBy('date', 'desc')
            ->get();


            $date_table = DB::table('sales')
            ->groupBy('YM')
            ->selectRaw('YM as date,YM-100 as prev_date')->get();

            // 前年同月データを取得

            $query2 = $prev_subQuery
            ->where('shop_id', 'LIKE', '%' . $request->sh_id . '%')
            ->where('company_id', 'LIKE', '%' . $request->co_id . '%')
            ->where('brand_id', 'LIKE', '%' . ($request->brand_code) . '%')
            ->where('season_id', 'LIKE', '%' . ($request->season_code) . '%')
            ->groupBy('shop_id', 'YM')
            ->selectRaw('shop_id, sum(kingaku) as totalPerPurchase, YM as date');

            $prev_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->orderBy('date', 'desc')
            ->get();

            // $merged_data = DB::query()->fromSub($date_table, 'date_table')
            // ->leftjoinSub($datas, 'cr_data', function($join) { $join->on('date_table.date', '=', 'cr_data.date'); })
            // ->leftjoinSub($prev_datas, 'pv_data', function($join) { $join->on('date_table.date', '=', 'pv_data.date'); })
            // ->get();

            // dd($YM,$datas,$prev_datas);
        // 比較データを作成
            $total = DB::table($query)
                ->selectRaw('sum(totalPerPurchase) as total')
                ->first();

            // dd($datas,$previousYearData);
            // dd($date_table,$datas,$prev_datas,$merged_data);
            // dd($date_table,$datas,$prev_datas);
            return view('analysis.sales_transition',
             compact('YMs','max_YM','companies','shops','areas',
                'brands','datas',// 'merged_data',
                'total', 'seasons'
            ));
        }

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

        // $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
        // ->where('YM','<=',($request->YM2 ?? $max_YM));

        $subQuery = SalesData::where('YW','>=',($request->YW1 ?? $max_YW))
        ->where('YW','<=',($request->YW2 ?? $max_YW));

        // $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
        // ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

        $prev_subQuery = SalesData::where('YW','>=',($request->YW1-100 ?? $max_YW-100))
        ->where('YW','<=',($request->YW2-100 ?? $max_YW-100));



        if($request->type1 == ''){
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

}
