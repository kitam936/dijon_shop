
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            店別品番売上<br>
        </h2>

        <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >※Brand・店舗・期間を選択してください</span>

        <form method="get" action="{{ route('user.shop.s_search_h_form')}}" class="mt-4">
            <div class="flex mb-2">
                <select class="w-32 h-8 rounded text-sm pt-1 border mb-2 mr-2 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >全ブランド</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->br_name  }}</option>
                    @endforeach
                </select>
                <select class="w-32 h-8 rounded text-sm items-center pt-1" id="sh_id" name="sh_id" type="number" class="border">
                    <option value="0" @if(\Request::get('sh_id') == '0') selected @endif >全店</option>
                    @foreach ($companies as $company)
                        <optgroup  label = "{{ $company->co_name }}" class="text-indigo-700 font-weight:bold">
                            @foreach ($company->shop as $shop )
                            <option  value="{{ $shop->id }}" @if(\Request::get('sh_id') == $shop->id) selected @endif >{{ $shop->shop_name }}</option>
                            @endforeach
                    @endforeach
                </select>

                <div class="ml-2" >
                    <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-0 hover:bg-indigo-600 rounded " onclick="location.href='{{ route('user.shop.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">SHOP一覧</button>
                </div>
            </div>
            <div class="flex">
                {{-- <span class="items-center text-sm mt-2" >期間： 　</span> --}}
                <select class="w-32 h-8 rounded text-sm pt-1" id="YW1" name="YW1" type="number" class="border">
                    <option value="" @if(\Request::get('YW1') == '0') selected @endif >{{ $max_YW }}直近週</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW1') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　～　</span>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YW2" name="YW2" type="number" class="border">
                    <option value="" @if(\Request::get('YW2') == '0') selected @endif >{{ $max_YW }}直近週</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW2') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2" >　</span>

            </div>
            </form>

            @if(\Request::get('sh_id') =='0')

            {{-- <div class="ml-16 py-2 border"> --}}
                <div class=" w-full  sm:px-0 lg:px-0 border mt-4 ml-0">
                    <div class='border bg-gray-100 h-6'>
                        @foreach ($all_stocks as $all_stock)
                        　現在庫　：　{{ number_format($all_stock->pcs) }}枚　　　　{{ number_format(round($all_stock->zaikogaku)/1000) }}千円　
                        @endforeach
                    </div>
                </div>
            {{-- </div> --}}

            @else

                {{-- <div class="ml-16 py-2 border"> --}}
                    <div class=" w-full  sm:px-0 lg:px-0 border mt-4 ml-0">
                    <div class='border bg-gray-100 h-6'>
                        @foreach ($s_stocks as $s_stock)
                        　現在庫　：　{{ number_format($s_stock->pcs) }}枚　　　　{{ number_format(round($s_stock->zaikogaku)/1000) }}千円　
                        @endforeach
                    </div>
                </div>
                {{-- </div> --}}

            @endif
    </x-slot>

    @if(\Request::get('sh_id') =='0')

    <div class="px-2 py-4 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="px-2 md:w-1/2 bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                <tr>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年度</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">UNIT</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                    <th class="w-2/8 pr-10 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売数</th>

                </tr>
                </thead>

                <tbody>
                @foreach ($h_sales_all as $h_sale)

                <tr>
                    <td class="w-2/8 text-sm md:px-4 py-1">{{ $h_sale->year_code }}</td>
                    <td class="w-2/8 text-sm md:px-4 py-1">{{ $h_sale->unit_id }}</td>
                    <td class="w-2/8 text-sm md:px-4 py-1">{{ $h_sale->hinban_id }}</td>
                    <td class="w-2/8 text-xs pl-4 md:px-4 py-1 text-left">{{ $h_sale->hinmei }}</td>
                    <td class="w-2/8 text-sm pr-10 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format($h_sale->pcs)}}</span></td>

                </tr>
                @endforeach

                </tbody>
            </table>
            {{  $h_sales_all->appends([
                'brand_code'=>\Request::get('brand_code'),
                'sh_id'=>\Request::get('sh_id'),
                'YW1'=>\Request::get('YW1'),
                'YW2'=>\Request::get('YW2'),
            ])->links()}}
        </div>

    @else

    <div class="px-2 py-4 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="px-2 md:w-1/2 bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                <tr>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年度</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">UNIT</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                    <th class="w-2/8 pr-10 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売数</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($h_sales as $h_sale)

                <tr>
                    <td class="w-2/8 text-sm md:px-4 py-1">{{ $h_sale->year_code }}</td>
                    <td class="w-2/8 text-sm md:px-4 py-1">{{ $h_sale->unit_id }}</td>
                    <td class="w-2/8 text-sm md:px-4 py-1">{{ $h_sale->hinban_id }}</td>
                    <td class="w-2/8 text-xs pl-4 md:px-4 py-1 text-left">{{ $h_sale->hinmei }}</td>
                    <td class="w-2/8 text-sm pr-10 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format($h_sale->pcs)}}</span></td>

                </tr>
                @endforeach

                </tbody>
            </table>
            {{  $h_sales->appends([
                'brand_code'=>\Request::get('brand_code'),
                'sh_id'=>\Request::get('sh_id'),
                'YW1'=>\Request::get('YW1'),
                'YW2'=>\Request::get('YW2'),
            ])->links()}}
        </div>

        @endif

        <script>
            const shop = document.getElementById('sh_id')
            shop.addEventListener('change', function(){
            this.form.submit()
            })

            const YW1 = document.getElementById('YW1')
            YW1.addEventListener('change', function(){
            this.form.submit()
            })

            const YW2 = document.getElementById('YW2')
            YW2.addEventListener('change', function(){
            this.form.submit()
            })

            const brand = document.getElementById('brand_code')
            brand.addEventListener('change', function(){
            this.form.submit()
            })


        </script>

</x-app-layout>
