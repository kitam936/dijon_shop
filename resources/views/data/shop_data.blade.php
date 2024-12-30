
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <div class="flex">
            <div>
            店舗データ
            </div>
            <div class="pl-10 ml-30 w-30 ml-60 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('admin.data.data_index') }}'" class="text-white bg-indigo-400 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-ml">戻る</button>
            </div>
            </div>
        </h2>

        <form method="get" action="{{ route('admin.data.shop_index')}}" class="mt-4">
            <div class="flex">
            <select class="w-40 h-8 text-sm" id="co_id" name="co_id" type="text" class="border">
            <option value="" @if(\Request::get('co_id') == '0') selected @endif >全社</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" @if(\Request::get('co_id') == $company->id) selected @endif >{{ $company->co_name }}</option>
            @endforeach
             </select>
             <span class="items-center text-sm mt-2" >　※会社を選択してください　　　</span>
             <div>
                <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded " onclick="location.href='{{ route('admin.data.shop_index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
            </div>
            </div>

        </form>


    </x-slot>

    <div class="py-0 border">
        <x-flash-message status="session('status')" />
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-full bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/12 md:px-2 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">社コード</th>
                        <th class="w-1/12 md:px-2 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">社名</th>
                        <th class="w-1/12 md:px-2 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店コード</th>
                        <th class="w-2/12 md:px-2 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-2/12 md:px-2 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">info</th>
                        <th class="w-3/12 md:px-2 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">編集</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($shops as $shop)
                    <tr>
                        <td class="w-1/12 md:pl-6 py-1 text-left"> {{ $shop->company_id }} </td>
                        <td class="w-2/12 md:pl-6 py-1 text-left">{{ $shop->company->co_name }}</td>
                        <td class="w-1/12 md:pl-6 py-1 text-left">{{ $shop->id }}</td>
                        <td class="w-3/12 md:pl-6 py-1 text-left">{{ $shop->shop_name }}</td>
                        <td class="w-3/12 md:pl-6 py-1 text-left">{{ $shop->shop_info }}</td>
                        <td class="w-2/12 md:pl-6 py-1 text-center"><a href="{{ route('admin.data.shop_edit',['shop'=>$shop->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  >編集</a></td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{  $shops->links()}}
        </div>
    </div>

    <script>
        const company = document.getElementById('co_id')
        company.addEventListener('change', function(){
        this.form.submit()
        })



    </script>




</x-app-layout>
