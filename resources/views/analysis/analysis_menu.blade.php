<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Data分析 Menu
        </h2>
        <div class="md:flex-auto p-1 text-gray-900 dark:text-gray-100  ">
            <div class="flex px-4 py-2 md:w-2/3">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_transition') }}'" >社店売上推移</button>
            <button type="button" class="ml-4 w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_total') }}'" >社店累計売上順</button>

            </div>
        </div>

        <div class="md:flex-auto p-1 text-gray-900 dark:text-gray-100  ">
            <div class="flex px-4 py-2 md:w-2/3">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_product') }}'" >商品別売上順</button>
            <button type="button" class="ml-4 w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('stocks_product') }}'" >在庫</button>

            </div>
        </div>

    </x-slot>

</x-app-layout>
