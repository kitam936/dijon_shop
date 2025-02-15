<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesData;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\Company;
use App\Models\Area;
use App\Models\Ym;
use App\Models\Yms;
use App\Models\Ymd;
use App\Models\Yy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\AnalysisService;
use Illuminate\Http\Response;

class MyBudgetController extends Controller
{
    public function budget_progress(Request $request)
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)
        ->where('id','<>',3200)
        ->where('id','<>',400)
        ->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',1000)
        ->where('id','<',7000)
        ->where('company_id','<>',3200)
        ->where('company_id','<>',400)
        ->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
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

        // $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
        // ->where('YM','<=',($request->YM2 ?? $max_YM));

        // $prev_subQuery = SalesData::where('YM','>=',($request->YM1-100 ?? $max_YM-100))
        // ->where('YM','<=',($request->YM2-100 ?? $max_YM-100));

        if($request->type2 == ''){
            // 初回アクセス時には最大月を表示する
            $subQuery = SalesData::where('YM','>=', $max_YM)
            ->where('YM','<=', $max_YM);

            $query = $subQuery
            ->where('shop_id',$logIn_user->shop_id)
            ->groupBy('shop_id','YMD')
            ->selectRaw('shop_id, sum(kingaku) as kingaku,
            YMD as date');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(kingaku) as total');
            // ->orderBy('date','desc')
            // ->get();

            $query2 = DB::table('budgets')
            ->join('shops','shops.id','budgets.shop_id')
            ->where('budgets.YM','<=',$max_YM)
            ->where('budgets.shop_id',$logIn_user->shop_id)
            ->groupBy('budgets.shop_id', 'YMD')
            ->selectRaw('budgets.shop_id, sum(budgets.bg_kingaku) as bg_kingaku, YMD');

            $bg_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YMD')
            ->selectRaw('YMD as bg_date, sum(bg_kingaku) as bg_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('ymds')
            ->where('ymds.YMD','<=',$max_YMD)
            ->leftjoinSub($datas, 'cr_data', 'ymds.YMD', '=', 'cr_data.date')
            ->leftjoinSub($bg_datas, 'bg_data', 'ymds.YMD', '=', 'bg_data.bg_date')
            ->select('ymds.YMD as date','cr_data.total','bg_data.bg_total')
            ->where('cr_data.total','>',0)
            ->orWhere('bg_data.bg_total','>',0)
            ->orderBy('ymds.YMD','desc')
            ->get();


            $total = DB::table($query)
            ->selectRaw('sum(kingaku) as total')
            ->first();

            $bg_total = DB::table($query2)
            ->selectRaw('sum(bg_kingaku) as total')
            ->first();

            // dd($merged_data,$total);
            return view('my_analysis.budget_progress',
             compact('YMs','max_YM','companies','shops','areas',
                'datas','merged_data','total','bg_total'
            ));
        }

        if($request->type2 == 'd'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('shop_id',$logIn_user->shop_id)
            ->groupBy('shop_id','YMD')
            ->selectRaw('shop_id, sum(kingaku) as kingaku,
            YMD as date');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(kingaku) as total');
            // ->orderBy('date','desc')
            // ->get();

            $query2 = DB::table('budgets')
            ->join('shops','shops.id','budgets.shop_id')
            ->where('budgets.YM','<=',$max_YM)
            ->where('budgets.shop_id',$logIn_user->shop_id)
            ->groupBy('budgets.shop_id', 'YMD')
            ->selectRaw('budgets.shop_id, sum(budgets.bg_kingaku) as bg_kingaku, YMD');

            $bg_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YMD')
            ->selectRaw('YMD as bg_date, sum(bg_kingaku) as bg_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('ymds')
            ->where('ymds.YMD','<=',$max_YMD)
            ->leftjoinSub($datas, 'cr_data', 'ymds.YMD', '=', 'cr_data.date')
            ->leftjoinSub($bg_datas, 'bg_data', 'ymds.YMD', '=', 'bg_data.bg_date')
            ->select('ymds.YMD as date','cr_data.total','bg_data.bg_total')
            ->where('cr_data.total','>',0)
            ->orWhere('bg_data.bg_total','>',0)
            ->orderBy('ymds.YMD','desc')
            ->get();


            $total = DB::table($query)
            ->selectRaw('sum(kingaku) as total')
            ->first();

            $bg_total = DB::table($query2)
            ->selectRaw('sum(bg_kingaku) as total')
            ->first();

            // dd($total);
            return view('my_analysis.budget_progress',
             compact('YMs','max_YM','companies','shops','areas',
                'datas','merged_data','total','bg_total'
            ));
        }

        if($request->type2 == 'w'){

            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('shop_id',$logIn_user->shop_id)
            ->groupBy('shop_id','YW')
            ->selectRaw('shop_id, sum(kingaku) as kingaku,
            YW as date');
        // dd($query);
            $datas = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(kingaku) as total');
            // ->orderBy('date','desc')
            // ->get();

            $query2 = DB::table('budgets')
            ->join('shops','shops.id','budgets.shop_id')
            ->where('budgets.YM','<=',$max_YM)
            ->where('budgets.shop_id',$logIn_user->shop_id)
            ->groupBy('budgets.shop_id', 'YW')
            ->selectRaw('budgets.shop_id, sum(budgets.bg_kingaku) as bg_kingaku, YW');

            $bg_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YW')
            ->selectRaw('YW as bg_date, sum(bg_kingaku) as bg_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('yws')
            ->where('yws.YW','<=',$max_YW)
            ->leftjoinSub($datas, 'cr_data', 'yws.YW', '=', 'cr_data.date')
            ->leftjoinSub($bg_datas, 'bg_data', 'yws.YW', '=', 'bg_data.bg_date')
            ->select('yws.YW as date','cr_data.total','bg_data.bg_total')
            ->where('cr_data.total','>',0)
            ->orWhere('bg_data.bg_total','>',0)
            ->orderBy('yws.YW','desc')
            ->get();



            $total = DB::table($query)
            ->selectRaw('sum(kingaku) as total')
            ->first();

            $bg_total = DB::table($query2)
            ->selectRaw('sum(bg_kingaku) as total')
            ->first();

            // dd($total);
            return view('my_analysis.budget_progress',
             compact('YMs','max_YM','companies','shops','areas',
                'datas','merged_data','total','bg_total'
            ));
        }

        if($request->type2 == 'm'){
            $subQuery = SalesData::where('YM','>=',($request->YM1 ?? $max_YM))
            ->where('YM','<=',($request->YM2 ?? $max_YM));

            $query = $subQuery
            ->where('shop_id',$logIn_user->shop_id)
            ->groupBy('shop_id', 'YM')
            ->selectRaw('shop_id, sum(kingaku) as kingaku, YM as date');

            $datas = DB::table($query)
            // $datas = $query
            ->groupBy('date')
            ->selectRaw('date, sum(kingaku) as total');
            // ->orderBy('date', 'desc')
            // ->get();


            $date_table = DB::table('sales')
            ->groupBy('YM')
            ->selectRaw('YM ,YM-100 as prev_date');
            // ->orderBy('YM', 'desc')->get();

            // 前年同月データを取得

            $query2 = DB::table('budgets')
            ->join('shops','shops.id','budgets.shop_id')
            ->where('budgets.YM','<=',$max_YM)
            ->where('budgets.shop_id',$logIn_user->shop_id)
            ->groupBy('budgets.shop_id', 'YM')
            ->selectRaw('budgets.shop_id, sum(budgets.bg_kingaku) as bg_kingaku, YM');

            $bg_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('YM')
            ->selectRaw('YM as bg_date, sum(bg_kingaku) as bg_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('yms')
            ->where('yms.YM','<=',$max_YM)
            ->leftjoinSub($datas, 'cr_data', 'yms.YM', '=', 'cr_data.date')
            ->leftjoinSub($bg_datas, 'bg_data', 'yms.YM', '=', 'bg_data.bg_date')
            ->select('yms.YM as date','cr_data.total','bg_data.bg_total')
            ->where('cr_data.total','>',0)
            ->orWhere('bg_data.bg_total','>',0)
            ->orderBy('yms.YM','desc')
            ->get();


            // dd($merged_data);

            $total = DB::table($query)
                ->selectRaw('sum(kingaku) as total')
                ->first();

            $bg_total = DB::table($query2)
            ->selectRaw('sum(bg_kingaku) as total')
            ->first();

            // dd($datas,$previousYearData);
            // dd($merged_data);
            // dd($date_table,$datas,$prev_datas);
            return view('my_analysis.budget_progress',
             compact('YMs','max_YM','companies','shops','areas',
                'datas','merged_data','total', 'bg_total'
            ));
        }

        if($request->type2 == 'y'){
            $subQuery = SalesData::where('Y','>=', $min_Y)
            ->where('Y','<=',$max_Y);


            // dd($min_Y-100,$prev_subQuery);

            $query = $subQuery
            ->where('shop_id',$logIn_user->shop_id)
            ->groupBy('shop_id', 'Y')
            ->selectRaw('shop_id, sum(kingaku) as kingaku, Y as date');

            $datas = DB::table($query)
            // $datas = $query
            ->groupBy('date')
            ->selectRaw('date, sum(kingaku) as total');
            // ->orderBy('date', 'desc')
            // ->get();


            $date_table = DB::table('sales')
            ->groupBy('Y')
            ->selectRaw('Y ,Y-1 as prev_date');
            // ->orderBy('YM', 'desc')->get();

            // 前年同月データを取得

            $query2 = DB::table('budgets')
            ->join('shops','shops.id','budgets.shop_id')
            ->where('budgets.YM','<=',$max_YM)
            ->where('budgets.shop_id',$logIn_user->shop_id)
            ->groupBy('budgets.shop_id', 'Y')
            ->selectRaw('budgets.shop_id, sum(budgets.bg_kingaku) as bg_kingaku, Y');

            $bg_datas = DB::table($query2)
            // $prev_datas = $query2
            ->groupBy('Y')
            ->selectRaw('Y as bg_date, sum(bg_kingaku) as bg_total');
            // ->orderBy('prev_date', 'desc')
            // ->get();

            $merged_data = DB::table('yys')
            // ->where('yys.Y','<=',$max_YM)
            ->leftjoinSub($datas, 'cr_data', 'yys.Y', '=', 'cr_data.date')
            ->leftjoinSub($bg_datas, 'bg_data', 'yys.Y', '=', 'bg_data.bg_date')
            ->select('yys.Y as date','cr_data.total','bg_data.bg_total')
            ->where('cr_data.total','>',0)
            ->orWhere('bg_data.bg_total','>',0)
            ->orderBy('yys.Y','desc')
            ->get();


            // dd($merged_data);

            $total = DB::table($query)
                ->selectRaw('sum(kingaku) as total')
                ->first();

            $bg_total = DB::table($query2)
            ->selectRaw('sum(bg_kingaku) as total')
            ->first();

            // dd($datas,$previousYearData);
            // dd($merged_data);
            // dd($date_table,$datas,$prev_datas);
            return view('my_analysis.budget_progress',
             compact('YMs','max_YM','companies','shops','areas',
                'datas','merged_data','total','bg_total'
            ));
        }

    }

    public function budget_progress_reset(Request $request)
    {
        $logIn_user = DB::table('users')
        ->where('users.id',Auth::id())->first();

        $companies = Company::Where('id','>',1000)
        ->where('id','<',7000)
        ->where('id','<>',3200)
        ->where('id','<>',400)
        ->get();

        $shops = Shop::Where('company_id','LIKE','%'.$request->co_id.'%')
        ->where('id','>',1000)
        ->where('id','<',7000)
        ->where('company_id','<>',3200)
        ->where('company_id','<>',400)
        ->get();


        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
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

        $query = $subQuery
        ->where('shop_id',$logIn_user->shop_id)
        ->groupBy('shop_id','YMD')
        ->selectRaw('shop_id, sum(kingaku) as kingaku,
        YMD as date');
    // dd($query);
        $datas = DB::table($query)
        ->groupBy('date')
        ->selectRaw('date, sum(kingaku) as total');
        // ->orderBy('date','desc')
        // ->get();

        $query2 = DB::table('budgets')
        ->where('budgets.YM','<=',$max_YM)
        ->where('budgets.shop_id',$logIn_user->shop_id)
        ->groupBy('budgets.shop_id', 'YMD')
        ->selectRaw('budgets.shop_id, sum(budgets.bg_kingaku) as bg_kingaku, YMD');

        $bg_datas = DB::table($query2)
        // $prev_datas = $query2
        ->groupBy('YMD')
        ->selectRaw('YMD as bg_date, sum(bg_kingaku) as bg_total');
        // ->orderBy('prev_date', 'desc')
        // ->get();

        $merged_data = DB::table('ymds')
        ->where('ymds.YMD','<=',$max_YMD)
        ->leftjoinSub($datas, 'cr_data', 'ymds.YMD', '=', 'cr_data.date')
        ->leftjoinSub($bg_datas, 'bg_data', 'ymds.YMD', '=', 'bg_data.bg_date')
        ->select('ymds.YMD as date','cr_data.total','bg_data.bg_total')
        ->where('cr_data.total','>',0)
        ->orWhere('bg_data.bg_total','>',0)
        ->orderBy('ymds.YMD','desc')
        ->get();


        $total = DB::table($query)
        ->selectRaw('sum(kingaku) as total')
        ->first();

        $bg_total = DB::table($query2)
        ->selectRaw('sum(bg_kingaku) as total')
        ->first();

        // dd($total);
        return view('my_analysis.budget_progress',
            compact('YMs','max_YM','companies','shops','areas',
            'datas','merged_data','total','bg_total'
        ));
    }

}
