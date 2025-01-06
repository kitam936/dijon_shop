<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            売上Data Menu
        </h2>
        <div class="md:flex-auto p-1 text-gray-900 dark:text-gray-100  ">
            <div class="flex px-4 py-4 md:w-2/3">
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 flex-auto p-0 text-sm text-white dark:text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('sales_transition') }}'" >売上推移</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 flex-auto p-0 text-sm text-white dark:text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('sales_total') }}'" >累計売上順位</button>

            </div>


        </div>

    </x-slot>

</x-app-layout>
