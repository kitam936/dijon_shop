
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <div class="flex">
            <div>
            納品データ
            </div>
            <div class="w-40 ml-60 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('admin.data.data_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">戻る</button>
            </div>
            </div>
        </h2>
        <x-flash-message status="session('status')"/>

    </x-slot>

    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-3/4  bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">shop_id</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">pcs</th>
                        <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">tanka</th>
                        <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">kingaku</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($delivs as $deliv)
                    <tr>
                        <td class="w-1/15 md:px-4 py-1"> {{ $deliv->deliv_date }} </td>
                        <td class="w-1/15 md:px-4 py-1">{{ $deliv->shop_id }}</td>
                        <td class="w-1/15 md:px-4 py-1"> {{ $deliv->hinban_id }}</td>
                        <td class="w-2/15 md:px-4 py-1"> {{ $deliv->pcs }}</td>
                        <td class="w-1/15 md:px-4 py-1 text-right">{{ number_format($deliv->tanka )}}</td>
                        <td class="w-1/15 md:px-4 py-1 text-right">{{ number_format($deliv->kingaku )}}</td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{  $delivs->links()}}
        </div>
    </div>

</x-app-layout>
