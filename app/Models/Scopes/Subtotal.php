<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class Subtotal implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $sql=DB::table('sales')
        ->join('shops','sales.shop_id','=','shops.id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('skus','skus.id','=','sales.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->join('brands','brands.id','=','hinbans.brand_id')
        ->join('units','units.id','=','hinbans.unit_id')
        ->join('cols','cols.id','=','skus.col_id')
        ->join('sizes','sizes.id','=','skus.size_id')
        ->select('shops.company_id','companies.co_name','sales.shop_id','shops.shop_name','shops.area_id',
         'sales.YM','sales.YW','sales.YMD','sales.Y','sales.sku_id','skus.hinban_id','hinbans.hinban_name','hinbans.price','hinbans.m_price',
         'hinbans.brand_id','brands.brand_name','sales.pcs','sales.kingaku','hinbans.unit_id','units.season_id','units.season_name','hinbans.face',
         'col_id','col_name','size_id','size_name','hinbans.vendor_id')
        ;

        $builder->fromSub($sql,'salesdata_subtotal');
    }
}
