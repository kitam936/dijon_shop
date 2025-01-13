<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DataDownloadController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('analysis',[AnalysisController::class,'index'])->name('analysis');

Route::get('/', function () {
    return Inertia::render('hello-world');
});

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
->middleware('can:admin')
->group(function(){
    Route::get('/data/data_menu', [DataController::class, 'menu'])->name('admin.data.data_menu');
    Route::get('/data', [DataController::class, 'create'])->name('admin.data.create');
    Route::get('data/data_index', [DataController::class, 'index'])->name('admin.data.data_index');
    Route::get('data/brand_index', [DataController::class, 'brand_index'])->name('admin.data.brand_index');
    Route::get('data/unit_index', [DataController::class, 'unit_index'])->name('admin.data.unit_index');
    Route::get('data/hinban_index', [DataController::class, 'hinban_index'])->name('admin.data.hinban_index');
    Route::get('data/sku_index', [DataController::class, 'sku_index'])->name('admin.data.sku_index');
    Route::get('data/col_index', [DataController::class, 'col_index'])->name('admin.data.col_index');
    Route::get('data/size_index', [DataController::class, 'size_index'])->name('admin.data.size_index');
    Route::get('data/area_index', [DataController::class, 'area_index'])->name('admin.data.area_index');
    Route::get('data/company_index', [DataController::class, 'company_index'])->name('admin.data.company_index');
    Route::get('data/shop_index', [DataController::class, 'shop_index'])->name('admin.data.shop_index');
    Route::get('data/sales_index', [DataController::class, 'sales_index'])->name('admin.data.sales_index');
    Route::get('data/deliv_index', [DataController::class, 'delivery_index'])->name('admin.data.deliv_index');
    Route::get('data/stock_index', [DataController::class, 'stock_index'])->name('admin.data.stock_index');
    Route::get('data/ym_index', [DataController::class, 'ym_index'])->name('admin.data.ym_index');
    Route::get('data/yw_index', [DataController::class, 'yw_index'])->name('admin.data.yw_index');
    Route::get('data/ymd_index', [DataController::class, 'ymd_index'])->name('admin.data.ymd_index');
    Route::get('data/y_index', [DataController::class, 'y_index'])->name('admin.data.y_index');
    Route::POST('data/stock_upload', [DataController::class, 'stock_upload'])->name('admin.data.stock_upload');
    Route::POST('data/shop_upsert', [DataController::class, 'shop_upsert'])->name('admin.data.shop_upsert');
    Route::POST('data/sales_upload', [DataController::class, 'sales_upload'])->name('admin.data.sales_upload');
    Route::POST('data/deliv_upload', [DataController::class, 'delivery_upload'])->name('admin.data.deliv_upload');
    Route::POST('data/hinban_upsert', [DataController::class, 'hinban_upsert'])->name('admin.data.hinban_upsert');
    Route::POST('data/sku_upsert', [DataController::class, 'sku_upsert'])->name('admin.data.sku_upsert');
    Route::POST('data/ym_upsert', [DataController::class, 'ym_upsert'])->name('admin.data.ym_upsert');
    Route::POST('data/yw_upsert', [DataController::class, 'yw_upsert'])->name('admin.data.yw_upsert');
    Route::POST('data/ymd_upsert', [DataController::class, 'ymd_upsert'])->name('admin.data.ymd_upsert');
    Route::POST('data/y_upsert', [DataController::class, 'y_upsert'])->name('admin.data.y_upsert');
    Route::POST('data/col_upsert', [DataController::class, 'col_upsert'])->name('admin.data.col_upsert');
    Route::POST('data/size_upsert', [DataController::class, 'size_upsert'])->name('admin.data.size_upsert');
    Route::POST('data/company_upsert', [DataController::class, 'company_upsert'])->name('admin.data.company_upsert');
    Route::POST('data/area_upsert', [DataController::class, 'area_upsert'])->name('admin.data.area_upsert');
    Route::POST('data/unit_upsert', [DataController::class, 'unit_upsert'])->name('admin.data.unit_upsert');
    Route::POST('data/brand_upsert', [DataController::class, 'brand_upsert'])->name('admin.data.brand_upsert');
    Route::get('data/shop_edit/{shop}', [DataController::class, 'shop_edit'])->name('admin.data.shop_edit');
    Route::get('report_update/{shop}', [DataController::class, 'shop_update'])->name('admin.data.shop_update');
    Route::post('report_update/{shop}', [DataController::class, 'shop_update'])->name('admin.data.shop_update');
    Route::delete('report_destroy/{shop}', [DataController::class, 'shop_destroy'])->name('admin.data.shop_destroy');
    Route::get('data/delete_index', [DataController::class, 'delete_index'])->name('admin.data.delete_index');
    Route::delete('sales_destroy', [DataController::class, 'sales_destroy'])->name('admin.data.sales_destroy');
    Route::delete('deliv_destroy', [DataController::class, 'deliv_destroy'])->name('admin.data.deliv_destroy');
    Route::delete('stock_destroy', [DataController::class, 'stock_destroy'])->name('admin.data.stock_destroy');
    Route::delete('sku_destroy', [DataController::class, 'sku_destroy'])->name('admin.data.sku_destroy');
    Route::delete('hinban_destroy', [DataController::class, 'hinban_destroy'])->name('admin.data.hinban_destroy');
    Route::delete('shop_destroy', [DataController::class, 'shop_destroy'])->name('admin.data.shop_destroy');
    Route::delete('shop_destroy_all', [DataController::class, 'shop_destroy_all'])->name('admin.data.shop_destroy_all');
    Route::delete('company_destroy', [DataController::class, 'company_destroy'])->name('admin.data.company_destroy');
    Route::delete('area_destroy', [DataController::class, 'area_destroy'])->name('admin.data.area_destroy');
    Route::delete('unit_destroy', [DataController::class, 'unit_destroy'])->name('admin.data.unit_destroy');
    Route::delete('brand_destroy', [DataController::class, 'brand_destroy'])->name('admin.data.brand_destroy');
    Route::delete('col_destroy', [DataController::class, 'col_destroy'])->name('admin.data.col_destroy');
    Route::delete('size_destroy', [DataController::class, 'size_destroy'])->name('admin.data.size_destroy');
    Route::get('user_create', [UserController::class, 'create'])->name('admin.user_create');
    Route::POST('user_store', [UserController::class, 'store'])->name('admin.user_store');
    Route::get('user_edit/{user}', [UserController::class, 'edit'])->name('admin.user_edit');
    Route::delete('user_destroy/{user}', [UserController::class, 'user_destroy'])->name('admin.user_destroy');

});

Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){
    Route::get('role_list', [UserController::class, 'role_list'])->name('role_list');
    Route::get('role_edit/{user}', [UserController::class, 'role_edit'])->name('role_edit');
    Route::get('role_update/{user}', [UserController::class, 'role_update'])->name('role_update');
    Route::post('role_update/{user}', [UserController::class, 'role_update'])->name('role_update');


});

