<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            Member情報編集
        </div>
        </h2>
        <div class="md:flex ml-8 ">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('memberlist') }}'" class="w-32 text-center text-sm text-white bg-indigo-400 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">Membert一覧</button>
        </div>



        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:w-2/3 px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-2 py-2 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('member_update1',['user'=>$user->id])}}" enctype="multipart/form-data">
                    @csrf

                    <div class="-m-2">
                        {{-- <div class="flex"> --}}
                        {{-- <div class="px-2 py-1 ">
                            <div class="relative flex"> --}}
                            {{--  <label for="" class="p-2 w-28 leading-7 text-sm text-gray-600"></label>  --}}
                            {{-- <div id="" name="" value="{{ $user->id }}" required class="w-20 ml-2 bg-gray-100 bg-opacity-50 rounded border text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $user->id }}</div>
                            </div>
                        </div> --}}
                        <div class="relative mt-4">
                                {{-- <label class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >エリア　</label> --}}
                                <select class="w-32 h-8 rounded text-sm pt-1" id="area_id" name="area_id"  class="border">
                                {{-- <option value="" @if(\Request::get('area_id') == '0') selected @endif >エリア</option> --}}
                                <option value="{{ $user->area_id }}" @if(\Request::get('area_id') == '0') selected @endif >{{ $user->area_name }}</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" @if(\Request::get('area_id') == $area->id) selected @endif >{{ $area->area_name }}</option>
                                @endforeach
                                </select>
                        </div>
                        {{-- </div> --}}
                        <div class="px-2 mx-auto">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 leading-tight">ニックネーム</label>
                                <input id="name" name="name" value=" {{ $user->name }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>
                            <div class="relative">
                                <label for="name"_kana class="leading-7 text-sm  text-gray-800 dark:text-gray-200 leading-tight">ニックネーム（カナ）</label>
                                <input id="name_kana" name="name_kana" value=" {{ $user->name_kana }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>

                            <div class="relative">
                                <label for="realname" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 leading-tight">本名</label>
                                <input id="realname" name="realname" value=" {{ $user->realname }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>
                            <div class="relative">
                                <label for="realname_kana" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 leading-tight">本名（カナ）</label>
                                <input id="realname_kana" name="realname_kana" value=" {{ $user->realname_kana }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>
                            <div class="relative">
                                <label for="email" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 leading-tight">Mail</label>
                                <input id="email" name="email" value=" {{ $user->email }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>
                            <div class="relative">
                                <label for="user_info" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 leading-tight">info</label>
                                <textarea id="user_info" name="user_info" rows="8" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $user->user_info }}</textarea>
                            </div>
                        </div>
                        <div class="px-2 md:w-2/1 mx-auto">
                            <div class="relative flex">
                            <div class="w-80 ml-2">
                                <x-user-thumbnail :filename="$user->photo1" />
                            </div>
                            <div class="w-80 ml-2">
                                <x-user-thumbnail :filename="$user->photo2" />
                            </div>
                            </div>
                        </div>
                        <div class="p-0 md:flex">
                        <div class="relative">
                            <label for="photo1" class="leading-7 text-sm text-gray-600">画像1</label>
                            <input type="file" id="photo1" name="photo1" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="photo2" class="leading-7 text-sm text-gray-600">画像2</label>
                            <input type="file" id="photo2" name="photo2" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        </div>


                        <div class="p-2 w-1/2 mx-auto flex">
                        <div class="p-2 w-full mt-2 flex justify-around">
                            <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
