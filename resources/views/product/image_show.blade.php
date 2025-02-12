<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品画像<br>
        </h2>

        <div class="flex">

            <div class="">
                <input type="hidden" class="pl-0 ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="hinban_id2"  value="{{ $image->hinban_id }}"/>
                <div class="p-0 w-full ml-6 flex mt-2 md:mt-0">
                    <button type="button" onclick="window.location.href='{{ url()->previous()}}'" class="w-32 h-8 text-white text-sm bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">戻る</button>
                    <button  onclick="location.href='{{ route('image_index')}}'" class="w-32 h-8 ml-6 text-white text-sm bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">画像リスト</button>
                </div>
            </div>
            </div>

            <div class="p-2 w-full ml-4 flex mt-0">
                @if($login_user->role_id <= 2 )
                <form id="delete_{{$image->hinban_id}}" method="POST" action="{{ route('admin.image_destroy',['hinban'=>$image->hinban_id]) }}">
                    @csrf
                    @method('delete')
                    <div class="ml-0 mt-2 md:ml-4 md:mt-0">
                        <div class="w-32 h-8 text-center text-sm text-white bg-red-500 border-0 pt-2 px-2 focus:outline-none hover:bg-red-700 rounded ">
                        <a href="#" data-id="{{ $image->hinban_id }}" onclick="deletePost(this)" >削除</a>
                        </div>
                    </div>
                </form>
                @endif
            </div>


    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">
                    {{$image->hinban_id}}　：　{{$image->hinban_name}}<br><br>
                    {{$image->hinban_info}}<br><br>
                    マスタ売価：{{$image->m_price}}円　：　現売価{{$image->m_price}}円<br><br>
                        <img class="w-full mx-auto" src="{{ asset('storage/images/'.$image->filename) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="flex flex-wrap">
        @foreach ($sku_images as $image )
        <div class="w-1/3 md:w-1/4 p-2 md:p-4">

            <div class="text-gray-700"> Col:　{{ $image->col_id  }}</div>
            <x-sku_image-thumbnail :filename="$image->filename"  />

        </div>
        @endforeach
    </div> --}}

    <div class="flex flex-wrap">
        @foreach ($sku_images as $image )
        <div class="w-1/3 md:w-1/4 p-2 md:p-2">
        {{-- <a href="{{ route('image.edit',['image'=>$image->id]) }}"> --}}

            <div class="text-gray-700"> Col:　{{ $image->col_id  }}</div>
            @if(($image->filename))
            {{-- <a href="{{ route('sku_image_show',['sku'=>$image->sku_id]) }}"> --}}
            <x-sku_image-thumbnail :filename="$image->filename"  />
            {{-- </a> --}}
            @endif
            @if(!($image->filename))
            <x-sku_image-thumbnail :filename="$image->filename"  />
            @endif
           {{--  <x-sku_image-thumbnail :filename="$image->filename"  />  --}}
        {{-- </a> --}}
        </div>
        @endforeach
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