Route::middleware('can:user-higher')
->group(function(){
    Route::get('ac_info', [UserController::class, 'ac_info'])->name('ac_info');
    Route::get('ac_info_edit/{user}', [UserController::class, 'ac_info_edit'])->name('ac_info_edit');
    Route::get('pw_change/{user}', [UserController::class, 'pw_change'])->name('pw_change');
    Route::post('pw_update/{user}', [UserController::class, 'pw_update'])->name('pw_update');
    Route::get('pw_change_admin/{user}', [UserController::class, 'pw_change_admin'])->name('pw_change_admin');
    Route::post('pw_update_admin/{user}', [UserController::class, 'pw_update_admin'])->name('pw_update_admin');
    Route::get('memberlist', [UserController::class, 'memberlist'])->name('memberlist');
    Route::get('member_detail/{user}', [UserController::class, 'show'])->name('member_detail');
    Route::get('member_edit/{user}', [UserController::class, 'edit'])->name('member_edit');
    Route::get('member_update1/{user}', [UserController::class, 'member_update_rs1'])->name('member_update1');
    Route::post('member_update1/{user}', [UserController::class, 'member_update_rs1'])->name('member_update1');
    Route::get('shop_index', [ShopController::class, 'index'])->name('shop_index');
    Route::get('shop_show/{shop}', [ShopController::class, 'show'])->name('shop_show');
    Route::get('product_index', [ProductController::class, 'index'])->name('product_index');
    Route::get('product_show/{hinban}', [ProductController::class, 'show'])->name('product_show');
    Route::get('product_show0/{hinban}', [ProductController::class, 'show0'])->name('product_show0');
    Route::get('sku_stock/{sku}', [ProductController::class, 'sku_zaiko'])->name('sku_stock');
    Route::get('report_list', [ReportController::class, 'report_list'])->name('report_list');
    Route::get('report_detail/{report}', [ReportController::class, 'report_detail'])->name('report_detail');
    Route::get('report_create/{shop}', [ReportController::class, 'report_create'])->name('report_create');
    Route::get('report_create2', [ReportController::class, 'report_create2'])->name('report_create2');
    Route::post('report_store', [ReportController::class, 'report_store_rs'])->name('report_store');
    Route::post('report_store2', [ReportController::class, 'report_store_rs2'])->name('report_store2');
    Route::get('report_edit/{report}', [ReportController::class, 'report_edit'])->name('report_edit');
    Route::post('report_update/{report}', [ReportController::class, 'report_update_rs'])->name('report_update');
    Route::delete('report_destroy/{report}', [ReportController::class, 'report_destroy'])->name('report_destroy');
    Route::get('report_image1/{report}', [ReportController::class, 'image1_show'])->name('report_image1');
    Route::get('report_image2/{report}', [ReportController::class, 'image2_show'])->name('report_image2');
    Route::get('report_image3/{report}', [ReportController::class, 'image3_show'])->name('report_image3');
    Route::get('report_image4/{report}', [ReportController::class, 'image4_show'])->name('report_image4');
    Route::get('comment_detail/{comment}', [CommentController::class, 'comment_detail'])->name('comment_detail');
    Route::get('comment_create/{report}', [CommentController::class, 'comment_create'])->name('comment_create');
    Route::post('comment_store', [CommentController::class, 'comment_store'])->name('comment_store');
    Route::get('comment_edit/{comment}', [CommentController::class, 'comment_edit'])->name('comment_edit');
    Route::post('comment_update/{comment}', [CommentController::class, 'comment_update'])->name('comment_update');
    Route::delete('comment_destroy/{comment}', [CommentController::class, 'comment_destroy'])->name('comment_destroy');
    Route::get('analysis_index', [AnalysisController::class, 'analysis_index'])->name('analysis_index');
    Route::get('sales_transition', [AnalysisController::class, 'sales_transition'])->name('sales_transition');
    Route::get('sales_total', [AnalysisController::class, 'sales_total'])->name('sales_total');
    Route::get('sales_product', [AnalysisController::class, 'sales_product'])->name('sales_product');
    Route::get('stocks_product', [AnalysisController::class, 'stocks_product'])->name('stocks_product');
    Route::get('sales_transition_reset', [AnalysisController::class, 'sales_transition_reset'])->name('sales_transition_reset');
    Route::get('sales_total_reset', [AnalysisController::class, 'sales_total_reset'])->name('sales_total_reset');
    Route::get('sales_product_reset', [AnalysisController::class, 'sales_product_reset'])->name('sales_product_reset');
    Route::get('stocks_product_reset', [AnalysisController::class, 'stocks_product_reset'])->name('stocks_product_reset');
    Route::get('order_index', [OrderController::class, 'order_index'])->name('order_index');
    Route::get('manual_download',[DataDownloadController::class,'manual_download'])->name('manual_download');
});

require __DIR__.'/auth.php';
