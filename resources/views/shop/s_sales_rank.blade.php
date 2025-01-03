
<x-app-layout>
    <x-slot name="header">
        <div class="">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            店別期間売上
        </h2>

        <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・エリア・会社・期間を選択してください</span>

        </div>
        <form method="get" action="{{ route('user.shop.s_sales_rank')}}" class="mt-4">
            <div class="md:flex">

                <div class="md:flex">
                <div class="flex ml-0">
                <div class="flex ml-0 md:ml-2 mb-2">
                    {{-- <label for="co_id" class="items-center text-sm mt-2" >会社： 　</label> --}}
                    {{-- <label for="brand_code" class="items-center text-sm mt-2  text-gray-800 dark:text-gray-200 leading-tight" >Brand：</label> --}}
                    <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-2 md:mr-0" id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >全ブランド</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->br_name  }}</option>
                    @endforeach
                    </select>

                </div>
                <div class="mb-2 ml-0 md:flex md:ml-2 mb-4">
                    <label class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" ></label>
                    <select class="w-26 h-8 rounded text-sm pt-1" id="ar_id" name="ar_id"  class="border">
                    <option value="" @if(\Request::get('ar_id') == '0') selected @endif >全エリア</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @if(\Request::get('ar_id') == $area->id) selected @endif >{{ $area->ar_name }}</option>
                    @endforeach
                         </select>
                </div>
                <div class="flex ml-0 mb-2 md:flex md:ml-2 mb-4">
                    <label class="pr-1 items-center text-sm mt-2 md:ml-0 text-gray-800 dark:text-gray-200 leading-tight" ></label>
                        <select class="w-28 h-8 ml-1 rounded text-sm pt-1 " id="co_id" name="co_id"  class="border">
                        <option value="" @if(\Request::get('co_id') == '0') selected @endif >全社</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @if(\Request::get('co_id') == $company->id) selected @endif >{{ $company->co_name }}</option>
                        @endforeach
                        </select><br>
                </div>
                </div>
                </div>

            <div class="flex ml-0 md:ml-6">
                    {{-- <label for="YW1" class="items-center text-sm mt-2 " >期間： 　</label> --}}
                    <select class="w-32 h-8 md:ml-4 rounded text-sm items-center pt-1" id="YW1" name="YW1" type="number" >
                    <option value="" @if(\Request::get('YW1') == '0') selected @endif >{{ $max_YW }}直近週</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW1') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                <label for="YW2" class="items-center text-sm mt-2 ml-2 text-gray-800 dark:text-gray-200 leading-tight" >～</label>
                <select class="w-32 h-8 ml-2 rounded text-sm items-center pt-1" id="YW2" name="YW2" type="number" class="border">
                    <option value="" @if(\Request::get('YW2') == '0') selected @endif >{{ $max_YW }}直近週</option>
                    @foreach ($YWs as $YW)
                        <option value="{{ $YW->YW }}" @if(\Request::get('YW2') == $YW->YW) selected @endif >{{ floor(($YW->YM)/100)%100 }}年{{ ($YW->YM)%100 }}月{{ ($YW->YW)%100 }}週</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2" ></span>
            <div class="ml-2 md:ml-4" >
                <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-0 hover:bg-indigo-600 rounded " onclick="location.href='{{ route('user.shop.index') }}'" >SHOP一覧</button>
            </div>
            </div>
            </div>
        </form>

    </x-slot>



    <div class="py-4 border">
        <div class=" mx-auto px-4 sm:px-4 lg:px-4 border ">
            <table class="w-full bg-white table-auto md:w-1/2 text-center whitespace-no-wrap">
                <thead>
                <tr>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">社名</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">店名</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売上数</th>
                    <th class="w-2/8 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売上額(千円)</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($s_ranks as $s_rank)
                <tr>
                    <td class="w-2/8 text-sm text-left md:px-4 py-1">{{ Str::limit($s_rank->co_name,12) }}</td>
                    <td class="w-2/8 text-sm text-left md:px-4 py-1">{{ Str::limit($s_rank->shop_name,12) }}</td>
                    <td class="w-2/8 text-sm pr-0 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format($s_rank->pcs)}}</span></td>
                    <td class="w-2/8 text-sm pr-4 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($s_rank->kingaku)/1000)}}</span></td>
                </tr>
                @endforeach

                </tbody>
            </table>

            {{  $s_ranks->appends([
                'brand_code'=>\Request::get('brand_code'),
                'co_id'=>\Request::get('co_id'),
                'ar_id'=>\Request::get('ar_id'),
                'YW1'=>\Request::get('YW1'),
                'YW2'=>\Request::get('YW2'),
            ])->links()}}
        </div>



        <script>

            const brand = document.getElementById('brand_code')
            brand.addEventListener('change', function(){
            this.form.submit()
            })

            const company = document.getElementById('co_id')
            company.addEventListener('change', function(){
            this.form.submit()
            })

            const area = document.getElementById('ar_id')
            area.addEventListener('change', function(){
            this.form.submit()
            })

            const YW1 = document.getElementById('YW1')
            YW1.addEventListener('change', function(){
            this.form.submit()
            })

            const YW2 = document.getElementById('YW2')
            YW2.addEventListener('change', function(){
            this.form.submit()
            })


        </script>

</x-app-layout>
