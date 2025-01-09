<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           ユーザー登録
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section class="text-gray-600 body-font relative">
                        <div class="container px-5 py-0 mx-auto">
                          <div class="flex flex-col text-center w-full mb-0">
                            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-0 text-gray-900">ユーザー登録</h1>
                          </div>
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            <form method="post" action="{{ route('admin.user_store') }}">
                              @csrf
                            <div class="-m-2">
                              <div class="p-0 mx-auto">
                                <div class="relative">
                                  <label for="name" class="leading-7 text-sm text-gray-600">名前</label>
                                  <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="flex ml-0 mb-2  md:mb-4">
                                    <div>
                                    <label for="name" class="leading-7 text-sm text-gray-600">所属</label>
                                    <div>
                                        <select class="w-80 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" id="sh_id" name="sh_id"  class="border">
                                            <option value="" @if(\Request::get('sh_id') == '0') selected @endif >所属選択</option>
                                            @foreach ($shops as $shop)
                                                <option value="{{ $shop->id }}" @if(\Request::get('sh_id') == $shop->id) selected @endif >{{ $shop->shop_name }}</option>
                                                {{-- <input class="w-44 h-8 ml-0 md:ml-4 rounded text-sm pt-1"  name="info"  class="border">{{ $shop->id }} --}}
                                            @endforeach
                                        </select><br>
                                    </div>
                                    </div>
                                </div>
                                <div class="relative">
                                  <label for="email" class="leading-7 text-sm text-gray-600">メールアドレス</label>
                                  <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative">
                                    <label for="user_info" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">info</label>
                                    <textarea id="user_info" name="user_info" rows="8"  class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ old('user_info') }}</textarea>
                                </div>
                                <div class="relative">
                                    <label for="password" class="leading-7 text-sm text-gray-600">パスワード</label>
                                    <input type="password" id="password" name="password" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative">
                                    <label for="password_confirmation" class="leading-7 text-sm text-gray-600">パスワード確認</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                              </div>

                              <div class="p-2 w-full mt-4 flex justify-around">
                                <button type="button" onclick="location.href='{{ route('memberlist') }}'" class="h-10 text-black bg-gray-300 border-0 py-2 px-8 focus:outline-none hover:bg-gray-300 rounded ">戻る</button>
                                <button type="submit" class="h-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded ">登録</button>
                                </div>

                            </div>
                            </form>
                          </div>
                        </div>
                      </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
