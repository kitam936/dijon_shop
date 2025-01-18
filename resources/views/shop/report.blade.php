
<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        店舗Report一覧

        </h2>
        <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            <div class="pl-2 mt-2 ml-4 md:ml-8 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('report_create2') }}'" >新規Report</button>
            </div>
        </div>
        <x-flash-message status="session('status')"/>

        <form method="get" action="{{ route('report_list')}}" class="mt-4">
            <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >※エリア・会社を選択してください　　　</span>
            <div class="md:flex">
                <div class="flex">
                    <div class="mb-2 ml-00 md:flex ">
                        {{--  <label class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >エリア　</label>  --}}
                        <select class="w-32 h-8 rounded text-sm pt-1" id="ar_id" name="ar_id"  class="border">
                        <option value="" @if(\Request::get('ar_id') == '0') selected @endif >全エリア</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @if(\Request::get('ar_id') == $area->id) selected @endif >{{ $area->area_name }}</option>
                        @endforeach
                            </select>
                    </div>
                    <div class="flex ml-2 mb-2 md:flex ">
                        {{--  <label class="pr-1 items-center text-sm mt-2 md:ml-4 text-gray-800 dark:text-gray-200 leading-tight" >会  社　</label>  --}}
                            <select class="w-32 h-8 ml-2 rounded text-sm pt-1 " id="co_id" name="co_id"  class="border">
                            <option value="" @if(\Request::get('co_id') == '0') selected @endif >全社</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @if(\Request::get('co_id') == $company->id) selected @endif >{{ $company->co_name }}</option>
                            @endforeach
                            </select><br>
                    </div>
                </div>
                <div class="flex">
                    <div class="ml-0 md:ml-2">
                        {{--  <span class="items-center text-sm mt-2  text-gray-800 dark:text-gray-200 leading-tight" >店名　:</span>  --}}
                        <input class="w-52 h-8 ml-0 rounded text-sm md:ml-4" id="sh_name" placeholder="店名検索入力(一部でも)" name="sh_name" >
                    </div>
                    <div class="ml-2 md:ml-4">
                        <button type="button" class="w-20 h-8 bg-blue-500 text-white ml-2 hover:bg-blue-600 rounded" onclick="location.href='{{ route('report_list') }}'" >全表示</button>
                    </div>

                </div>
            </div>
        </form>


    </x-slot>



    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">エリア</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">社名</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                        <th class="w-2/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ｺﾒﾝﾄ数</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $report)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center">  {{ $report->id }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center"> <a href="{{ route('report_detail',['report'=>$report->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  > {{\Carbon\Carbon::parse($report->created_at)->format("y/m/d H:i")}}</a> </td>
                        <td class="w-2/14 pr-2 text-sm md:px-4 py-1 text-center">  {{ Str::limit($report->area_name,8) }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">  {{ Str::limit($report->co_name,10) }} </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ Str::limit($report->shop_name,12) }}</td>
                        <td class="w-2/14 text-sm md:px-4 py-1 text-center"> {{ $report->comment_count }}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{ $reports->links() }}
        </div>
    </div>

    <script>
        const company = document.getElementById('co_id')
        company.addEventListener('change', function(){
        this.form.submit()
        })

        const area = document.getElementById('ar_id')
        area.addEventListener('change', function(){
        this.form.submit()
        })

        const shop = document.getElementById('sh_name')
        shop.addEventListener('change', function(){
        this.form.submit()
        })

    </script>


</x-app-layout>
