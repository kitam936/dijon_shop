<x-app-layout>
    <x-slot name="header">
        <div >
            <h2 class="mb-4 font-semibold text-xl  text-gray-800 dark:text-gray-200 leading-tight">
            <div>
                店舗Report登録
            </div>
            </h2>
            <div class="flex">
            <div class="ml-4 mb-2">
                <button type="button" onclick="location.href='{{ route('report_list') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">店舗Report一覧</button>
            </div>
            <div class="ml-4 mb-2">
                <button type="button" onclick="location.href='{{ route('shop_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">店舗一覧</button>
            </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('report_store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="-m-2">
                        <div class="mb-0">
                            <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >店舗： 　</span>
                                @foreach ($shops as $shop)
                                <div class="flex pl-0 mt-0">
                                    <div class="pl-0  ml-0 md:ml-2 w-80 h-6 items-center bg-gray-100 border rounded text-gray-800 dark:text-gray-200 leading-tight" name="sh_id"  value="{{ $shop->id }}">{{ $shop->id }}--{{ $shop->shop_name }}</div>
                                    <input type="hidden" class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="sh_id2"  value="{{ $shop->id }}"/>
                                </div>
                                @endforeach

                            </select>

                        </div>

                        <div class="p-2 mx-auto">
                            <div class="relative">
                              <label for="report" class="leading-7  text-sm mt-2 text-gray-800 dark:text-gray-200 ">Report※必須</label>
                              <textarea id="report" name="report" rows="8" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ old('report') }}</textarea>
                            </div>
                        </div>
                        <div class="p-0 md:flex">
                        <div class="relative">
                            <label for="image1" class="leading-7  text-sm mt-2 text-gray-800 dark:text-gray-200 ">画像1</label>
                            <input type="file" id="image1" name="image1" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="image2" class="leading-7  text-sm mt-2 text-gray-800 dark:text-gray-200 ">画像2</label>
                            <input type="file" id="image2" name="image2" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="image3" class="leading-7  text-sm mt-2 text-gray-800 dark:text-gray-200 ">画像3</label>
                            <input type="file" id="image3" name="image3" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="image4" class="leading-7  text-sm mt-2 text-gray-800 dark:text-gray-200 ">画像4</label>
                            <input type="file" id="image4" name="image4" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        </div>


                        <div class="p-2 w-1/2 mx-auto">

                          <div class="p-2 w-full mt-4 flex justify-around">

                            <button type="submit" class="w-32 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">登録</button>
                          </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

