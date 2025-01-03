
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            在庫店舗
        </h2>

        {{-- @foreach ($h_shop_stocks as $stock)
         {{ $stock->hinban_id }}
        @endforeach --}}
        <div class="flex mt-4">
        <div calss="">　SKUコード　：　{{ $skus->sku_id }}</div>
        <button type="button" onclick="location.href='{{ route('product_index') }}'" class="ml-10 mb-2 text-right text-white bg-indigo-400 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-600 rounded ">戻る</button>
        </div>

    </x-slot>





    <div class="py-2 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-1/2 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">エリア</th>
                        <th class="w-4/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">社名</th>
                        <th class="w-4/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">在庫数</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($sku_shop_stocks as $stock)
                    <tr>
                        <td class="w-2/12 text-sm md:px-4 py-1"> {{ $stock->area_name }} </td>
                        <td class="w-4/12 text-sm md:px-4 py-1">{{ $stock->co_name }}</td>
                        <td class="w-4/12 text-sm md:px-4 py-1"> {{ $stock->shop_name }}</td>
                        <td class="w-2/12 text-sm pr-4 md:px-4 py-1 text-right">{{ number_format($stock->pcs )}}</td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{-- {{  $products->appends([
                'year_code'=>\Request::get('year_code'),
                'brand_code'=>\Request::get('brand_code'),
                'season_code'=>\Request::get('season_code'),
                'unit_code'=>\Request::get('unit_code'),
            ])->links()}} --}}
            {{  $sku_shop_stocks->links()}}
        </div>



</x-app-layout>
