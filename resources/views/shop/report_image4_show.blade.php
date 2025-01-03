<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        画像4<br>
        </h2>

        <div class="m-">
            <input type="hidden" class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="album_id2"  value="{{ $report->id }}"/>
            <div class="p-2 w-full ml-60 flex mt-2">
                <button type="button" onclick="location.href='{{ route('report_detail',['report'=>$report->id])}}'" class="w-32 h-8 text-white text-sm bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded ">戻る</button>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">
                    {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}
                        <img class="w-full mx-auto" src="{{ asset('storage/reports/'.$report->image4) }}">


                </div>
            </div>
        </div>
    </div>


</x-app-layout>

