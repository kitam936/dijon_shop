<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
            <div>
                各種データ登録
            </div>

            <div class="w-40 ml-60 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('admin.data.create') }}'" class="text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-300 rounded text-ml">クリア</button>
            </div>
            </h2>
    </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">

            <x-flash-message status="session('status')" />
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}


                    <div class="-m-2">

                        <div class="p-2">

                        <form method="POST" action="{{ route('admin.data.sales_upload') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="sales_data" class="leading-7 text-sm text-gray-600">売上データ</label>
                            <input type="file" id="sales_data" name="sales_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">売上データ追加</button>
                        </form>

                        <form method="POST" action="{{ route('admin.data.stock_upload') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="stock_data" class="leading-7 text-sm text-gray-600">在庫データ</label>
                            <input type="file" id="stock_data" name="stock_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">在庫データ更新</button>
                        </form>

                        <form method="POST" action="{{ route('admin.data.sku_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="sku_data" class="leading-7 text-sm text-gray-600">SKUデータ</label>
                            <input type="file" id="sku_data" name="sku_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">SKUデータ更新</button>
                        </form>

                        <form method="POST" action="{{ route('admin.data.hinban_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="hinban_data" class="leading-7 text-sm text-gray-600">品番データ</label>
                            <input type="file" id="hinban_data" name="hinban_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">品番データ更新</button>
                        </form>

                        <form method="POST" action="{{ route('admin.data.shop_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="shop_data" class="leading-7 text-sm text-gray-600">店舗データ</label>
                            <input type="file" id="shop_data" name="shop_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">店舗データ更新</button>
                        </form>
                        <form method="POST" action="{{ route('admin.data.company_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="co_data" class="leading-7 text-sm text-gray-600">会社データ</label>
                            <input type="file" id="co_data" name="co_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">会社データ更新</button>
                        </form>
                        <form method="POST" action="{{ route('admin.data.area_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="ar_data" class="leading-7 text-sm text-gray-600">エリアデータ</label>
                            <input type="file" id="ar_data" name="ar_data" accept=“.csv” class="w-1/3 ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">エリアデータ更新</button>
                        </form>
                        <form method="POST" action="{{ route('admin.data.unit_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="unit_data" class="leading-7 text-sm text-gray-600">UNITデータ</label>
                            <input type="file" id="unit_data" name="unit_data" accept=“.csv” class="w-1/3 ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">UNITデータ更新</button>
                        </form>
                        <form method="POST" action="{{ route('admin.data.brand_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="brand_data" class="leading-7 text-sm text-gray-600">Brandデータ</label>
                            <input type="file" id="brand_data" name="brand_data" accept=“.csv” class="w-1/3 ml-1 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">Brandデータ更新</button>
                        </form>

                        <form method="POST" action="{{ route('admin.data.col_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="col_data" class="leading-7 text-sm text-gray-600 mr-2">Colデータ</label>
                            <input type="file" id="col_data" name="col_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">Colデータ更新</button>
                        </form>

                        <form method="POST" action="{{ route('admin.data.size_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="size_data" class="leading-7 text-sm text-gray-600">Sizeデータ</label>
                            <input type="file" id="size_data" name="size_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-white bg-indigo-500 border-0 py-1 px-4 focus:outline-none hover:bg-indigo-600 rounded">Sizeデータ更新</button>
                        </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

