<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
                データ削除
            <div class="ml-80 w-40 text-sm items-right mb-2">
                <button onclick="location.href='{{ route('admin.data.delete_index') }}'" class="text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-300 rounded text-ml">クリア</button>
            </div>
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">

            <x-flash-message status="session('status')" />
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}


                    <div class="-m-2">

                        <div class="p-2">

                            <form method="POST" action="{{ route('admin.data.sales_destroy') }}" class=" p-1" >
                                @csrf
                                @method('delete')
                                <select class="w-32 h-8 text-sm items-center pt-1" id="YW1" name="YW1" type="number" class="border">
                                    <option value="" @if(\Request::get('YW1') == '0') selected @endif >{{ $max_YW }}</option>
                                    @foreach ($YWs as $YW)
                                        <option value="{{ $YW->YW }}" @if(\Request::get('YW1') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                                    @endforeach
                                </select>
                                <span class="items-center text-sm mt-2" >　週　～</span>
                                <select class="w-32 h-8 text-sm items-center pt-1" id="YW2" name="YW2" type="number" class="border">
                                    <option value="" @if(\Request::get('YW2') == '0') selected @endif >{{ $max_YW }}</option>
                                    @foreach ($YWs as $YW)
                                        <option value="{{ $YW->YW }}" @if(\Request::get('YW2') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                                    @endforeach
                                </select>
                                <span class="items-center text-sm mt-2" >　週　　</span>
                                <button type="submit" class="ml-5 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">売上データ削除</button>
                            </form>


                            <form method="POST" action="{{ route('admin.data.stock_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >在庫データの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">在庫データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.sku_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >SKUデータの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">SKUデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.hinban_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >品番データの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">品番データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.shop_destroy_all') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >店舗データの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">店舗データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.company_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >会社データの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">会社データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.area_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >エリアデータの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">エリアデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.unit_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >Unitデータの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Unitデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.brand_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >Brandデータの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Brandデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.col_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >Colデータの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Colデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.size_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                               <span class="items-center mt-2 mr-20" >Sizeデータの全削除</span>
                                <button type="submit" class="ml-44 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Sizeデータ削除</button>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

