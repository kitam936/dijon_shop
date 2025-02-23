<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use \SplFileObject;
use Throwable;
use App\Models\Stock;
use App\Models\Budget;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\Company;
use App\Models\Area;
use App\Models\Brand;
use App\Models\Col;
use App\Models\Hinban;
use App\Models\Size;
use App\Models\Sku;
use App\Models\Unit;
use App\Models\Ym;
use App\Models\Yw;
use App\Models\Ymd;
use App\Models\Yy;

class DataController extends Controller
{

    public function menu()
    {
        return view('data.data_menu');
    }
    public function index()
    {
        return view('data.data_index');
    }


    public function create()
    {
        return view('data.data_create');
    }



    public function unit_index(Request $request)
    {
        $units=Unit::All();

        return view('data.unit_data',compact('units'));
    }

    public function brand_index(Request $request)
    {
        $brands=Brand::All();

        return view('data.brand_data',compact('brands'));
    }

    public function sku_index(Request $request)
    {
        $skus=Sku::All();

        return view('data.sku_data',compact('skus'));
    }

    public function col_index(Request $request)
    {
        $cols=Col::All();

        return view('data.col_data',compact('cols'));
    }

    public function size_index(Request $request)
    {
        $sizes=Size::All();

        return view('data.size_data',compact('sizes'));
    }

    public function Ym_index(Request $request)
    {
        $yms=Ym::All();

        return view('data.Ym_data',compact('yms'));
    }

    public function Yw_index(Request $request)
    {
        $yws=Yw::All();

        return view('data.Yw_data',compact('yws'));
    }

    public function Ymd_index(Request $request)
    {
        $ymds=Ymd::All();

        return view('data.Ymd_data',compact('ymds'));
    }

    public function y_index(Request $request)
    {
        $ys=Yy::All();

        return view('data.y_data',compact('ys'));
    }

