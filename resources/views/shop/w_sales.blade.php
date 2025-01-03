
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            店別週別売上2<br>
        </h2>

        <div class="mt-4 md:flex">
            @foreach ($shops as $shop)
            <div class="flex">
            <div class="flex pl-0 mt-0">

                <div class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border" name="co_id"  value="">{{ $shop->co_name }}</div>
            </div>
            <div class="flex pl-2 mt-0">

                <div class="pl-0 w-32 h-6 items-center bg-gray-100 border" name="ar_id" value="">{{ $shop->shop_name }}</div>
            </div>
            <div>
                <button type="button" class="w-20 h-6 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('user.shop.show',['shop'=>$shop->id]) }}'" >戻る</button>
            </div>
            </div>


            @endforeach
        </div>

        <form method="get" action="{{ route('user.shop.s_w_form',['shop'=>$shop->id])}}" class="mt-4 ml-0">
            <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >※Brandを選択してください</span>
            <div class="flex ml-0 md:ml-2 mb-2">

                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-2 md:mr-0" id="brand_code" name="brand_code" type="number" >
                <option value="" @if(\Request::get('brand_code') == '0') selected @endif >全ブランド</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->br_name  }}</option>
                @endforeach
                </select>

            </div>
        </form>

        @if(\Request::get('sh_id') =='0')

        {{-- <div class="ml-16 py-2 border"> --}}
            <div class="w-full m-0 sm:px-0 lg:px-0 border mt-0 md:ml-2">
                <div class='border bg-gray-100 h-6'>
                    @foreach ($all_stocks as $all_stock)
                    　現在庫　：　{{ number_format($all_stock->pcs) }}枚　　　　{{ number_format(round($all_stock->zaikogaku)/1000) }}千円　
                    @endforeach
                </div>
            </div>
        {{-- </div> --}}

        @else

            {{-- <div class="ml-16 py-2 border"> --}}
                <div class="w-full m-0 sm:px-0 lg:px-0 border mt-0 md:ml-2">
                <div class='border bg-gray-100 h-6'>
                    @foreach ($s_stocks as $s_stock)
                    　現在庫　：　{{ number_format($s_stock->pcs) }}枚　　　　{{ number_format(round($s_stock->zaikogaku)/1000) }}千円　
                    @endforeach
                </div>
            </div>
            {{-- </div> --}}

        @endif

    </x-slot>

    {{-- @if(\Request::get('sh=id') =='0')

    <div class="py-6 border">
        <div class=" w-1/2  sm:px-4 lg:px-4 border">
            <table class="table-auto bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年月</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年週</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">週売上</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($w_sales_all as $w_sale)
                <tr>
                    <td class="w-1/4 md:px-4 py-1">{{ $w_sale->YM }}</td>
                    <td class="w-1/4 md:px-4 py-1">{{ $w_sale->YW }}</td>
                    <td class="w-1/4 md:px-4 py-1 text-right">{{ number_format($w_sale->kingaku)}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else --}}

        <div class="py-6 border">
        <div class=" w-full  sm:px-4 lg:px-4 border">
            <table class="md:w-1/2 table-auto bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年月</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年週</th>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">週売上(千円)</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($w_sales as $w_sale)
                <tr>
                    <td class="w-1/4 md:px-4 py-1">{{ $w_sale->YM }}</td>
                    <td class="w-1/4 md:px-4 py-1">{{ $w_sale->YW }}</td>
                    <td class="w-1/4 pr-10 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($w_sale->kingaku)/1000)}}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    {{-- @endif --}}

    <script>

        const brand = document.getElementById('brand_code')
        brand.addEventListener('change', function(){
        this.form.submit()
        })

        const shop = document.getElementById('sh_id')
        shop.addEventListener('change', function(){
        this.form.submit()
        })


    </script>

</x-app-layout>



