
<x-app-layout>
    <x-slot name="header">

        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            店舗詳細
            {{-- <button type="button" onclick="location.href='{{ route('user.company.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <div class="ml-2 flex mb-4 md:ml-4">
        <div class="ml-0 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('shop_index') }}'" >shop一覧</button>
        </div>

        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('report_create',['shop'=>$shop->id]) }}'" >新規Report</button>
        </div>
        </div>




        <form method="get" action="" class="mt-2 mb-4">

            <div class="md:flex">

                <div class="flex">
                <div class="flex pl-0 mt-0">

                    <div class="pl-2 ml-0 md:ml-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="co_id"  value="">{{ $shop->co_name }}</div>
                </div>
                <div class="flex pl-2 mt-0">

                    <div class="pl-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="ar_id" value="">{{ $shop->area_name }}</div>
                </div>
                <div class="flex pl-2 mt-0 md:mt-0 ">

                    <div class="pl-2 w-32 h-6 text-sm items-center bg-gray-100 border rounded" name="sh_name" value="">{{ $shop->shop_name }}</div>
                </div>
                </div>
                <div class="flex">
                {{--  <div class="flex pl- mt-1 md:mt-0">

                    <div class="pl-2 w-32 h-6 items-center bg-gray-100 border rounded md:ml-2" name="sh_id" value="">{{ $shop->id }}</div>
                </div>  --}}

                </div>
                <div class="md:flex">
                <div class="flex pl-0 mt-1 md:mt-0">

                    <div class="pl-2 ml-0 text-sm md:ml-2 w-80 h-6 items-center bg-gray-100 border rounded" name="sh_info" value="">{{ $shop->shop_info }}</div>
                </div>

                </div>

            </div>
     </form>


    </x-slot>

    <div class="py-0 border">
        <h1>店舗Report</h1>
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-full bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-2/12 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Id</th>
                        <th class="w-2/12 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>
                        <th class="w-3/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Name</th>
                        <th class="w-2/12 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">report</th>
                        <th class="w-2/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">詳細</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $report)
                    <tr>
                        <td class="w-2/12 md:1/12 text-sm md:px-4 py-1 text-left"> {{ $report->id }} </td>
                        <td class="w-2/12 md:1/12 text-sm md:px-4 py-1 text-left"> {{\Carbon\Carbon::parse($report->updated_at)->format("y/m/d  H:i")}} </td>
                        <td class="w-3/12 md:2/12 text-sm md:px-4 py-1 text-left">{{ $report->name }}</td>
                        <td class="w-3/12 md:2/12 text-sm md:px-4 py-1 text-left">{{ Str::limit($report->report,20) }}</td>

                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-center"><a href="{{ route('report_detail',['report'=>$report->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  >詳細</a></td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{  $reports->appends([
                'co_id'=>\Request::get('co_id'),
                'area_id'=>\Request::get('area_id'),
                'sh_id'=>\Request::get('sh_id'),
                'info'=>\Request::get('info'),
            ])->links()}}
        </div>
    </div>


</x-app-layout>
