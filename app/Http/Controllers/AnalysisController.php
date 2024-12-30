<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Services\AnalysisService;
use Illuminate\Http\Response;

class AnalysisController extends Controller
{
    public function index()
    {
        $startDate = '2023-10-01';
        $endDate = '2024-12-31';

        $subQuery = Order::betweenDate($startDate,$endDate);

        $query = $subQuery->where('status', true)
        ->groupBy('id')
        ->selectRaw('id, sum(subtotal) as totalPerPurchase,
        DATE_FORMAT(created_at, "%Y%m") as date');

        $data = collect(DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get());

        // 現在のデータをキー付きで取得
        $currentYearData = $data->keyBy('date');

        // 前年のデータを構築
        $previousYearData = $data->reduce(function ($carry, $item) {
            $currentDate = $item->date; // 例: '202108'
            $year = intval(substr($currentDate, 0, 4));
            $month = intval(substr($currentDate, 4, 2));
            $previousYearDate = sprintf('%04d%02d', $year - 1, $month); // '202008'
            $carry[$previousYearDate] = $item->total;
            return $carry;
        }, []);


        // ラベルを作成
        $labels = $currentYearData->keys();

        // 現在の売上データ
        $currentTotals = $labels->map(fn($label) => $currentYearData->get($label)->total ?? 0);

        // 前年の売上データ（ラベルに基づく）
        $previousTotals = $labels->map(function ($label) use ($previousYearData) {
            // 前年のキーを計算
            $year = intval(substr($label, 0, 4));
            $month = intval(substr($label, 4, 2));
            $previousYearLabel = sprintf('%04d%02d', $year -2, $month);

        // 前年のデータを取得
            return $previousYearData[$previousYearLabel] ?? 0;
        });


        // dd($data,$labels,$currentYearData, $previousYearData,$currentTotals, $previousTotals);

        // return [$labels, $currentTotals, $previousTotals];




        return Inertia::render('Analysis');


    }

    public function index2()
    {
        $startDate = '2022-08-01';
        $endDate = '2023-08-31';

        $subQuery = Order::betweenDate($startDate,$endDate);

        // $query=  $subQuery->where('status',true)
        // ->groupBy('id')
        // ->selectRaw('id,sum(subtotal) as totalPerPurchase,
        // DATE_FORMAT(created_at, "%Y%m") as date ,DATE_FORMAT(DATE_SUB(created_at, INTERVAL 1 YEAR), "%Y%m") as previous_date');

        $query = $subQuery->where('status', true)
        ->groupBy('id')
        ->selectRaw('sum(subtotal) as totalPerPurchase, DATE_FORMAT(created_at, "%Y%m") as date');


        $max_date=  DB::table($query)
        ->groupBy('date')
        ->selectRaw('date, max(date) as max_date')->get();


        // dd($subQuery,$query);
        // 現在の月別データ
        // $data = DB::table($query)
        //     ->groupBy('date')
        //     ->selectRaw('date,sum(totalPerPurchase) as total')->get();

        $data = DB::table($query, 'currentYearData') // サブクエリにエイリアスを設定
        ->groupBy('date')
        ->selectRaw('date, sum(totalPerPurchase) as total')
        ->get();

        // 前年同月のデータを取得
        $previousYearData = DB::table($query)
            // ->where('date',$max_date)
            ->groupBy('date')
            ->selectRaw('DATE_FORMAT(DATE_SUB(STR_TO_DATE(date, "%Y%m"), INTERVAL 1 YEAR), "%Y%m") as date, sum(totalPerPurchase) as previous_total')
            // ->selectRaw('date,
            //           sum(totalPerPurchase) as previous_total')
            ->get();

        $lastYearQuery = $subQuery->where('status', true)
        ->whereBetween('created_at', [
            now()->subYear()->startOfYear(),
            now()->subYear()->endOfYear()
        ])
        ->groupBy('id')
        ->selectRaw('sum(subtotal) as totalPerPurchase, DATE_FORMAT(DATE_SUB(created_at, INTERVAL 1 YEAR), "%Y%m") as date');


        // $lastYearData = DB::table($lastYearQuery, 'lastYearData') // サブクエリにエイリアスを設定
        // ->groupBy('date')
        // ->selectRaw('date, sum(totalPerPurchase) as total')
        // ->get();

        dd($max_date,$data,$previousYearData,$previousYearData);


        return Inertia::render('Analysis');


    }


}
