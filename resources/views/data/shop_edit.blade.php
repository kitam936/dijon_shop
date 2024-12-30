<x-app-layout>
    <x-slot name="header">
        <div >
            <h2 class="mb-4 font-semibold text-xl text-gray-800 leading-tight">
            <div>
                店舗Data編集
            </div>
            </h2>
            <div class="flex">
            <div class="ml-20">
                <button type="button" onclick="location.href='{{ route('admin.data.shop_index') }}'" class="text-white bg-indigo-400 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-ml">店舗データ一覧</button>
            </div>

            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:w-2/3 px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-2 py-2 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('admin.data.shop_update',['shop'=>$shop->id])}}" >
                    @csrf
                    <div class="-m-2">
                        <div class="px-2 py-1 mx-auto">
                            <div class="relative flex">
                              <label for="sh_id" class="p-2 w-28 leading-7 text-sm text-gray-600">店舗コード</label>
                              <div id="sh_id" name="sh_id" value="{{ $shop->id }}" required class="w-20 ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $shop->id }}</div>
                              <label for="sh_name" class="p-2 ml-3 w-16 leading-7 text-sm text-gray-600">店名</label>
                              <input type="text" id="sh_name" name="sh_name" value="{{ $shop->shop_name }}" required class="w-50 ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>

                        <div class="px-2 py-1 mx-auto">
                            <div class="flex">
                            <div class="relative px-4">
                                <label for="co_id" class="leading-7 text-sm text-gray-600">社コード</label>
                                <input type="text" id="co_id" name="co_id" value="{{ $shop->company_id }}" required class="w-40 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                            <div class="relative px-4">
                                <label for="ar_id" class="leading-7 text-sm text-gray-600">エリアコード</label>
                                <input type="text" id="ar_id" name="ar_id" value="{{ $shop->area_id }}" required class="w-40 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                            </div>
                            <div class="relative">
                              <label for="sh_info" class="leading-7 text-sm text-gray-600">info</label>
                              <textarea id="sh_info" name="sh_info" rows="8" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $shop->shop_info }}</textarea>
                            </div>
                        </div>
                        <div class="p-2 w-1/2 mx-auto">
                            <div class="relative flex justify-around">
                              <div><input type="radio" name="is_selling" class="mr-4" value="1" @if($shop->is_selling === 1){ checked } @endif>販売中</div>
                              <div><input type="radio" name="is_selling" class="mr-4" value="0" @if($shop->is_selling === 0){ checked } @endif>停止中</div>
                            </div>
                          </div>


                        <div class="p-2 w-1/2 mx-auto flex">
                        <div class="p-2 w-full mt-2 flex justify-around">
                            <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新</button>
                        </div>
                        </div>
                    </div>
                    </form>


                    <form id="delete_{{$shop->id}}" method="POST" action="{{ route('admin.data.shop_destroy',['shop' => $shop->id]) }}">
                        @csrf
                        @method('delete')
                        <div class="md:px-4 py-3">
                            <div class="p-2 w-full flex justify-around mt-0">
                            <a href="#" data-id="{{ $shop->id }}" onclick="deletePost(this)" class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded ">削除</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