    public function hinban_index(Request $request)
    {
        $products=Hinban::with('unit')
        ->where('year_code','LIKE','%'.$request->year_code.'%')
        ->where('brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('unit_id','LIKE','%'.$request->unit_code.'%')
        ->paginate(100);
        $years=DB::table('hinbans')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
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
        return view('data.hinban_data',compact('products','years','units','brands'));
    }

    public function area_index(Request $request)
    {
        $areas=Area::All();

        return view('data.area_data',compact('areas'));
    }

    public function company_index(Request $request)
    {
        $companies=Company::All();

        return view('data.company_data',compact('companies'));
    }

    public function shop_index(Request $request)
    {
        $shops=Shop::with('area')->with('company')
        ->where('company_id','LIKE','%'.$request->co_id.'%')->paginate(15);
        $companies = Company::where('id','LIKE','%'.$request->co_id.'%')->select('id','co_name')->get();

        return view('data.shop_data',compact('shops','companies'));
    }

    public function sales_index()
    {
        $sales=Sale::with('shop.company')
        ->orderby('sales_date','desc')->paginate(15);
        return view('data.sales_data',compact('sales'));
    }



    public function stock_index()
    {
        $stocks=Stock::with('shop.company')->paginate(15);
        return view('data.stock_data',compact('stocks'));
    }

    public function yosan_index()
    {
        $budgets=DB::table('budgets')
        ->paginate(100);
        return view('data.yosan_data',compact('budgets'));
    }

    public function delete_index()
    {
        $YMs=DB::table('sales')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();
        $max_YM=Sale::max('YM');
        $max_YW=Sale::max('YW');

        $years=DB::table('hinbans')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','asc')
        ->get();

        $min_year=Hinban::min('year_code');

        $bg_YMs=DB::table('budgets')
        ->select(['YM'])
        ->groupBy(['YM'])
        ->orderBy('YM','desc')
        ->get();
        $bg_max_YM=Budget::max('YM');
        $bg_max_YW=Budget::max('YW');

        return view('data.delete_index',compact('max_YM','max_YW','YMs','years','min_year','bg_YMs','bg_max_YM','bg_max_YW'));


    }

    public function shop_edit($id)
    {
        $shop=Shop::findOrFail($id);
        return view('data.shop_edit',compact('shop'));
    }


    public function shop_destroy($id)
    {
        // dd('delete');
        Shop::findOrFail($id)->delete();

        return to_route('admin.data.shop_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function shop_destroy_ALL(Request $request)
    {
        $Stocks=Shop::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }


    public function sales_destroy(Request $request)
    {
        DB::table('sales')
        ->where('sales.YM','>=',($request->YM1))
        ->where('sales.YM','<=',($request->YM2))
        ->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }



    public function stock_destroy(Request $request)
    {
        $Stocks=Stock::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function sku_destroy(Request $request)
    {
        $Stocks=Sku::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function hinban_destroy(Request $request)
    {
        // $Stocks=Hinban::query()->delete();
        DB::table('hinbans')
        ->where('hinbans.year_code','>=',($request->year1))
        ->where('hinbans.year_code','<=',($request->year2))
        ->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function company_destroy(Request $request)
    {
        $Stocks=Company::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function area_destroy(Request $request)
    {
        $Stocks=Area::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function unit_destroy(Request $request)
    {
        $Stocks=Unit::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function brand_destroy(Request $request)
    {
        $Stocks=Brand::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function col_destroy(Request $request)
    {
        $Stocks=Col::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function size_destroy(Request $request)
    {
        $Stocks=Size::query()->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function stock_upload(Request $request)
    {
        Stock::query()->delete();
        // タイムアウト対応？
        ini_set('max_execution_time',180);

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('stock_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;

				//配列に格納
				// $data_arr[$i]['id'] = $line[0];
				$data_arr[$i]['shop_id'] = $line[1];
				$data_arr[$i]['sku_id'] = $line[2];
				$data_arr[$i]['pcs'] = $line[3];
                $data_arr[$i]['zaikogaku'] = $line[4];
				$data_arr[$i]['created_at'] = $line[5];
				$data_arr[$i]['updated_at'] = $line[6];

                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('stocks')->insert($chunk);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }


    public function sales_upload(Request $request)
    {
        // タイムアウト対応？
        ini_set('max_execution_time',180);

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('sales_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;

				//配列に格納
				// $data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['sales_date'] = $line[1];
				$data_arr[$i]['shop_id'] = $line[2];
				$data_arr[$i]['sku_id'] = $line[3];
				$data_arr[$i]['pcs'] = $line[4];
                $data_arr[$i]['tanka'] = $line[5];
                $data_arr[$i]['kingaku'] = $line[6];
                $data_arr[$i]['Ym'] = $line[7];
                $data_arr[$i]['Yw'] = $line[8];
                $data_arr[$i]['Ymd'] = $line[9];
                $data_arr[$i]['Y'] = $line[10];
				$data_arr[$i]['created_at'] = $line[11];
				$data_arr[$i]['updated_at'] = $line[12];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('sales')->insert($chunk);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function yosan_upload(Request $request)
    {

        // タイムアウト対応？
        ini_set('max_execution_time',180);

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('yosan_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;

				//配列に格納
				// $data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['bg_date'] = $line[1];
				$data_arr[$i]['shop_id'] = $line[2];
				$data_arr[$i]['bg_kingaku'] = $line[3];
				$data_arr[$i]['YM'] = $line[4];
                $data_arr[$i]['YW'] = $line[5];
                $data_arr[$i]['YMD'] = $line[6];
                $data_arr[$i]['Y'] = $line[7];
				$data_arr[$i]['created_at'] = $line[8];
				$data_arr[$i]['updated_at'] = $line[9];

                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('budgets')->insert($chunk);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function yosan_destroy(Request $request)
    {
        DB::table('budgets')
        ->where('budgets.YM','>=',($request->YM1))
        ->where('budgets.YM','<=',($request->YM2))
        ->delete();

        return to_route('admin.data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }


    public function hinban_upsert(Request $request)
    {
        // タイムアウト対応？
        ini_set('max_execution_time',180);

        set_time_limit(150);
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('hinban_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;

				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['brand_id'] = $line[1];
				$data_arr[$i]['unit_id'] = $line[2];
				$data_arr[$i]['year_code'] = $line[3];
				$data_arr[$i]['shohin_gun'] = $line[4];
                $data_arr[$i]['hinban_name'] = $line[5];
                $data_arr[$i]['m_price'] = $line[6];
                $data_arr[$i]['price'] = $line[7];
                $data_arr[$i]['cost'] = $line[8];
                $data_arr[$i]['hinban_info'] = $line[9];
                $data_arr[$i]['vendor_id'] = $line[10];
                $data_arr[$i]['face'] = $line[11];
				$data_arr[$i]['created_at'] = $line[12];
				$data_arr[$i]['updated_at'] = $line[13];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('hinbans')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function shop_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('shop_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['company_id'] = $line[1];
				$data_arr[$i]['area_id'] = $line[2];
				$data_arr[$i]['shop_name'] = $line[3];
				$data_arr[$i]['shop_info'] = $line[4];
                $data_arr[$i]['is_selling'] = $line[5];
                $data_arr[$i]['rate'] = $line[6];
				$data_arr[$i]['created_at'] = $line[7];
				$data_arr[$i]['updated_at'] = $line[8];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('shops')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function company_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('co_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
				$data_arr[$i]['co_name'] = $line[1];
				$data_arr[$i]['co_info'] = $line[2];
				$data_arr[$i]['created_at'] = $line[3];
				$data_arr[$i]['updated_at'] = $line[4];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('companies')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function area_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('ar_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
				$data_arr[$i]['area_name'] = $line[1];
                $data_arr[$i]['area_info'] = $line[2];
				$data_arr[$i]['created_at'] = $line[3];
				$data_arr[$i]['updated_at'] = $line[4];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('areas')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function unit_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('unit_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['unit_code'] = $line[1];
                $data_arr[$i]['season_id'] = $line[2];
				$data_arr[$i]['season_name'] = $line[3];
				$data_arr[$i]['created_at'] = $line[4];
				$data_arr[$i]['updated_at'] = $line[5];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('units')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function brand_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('brand_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['brand_name'] = $line[1];
				$data_arr[$i]['brand_info'] = $line[2];
				$data_arr[$i]['created_at'] = $line[3];
				$data_arr[$i]['updated_at'] = $line[4];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('brands')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function sku_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('sku_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['hinban_id'] = $line[1];
				$data_arr[$i]['col_id'] = $line[2];
                $data_arr[$i]['sku_image'] = $line[4];
				$data_arr[$i]['created_at'] = $line[5];
				$data_arr[$i]['updated_at'] = $line[6];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('skus')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function col_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('col_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['col_name'] = $line[1];
				$data_arr[$i]['created_at'] = $line[2];
				$data_arr[$i]['updated_at'] = $line[3];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('cols')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function size_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('size_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['size_name'] = $line[1];
				$data_arr[$i]['created_at'] = $line[2];
				$data_arr[$i]['updated_at'] = $line[3];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('sizes')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function Ym_upsert(Request $request)
    {
        Ym::query()->delete();

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('ym_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['YM'] = $line[0];
                $data_arr[$i]['prev_YM'] = $line[1];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('yms')->upsert($chunk,['YM']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function Yw_upsert(Request $request)
    {
        Yw::query()->delete();

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('yw_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['YW'] = $line[0];
                $data_arr[$i]['prev_YW'] = $line[1];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('yws')->upsert($chunk,['YW']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function Ymd_upsert(Request $request)
    {

        Ymd::query()->delete();

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('ymd_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['YMD'] = $line[0];
                $data_arr[$i]['prev_YMD'] = $line[1];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('ymds')->upsert($chunk,['YMD']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function Y_upsert(Request $request)
    {
        Yw::query()->delete();

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('y_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['Y'] = $line[0];
                $data_arr[$i]['prev_Y'] = $line[1];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('yys')->upsert($chunk,['Y']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('admin.data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }


}
