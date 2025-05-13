<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            棚卸リスト
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="md:flex">
            <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            @if($user->shop_id >1000)
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('inventory_index') }}'" >棚卸入力状況</button>
            </div>
            </div>
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-32 text-center text-sm text-white bg-green-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('inventory_scan') }}'" >棚卸開始</button>
            </div>
            @endif
        </div>
    </x-slot>

    @if($user->shop_id >1000)
    <div class="py-6 binventory">
        <div class=" mx-auto sm:px-4 lg:px-4 binventory ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店コード</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($inventory_hs as $inventory_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center"> <a href="{{ route('inventory_result_show',['id'=>$inventory_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  > {{ $inventory_h->id }}</a> </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->inventory_date }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->shop_id }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->shop_name }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->pcs }}</td>
                        @if($inventory_h->status_id == 1)
                        <td class="w-3/14 pl-2 text-pink-500 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->status_name }}</td>
                        @else
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->status_name }}</td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $inventory_hs->links() }}
        </div>
    </div>
    {{-- <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
            <analysis-component></analysis-component>

        </div>
    </body> --}}
    @endif

    @if($user->shop_id <200)
    <div class="py-6 binventory">
        <div class=" mx-auto sm:px-4 lg:px-4 binventory ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店コード</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($inventory_hs2 as $inventory_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center"> <a href="{{ route('inventory_result_show',['id'=>$inventory_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  > {{ $inventory_h->id }}</a> </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->inventory_date }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->shop_id }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->shop_name }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->pcs }}</td>
                        @if($inventory_h->status_id == 1)
                        <td class="w-3/14 pl-2 text-pink-500 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->status_name }}</td>
                        @else
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $inventory_h->status_name }}</td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $inventory_hs2->links() }}
        </div>
    </div>
    @endif

</x-app-layout>
