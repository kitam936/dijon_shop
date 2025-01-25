<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2">
            画像リスト 　<br>
        </h2>
        <div class="md:flex  md:ml-24 mb-2">
            <div class="text-indigo-700 ml-4 md:ml-4 md:mb-2 font-semibold text-xl ">品番： {{ $image->hinban_id  }}</div>
            <div>
            <input type="hidden" id="hinban_id" name="hinban_id" value="{{ $image->hinban_id }}"/>
            </div>
            <div class="ml-4 flex mt-2 md:mt-0">
                <div class="ml-0 md:ml-4">
                    <button type="button" class="w-32 h-8 text-sm bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
                </div>
            <div>
            <button onclick="location.href='{{ route('image_create',['hinban'=>$image->hinban_id]) }}'" class="w-32 ml-8 md:ml-4 text-white h-8 bg-green-500 border-0 px-8 focus:outline-none hover:bg-green-600 rounded ">新規登録</button>
            </div>
            </div>
        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')"/>

                    <div class="flex flex-wrap">
                    @foreach ($images as $image )
                    <div class="w-full md:w-1/4 p-2 md:p-4">
                    {{-- <a href="{{ route('album.edit',['album'=>$album->id]) }}"> --}}
                    <div class="border rounded-md p-0 md:p-0">
                        <div class="text-gray-700"> {{ $image->hinban_id  }}</div>
                        <a href="{{ route('image_show',['hinban'=>$image->hinban_id]) }}">
                        <x-album-thumbnail :filename="$image->filename"  />
                        </a>
                        <div class="flex">
                        <div class="text-gray-700"> {{ $image->hinban_id  }}</div>
                        <div class="text-gray-700 ml-4"> {{ $image->hinban_name  }}</div>

                        <a href="{{ route('image_edit',['hinban'=>$image->hinban_id]) }}" ><span class="rounded text-red-500 ml-4 bg-gray-300 border-gray-800">削除</span></a>

                        </div>

                    </div>
                    </a>
                    </div>
                    @endforeach
                    </div>
                    {{ $images->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
