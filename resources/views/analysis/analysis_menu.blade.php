<x-app-layout>
    <x-slot name="header">
        {{--  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Menu
        </h2><br>  --}}

        <h3 class="ml-8 font-semibold text-xl text-indigo-800 leading-tight">
            売上・在庫分析
        </h3><br>
        <div class="md:flex ">
        <div class="flex ml-8 p-1 text-gray-900  ">
            <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_transition') }}'" >社店売上推移</button>
            <button type="button" class="ml-2 h-8 w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_total') }}'" >社店累計売上順</button>
        </div>

        <div class="ml-8 md:ml-0 md:flex p-1 text-gray-900  ">
            <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_product') }}'" >商品別売上順</button>
            <button type="button" class="ml-1 md:ml-2 h-8 w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('stocks_product') }}'" >在庫</button>
        </div>
        </div>

        <br><br><br><br>

        <h3 class="ml-8 font-semibold text-xl text-indigo-800 leading-tight">
            各種Data
        </h3><br>
        <div class="md:flex ">
        <div class="flex ml-8 p-1 text-gray-900  ">
            <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('report_list') }}'" >店舗Report</button>
            <button type="button" class="ml-2 h-8 w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('shop_index') }}'" >店舗リスト</button>
        </div>

        <div class="ml-8 md:ml-0 md:flex p-1 text-gray-900  ">
            <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('product_index') }}'" >商品リスト</button>
            <button type="button" class="ml-1 md:ml-2 h-8 w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('memberlist') }}'" >メンバーリスト</button>
        </div>
        </div>

    </x-slot>


</x-app-layout>
