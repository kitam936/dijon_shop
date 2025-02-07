<x-app-layout>
    <x-slot name="header">
    <div class="flex">
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            画像アップロード
        </h2>
        </div>

        <div class="p-2 ml-60 mt-4">
            <button type="button" onclick="location.href='{{ route('admin.data.data_menu')}}'" class="h-10 bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>

        </div>
        </div>
        <x-flash-message status="session('status')"/>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    品番画像
                </h2>
                    <form method="post" action="{{ route('admin.image_store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="-m-2">
                            <div class="p-2 flex ">
                                <div class="p-2 w-1/2 ">
                                    <div class="relative">
                                        <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                                        <input type="file" id="image" name="files[][image]" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    </div>
                                </div>
                                <div class="p-2 w-full flex mt-8">
                                    <label for="image" class="leading-7 text-sm text-gray-600">　</label>
                                    <button type="submit" class="h-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    SKU画像
                </h2>
                    <form method="post" action="{{ route('admin.sku_image_store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="-m-2">
                            <div class="p-2 flex ">
                                <div class="p-2 w-1/2 ">
                                    <div class="relative">
                                        <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                                        <input type="file" id="image" name="files[][image]" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    </div>
                                </div>
                                <div class="p-2 w-full flex mt-8">
                                    <label for="image" class="leading-7 text-sm text-gray-600">　</label>
                                    <button type="submit" class="h-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

