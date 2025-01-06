<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Services\AnalysisService;


class AnalysisController extends Controller
{
    public function index(Request $request)
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
}
