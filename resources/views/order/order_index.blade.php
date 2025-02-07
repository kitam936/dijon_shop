
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            追加発注リスト
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="md:flex">
            <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            @if($user->shop_id >1000)
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('cart_index') }}'" >カートを見る</button>
            </div>
            </div>
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('cart_create') }}'" >オーダー</button>
            </div>
            @endif
    </div>
    </x-slot>

    @if($user->shop_id >1000)
    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">発注者</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">status</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($order_hs as $order_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center">  {{ $order_h->id }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">
                            <a href="{{ route('order_detail',['order'=>$order_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  >{{\Carbon\Carbon::parse($order_h->order_date)->format("y/m/d")}}
                            </a> </td>
                        <td class="w-2/14 pr-2 text-sm md:px-4 py-1 text-center">  {{ Str::limit($order_h->shop_name,10) }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">  {{ Str::limit($order_h->name,10) }} </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $order_h->pcs }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $order_h->status }}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{-- {{ $order_hs->links() }} --}}
        </div>
    </div>
    {{-- <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
            <analysis-component></analysis-component>

        </div>
    </body> --}}
    @endif

    @if($user->shop_id <1000)
    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id2</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">発注者</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">status</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($all_order_hs as $order_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center">  {{ $order_h->id }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">
                            <a href="{{ route('order_detail',['order'=>$order_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  >{{\Carbon\Carbon::parse($order_h->order_date)->format("y/m/d")}}
                            </a> </td>
                        <td class="w-2/14 pr-2 text-sm md:px-4 py-1 text-center">  {{ Str::limit($order_h->shop_name,10) }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">  {{ Str::limit($order_h->name,10) }} </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $order_h->pcs }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $order_h->status }}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{-- {{ $order_hs->links() }} --}}
        </div>
    </div>
    {{-- <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
            <analysis-component></analysis-component>

        </div>
    </body> --}}
    @endif

</x-app-layout>
