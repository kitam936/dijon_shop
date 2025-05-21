<x-app-layout>
    <h2 class="font-semibold text-xl mt-4 ml-4 mb-4 text-gray-800 leading-tight"> 棚卸入力リスト</h2>

    <x-flash-message status="session('status')" />

    <div class="ml-4 flex mb-4 md:mb-0 md:ml-4">
        <div class="ml-4 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('inventory_result_index') }}'" >棚卸リスト</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-24 text-center text-sm text-white bg-green-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded " onclick="location.href='{{ route('inventory_scan') }}'" >棚卸開始</button>
        </div>
    </div>

    <div class="ml-8 md:ml-2">
    <form method="POST" action="{{ route('inventory_complete') }}" class="mt-2">
        @csrf
        <label>メモ：<input type="text" name="memo" style="width:300px;" class="rounded w-50" ></label>
        <button type="submit" class="mt-4 ml-0 md:ml-4 w-24 text-center text-sm text-white bg-pink-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-pink-700 rounded " onclick="return confirm('棚卸を確定してよろしいですか？')">棚卸確定</button>
    </form>
    </div>

<div class="mt-4">
    <h3 class="flex ml-4 font-semibold text-ml text-indigo-700 leading-tight mt-4">
        明細(新着順5)

        <div class="flex relative ml-20">
            <label for="total_pcs" class="leading-7 text-ml text-indigo-700 ">棚卸数</label>
            @if($works_total)
            <div  id="total_pcs" name="total_pcs" value="{{$works_total->pcs}}" class="h-8 text-ml w-24 text-indigo-700 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$works_total->pcs}}枚
            @else
            <div  id="total_pcs" name="total_pcs" class="h-8 text-ml w-24 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out"> 0 枚
            </div>
            @endif
        </div>
    </h3>



    <div class="mt-0">
        <table class="ml-1 bg-white text-center table-auto whitespace-no-wrap">

        <thead>
            <tr>
                <th class="w-3/16 md:px-0 py-1">CK</th>
                <th class="w-3/16 pr-8 md:px-4 py-1 text-center">読込Data（コード・数）</th>

            </tr>
        </thead>

        <tbody>
            @foreach ($works as $work)
            <tr>

                @if(is_null($work->sku_ck) || is_null($work->hin_ck))
                <td class="w-3/16 md:px-4 py-1 text-red-600 ">NG</td>
                @else
                <td class="w-3/16 md:px-4 py-1 text-green-600">OK</td>
                @endif

                <td class="w-5/16 md:px-2 py-1" >
                    <form method="POST" action="{{ route('inventory_update', $work->id) }}">
                        @csrf
                        <div class="flex ml-1">
                        <input name="raw_cd" value="{{ $work->raw_cd }}" class="rounded mr-2" style="width:160px; font-variant-numeric:tabular-nums">
                        <input type="number" name="pcs" value="{{ $work->pcs }}" class="rounded mr-2" style="width:60px; font-variant-numeric:tabular-nums">
                        <button type="submit" class="w-12 h-9 mt-1 items-right bg-blue-500 text-sm text-white ml-1 hover:bg-blue-600 rounded ">更新</button>
                    </div>
                    </form>
                </td>
                <td class="w-2/16 md:px-2 py-1">
                    <form method="POST" action="{{ route('inventory_destroy', $work->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-12 h-9 mt-1 items-center bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded " onclick="return confirm('削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    </div>



    {{ $works->links()}}
</div>
</x-app-layout>
