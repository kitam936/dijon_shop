<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            データ管理Menu
        </h2>
        <div class="md:flex-auto p-1 text-gray-900 dark:text-gray-100  ">
            <div class="flex px-4 py-4 md:w-2/3">
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 flex-auto p-0 text-sm text-white dark:text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('admin.data.data_index') }}'" >Index</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 flex-auto p-0 text-sm text-white dark:text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('admin.data.create') }}'" >反映・更新</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 flex-auto p-0 text-sm text-white dark:text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('admin.image_create') }}'" >画像UL</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 flex-auto p-0 text-sm text-white dark:text-white bg-pink-400  hover:bg-pink-600 rounded" onclick="location.href='{{ route('admin.data.delete_index') }}'" >削除</button>
            </div>


        </div>

    </x-slot>

</x-app-layout>
