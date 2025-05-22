<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品移動Dataリスト
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="md:flex">
            <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            @if($user->shop_id >1000 || $user->shop_id == 101)
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('move_index') }}'" >返品移動入力状況</button>
            </div>
            </div>
            <div class="flex">
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-32 text-center text-sm text-white bg-green-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('move_scan') }}'" >SCAN開始</button>
            </div>
            @if($user->shop_id == 101 )
            @if($dl_new)
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-32 text-center text-sm text-white bg-blue-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('move_dl_shop') }}'" >移動New一括DL</button>
            </div>
            @else
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2 text-indigo-700">未ダウンロードのデータはありません</div>
            @endif
            @endif
            @endif
            </div>

            @if($user->shop_id == 106)
            @if($dl_new)
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-32 text-center text-sm text-white bg-blue-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('move_dl_dc') }}'" >神田New一括DL</button>
            </div>
            @else
            <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2 text-indigo-700">未ダウンロードのデータはありません</div>
            @endif
            @endif


        </div>
    </x-slot>

    @if($user->shop_id >1000)
    <div class="py-6 bpos">
        <div class=" mx-auto sm:px-4 lg:px-4 bpos ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        {{-- <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店コード</th> --}}
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出荷店</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">着荷店</th>
                        {{-- <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者</th> --}}
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($move_hs as $move_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center"> <a href="{{ route('move_result_show',['id'=>$move_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  > {{ $move_h->id }}</a> </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->move_date }}</td>
                        {{-- <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->shop_id }}</td> --}}
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->shop_name }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->to_shop_name }}</td>
                        {{-- <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->name }}</td> --}}
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-right"> {{ $move_h->pcs }}</td>
                        @if($move_h->status_id == 1)
                        <td class="w-3/14 pl-2 text-pink-500 text-sm md:px-4 py-1 text-center"> {{ $move_h->status_name }}</td>
                        @else
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->status_name }}</td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $move_hs->links() }}
        </div>
    </div>
    {{-- <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
            <analysis-component></analysis-component>

        </div>
    </body> --}}
    @endif

    @if($user->shop_id == 101)
    <div class="py-6 bpos">
        <div class=" mx-auto sm:px-4 lg:px-4 bpos ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        {{-- <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店コード</th> --}}
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出荷店</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">着荷店</th>
                        {{-- <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者</th> --}}
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($move_hs2 as $move_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center"> <a href="{{ route('move_result_show',['id'=>$move_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  > {{ $move_h->id }}</a> </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->move_date }}</td>
                        {{-- <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->shop_id }}</td> --}}
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->shop_name }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->to_shop_name }}</td>
                        {{-- <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->name }}</td> --}}
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-right"> {{ $move_h->pcs }}</td>
                        @if($move_h->status_id == 1)
                        <td class="w-3/14 pl-2 text-pink-500 text-sm md:px-4 py-1 text-center"> {{ $move_h->status_name }}</td>
                        @else
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->status_name }}</td>
                        @endif
                        <td class="w-2/16 md:px-2 py-1">
                            <div>
                            <form method="POST" action="{{ route('move_result_destroy', $move_h->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-12 h-9 mt-1 items-center bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded " onclick="return confirm('削除しますか？')">削除</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $move_hs2->links() }}
        </div>
    </div>
    @endif

    @if($user->shop_id == 106)
    <div class="py-6 bpos">
        <div class=" mx-auto sm:px-4 lg:px-4 bpos ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        {{-- <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店コード</th> --}}
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出荷店</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">着荷店</th>
                        {{-- <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者</th> --}}
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Status</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($move_ks as $move_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center"> <a href="{{ route('move_result_show',['id'=>$move_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  > {{ $move_h->id }}</a> </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->move_date }}</td>
                        {{-- <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->shop_id }}</td> --}}
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->shop_name }}</td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->to_shop_name }}</td>
                        {{-- <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->name }}</td> --}}
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-right"> {{ $move_h->pcs }}</td>
                        @if($move_h->status_id == 1)
                        <td class="w-3/14 pl-2 text-pink-500 text-sm md:px-4 py-1 text-center"> {{ $move_h->status_name }}</td>
                        @else
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $move_h->status_name }}</td>
                        @endif
                        <td class="w-2/16 md:px-2 py-1">
                            <div>
                            <form method="POST" action="{{ route('move_result_destroy', $move_h->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-12 h-9 mt-1 items-center bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded " onclick="return confirm('削除しますか？')">削除</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $move_ks->links() }}
        </div>
    </div>
    {{-- <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
            <analysis-component></analysis-component>

        </div>
    </body> --}}
    @endif

</x-app-layout>
