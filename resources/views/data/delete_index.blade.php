<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
                データ削除
            <div class="ml-24 w-40 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('admin.data.delete_index') }}'" class="text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-300 rounded text-ml">クリア</button>
            </div>
            </h2>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">

            <x-flash-message status="session('status')" />
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}


                    <div class="-m-2">

                        <div class="p-2">
                            <span class="items-center mt-2 mr-20" >データ選択削除</span>
                            <div calss="flex">
                            <form method="POST" action="{{ route('admin.data.sales_destroy') }}" class=" p-1 mb-2" >
                                @csrf
                                @method('delete')
                                <div class="flex">
                                    <div class="flex">
                                        <div class="flex">
                                        {{-- <label for="YM1" class="mr-3 leading-7 text-sm  text-gray-800 ">期間(年月)</label> --}}
                                        <select class="w-32 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                                            <option value="{{ $max_YM }}" @if(\Request::get('YM1') == '0') selected @endif >年月選択(from)</option>
                                            @foreach ($YMs as $YM)
                                                <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div>
                                        <span class="items-center text-sm mt-2 text-gray-800 leading-tight" >　～　</span>
                                        <select class="w-32 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                                            <option value="{{ $max_YM }}" @if(\Request::get('YM2') == '0') selected @endif >年月選択(to)</option>
                                            @foreach ($YMs as $YM)
                                                <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    <span class="items-center text-sm mt-2" >　　　</span><br>
                                    </div>
                                <div>
                                <button type="submit" class="text-sm w-32 ml-0 mt-0 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">売上データ削除</button>
                                </div>
                                </div>
                            </form>
                            </div>

                            <div calss="flex">
                            <form method="POST" action="{{ route('admin.data.yosan_destroy') }}" class=" p-1 mb-2" >
                                @csrf
                                @method('delete')
                                <div class="flex">
                                    <div class="flex">
                                        <div class="flex">
                                        {{-- <label for="YM1" class="mr-3 leading-7 text-sm  text-gray-800 ">期間(年月)</label> --}}
                                        <select class="w-32 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                                            <option value="{{ $bg_max_YM }}" @if(\Request::get('YM1') == '0') selected @endif >年月選択(from)</option>
                                            @foreach ($bg_YMs as $YM)
                                                <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div>
                                        <span class="items-center text-sm mt-2 text-gray-800 leading-tight" >　～　</span>
                                        <select class="w-32 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                                            <option value="{{ $bg_max_YM }}" @if(\Request::get('YM2') == '0') selected @endif >年月選択(to)</option>
                                            @foreach ($bg_YMs as $YM)
                                                <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    <span class="items-center text-sm mt-2" >　　　</span><br>
                                    </div>
                                <div>
                                <button type="submit" class="text-sm w-32 ml-0 mt-0 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">予算データ削除</button>
                                </div>
                                </div>
                            </form>
                            </div>

                            <form method="POST" action="{{ route('admin.data.hinban_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')
                                <div class="flex">
                                <div>
                                <select class="w-32 h-8 text-sm rounded items-center pt-1" id="year1" name="year1" type="number" class="border">
                                    <option value="" @if(\Request::get('year1') == '0') selected @endif >年度選択(from)</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->year_code }}" @if(\Request::get('year1') == $year->year_code) selected @endif >{{ $year->year_code }}年度</option>
                                    @endforeach
                                </select>
                                <span class="items-center text-sm mt-2" >　～　</span>
                                <select class="w-32 h-8 text-sm rounded items-center pt-1" id="year2" name="year2" type="number" class="border">
                                    <option value="" @if(\Request::get('year2') == '0') selected @endif >年度選択(to)</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->year_code }}" @if(\Request::get('year2') == $year->year_code) selected @endif >{{ $year->year_code }}年度</option>
                                    @endforeach
                                </select>
                                <span class="items-center text-sm mt-2" >　　</span><br>
                                </div>
                                <div>
                                <button type="submit" class="text-sm w-32 ml-1 mt-0 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">品番削除</button>
                                </div>
                                </div>
                            </form>
                            <div class="mt-8">
                            <span class="items-center mt-12 mr-20" >データ全削除</span>
                            </div>

                            <form method="POST" action="{{ route('admin.data.stock_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm ml-0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">在庫データ削除</button>
                            </form>

                        <div class="flex mt-2">
                            <form method="POST" action="{{ route('admin.data.sku_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">SKUデータ削除</button>
                            </form>



                            <form method="POST" action="{{ route('admin.data.col_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Colデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.size_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Sizeデータ削除</button>
                            </form>
                        </div>

                        <div class="flex mt-2">
                            <form method="POST" action="{{ route('admin.data.unit_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Unitデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.brand_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Brandデータ削除</button>
                            </form>
                        </div>

                        <div class="flex mt-2">
                            <form method="POST" action="{{ route('admin.data.shop_destroy_all') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">店舗データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.company_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">会社データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('admin.data.area_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">エリアデータ削除</button>
                            </form>
                        </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

