<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class Stock_subtotal implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $sql=DB::table('stocks')
        ->join('shops','stocks.shop_id','=','shops.id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('skus','skus.id','=','stocks.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->select('shops.company_id','companies.co_name','stocks.shop_id','shops.shop_name',
         'stocks.sku_id','skus.hinban_id','hinbans.hinban_name','stocks.pcs','stocks.zaikogaku')
        ;

        $builder->fromSub($sql,'stocksdata_subtotal');
    }
}
