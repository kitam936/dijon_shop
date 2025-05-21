<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            POS詳細
        </div>
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="md:flex ml-8 ">
        <div class="md:flex">
            <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            @if($user->shop_id >100)
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('pos_result_index') }}'" >売上Dataリスト</button>
            </div>
            </div>

            @endif
        </div>

        @if($user->shop_id < 1000)
        <div class="flex ml-4 mt-2">
        <div class="ml-2 mb-2 md:mb-0">
            <form method="get" action="{{ route('pos_dl',['id'=>$pos_h->id]) }}">
                <input type="hidden" name="id2" value="{{ $pos_h->id }}">
                <button type="submit" class="w-32 text-center text-sm text-white bg-blue-500 border-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded">
                    CSVダウンロード
                </button>
            </form>
        </div>
        {{-- <div class="ml-2 mb-0 md:mb-0">
            <button type="button" onclick="location.href='{{ route('pos_edit',['order'=>$pos_h->id])}}'" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded ">編集</button>
        </div> --}}
        </div>
        @endif


        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">

                    {{-- <form method="get" action=""  enctype="multipart/form-data"> --}}

                        <div class="-m-2">
                            <div class="p-2 mx-auto">
                                {{-- @foreach ($pos_hs as $pos_h) --}}

                                <div class="p-2 w-full mx-auto">
                                    <div class="md:flex">
                                    <div class="flex">
                                    <div class="relative ml-2">
                                        <label for="pos_id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">POS_ID</label>
                                        <div  id="pos_id" name="pos_id" value="{{$pos_h->id}}" class="h-8 w-20 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->id}}
                                        </div>
                                    </div>
                                    <div class="relative ml-2">
                                        <label for="date" class="ml-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">日付</label>
                                        <div  id="date" name="date" value="{{$pos_h->pos_date}}" class="ml-2 h-8 w-32 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->pos_date}}
                                        </div>
                                    </div>
                                    </div>
                                    <div class="flex">

                                    <div class="relative ml-2">
                                        <label for="sh_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">店名</label>
                                        <div  id="sh_name" name="sh_name" value="{{$pos_h->shop_name}}" class="h-8 text-sm w-48 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->shop_name}}
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                    {{-- <div class="relative">
                                        <label for="status" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Status</label>
                                        <div  id="status" name="status" value="{{$pos_h->status}}" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->status}}
                                        </div>
                                    </div> --}}
                                    <div class="flex">

                                        <div class="relative ml-2">
                                            <label for="total_pcs" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">数計</label>
                                            <div  id="total_pcs" name="total_pcs" value="{{$pos_h->total_pcs}}" class="h-8 text-sm w-28 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->total_pcs}}枚
                                            </div>
                                        </div>
                                        <div class="relative ml-2">
                                            <label for="total_gaku" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">金額計</label>
                                            <div  id="total_gaku" name="total_gaku" value="{{$pos_h->uriage}}" class="h-8 text-sm w-36 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->uriage}}円
                                            </div>
                                        </div>
                                        <div class="relative ml-2">
                                            <label for="user_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">担当者</label>
                                            <div  id="user_name" name="user_name" value="{{$pos_h->name}}" class="h-8 text-sm w-36 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->name}}
                                            </div>
                                        </div>
                                        <div class="relative ml-2">
                                            <label for="status" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Status</label>
                                            <div  id="status" name="status" value="{{$pos_h->status_name}}" class="h-8 text-sm w-36 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->status_name}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative ml-2">
                                        <label for="comment" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">comment</label>
                                        <div  id="comment" rows="5" name="comment" value="{{$pos_h->memo}}" class="h-8 text-sm md:w-1/2 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$pos_h->memo}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <input  type="hidden" id="pos_id2" name="pos_id2" value="{{$pos_h->id}}" class="h-8 w-20 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out"> --}}
                        </input>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="py-0 border">
        <h2>明細</h2>
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-1/2 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-3/15 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                        <th class="w-2/15 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                        <th class="w-4/15 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                        <th class="w-2/15 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col</th>
                        <th class="w-2/15 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size</th>
                        <th class="w-2/15 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">pcs</th>
                        <th class="w-2/15 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">金額</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pos_fs as $pos_f)
                    <tr>
                        <td type="hidden" class="w-3/15 md:1/12 text-sm md:px-4 py-1 text-center"> {{$pos_f->sku_id}} </td>
                        <td class="w-2/12 md:2/15 text-sm md:px-4 py-1 text-center">{{ $pos_f->hinban_id }}</td>
                        <td class="w-4/12 md:2/15 text-sm md:px-4 py-1 text-center">{{ Str::limit($pos_f->hinban_name,15) }}</td>
                        <td class="w-2/12 md:2/15 text-sm md:px-4 py-1 text-center">{{ $pos_f->col_id }}</td>
                        <td class="w-2/12 md:2/15 text-sm md:px-4 py-1 text-center">{{ $pos_f->size_id }}</td>
                        <td class="w-2/12 md:2/15 text-sm md:px-4 py-1 text-center">{{ $pos_f->f_pcs }}</td>
                        <td class="w-2/12 md:2/15 text-sm md:px-4 py-1 text-right">{{ $pos_f->f_uriage }}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $pos_fs->links()}}
            {{-- {{  $pos_fs->appends([
                'co_id'=>\Request::get('co_id'),
                'area_id'=>\Request::get('area_id'),
                'sh_id'=>\Request::get('sh_id'),
                'info'=>\Request::get('info'),
            ])->links()}} --}}
        </div>
    </div>


    <script>
        function deletePost(e) {
        'use strict';
        if (confirm('本当に削除してもいいですか?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
        }
        }
    </script>

</x-app-layout>
