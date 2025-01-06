
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            売上推移<br>
        </h2>
        <div class="pl-2 mt-0 md:mt-0 md:ml-60 ml-40 ">
            <button type="button" class="w-32 h-8 bg-indigo-500 text-white hover:bg-indigo-600 rounded lg:ml-2 " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
        </div>

        {{-- <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・店舗を選択してください　　　</span> --}}

        <form method="get" action="{{ route('sales_transition')}}" class="mt-4">

            <div class="flex mb-2">
                <label for="YM1" class="mr-3 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">期間(年月)</label>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                    <option value="" @if(\Request::get('YM1') == '0') selected @endif >年月選択(from)</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　～　</span>
                <select class="w-32 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                    <option value="" @if(\Request::get('YM2') == '0') selected @endif >年月選択(to)</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                </select>
                <span class="items-center text-sm mt-2" >　</span>
            </div>
            <div class="flex">
                <label for="type2" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">期間種別</label>
                <select id="type2" name="type2" class="w-28 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                    <option value="d" @if(\Request::get('type2') == '0') selected @endif >日別</option>
                    {{-- <option value="dry">dry</option> --}}
                    <option value="d" @if(\Request::get('type2') == "d") selected @endif>日別</option>
                    <option value="w" @if(\Request::get('type2') == "w") selected @endif>週別</option>
                    <option value="m" @if(\Request::get('type2') == "m") selected @endif>月別</option>
                    {{-- <option value="y" @if(\Request::get('type2') == "y") selected @endif>年別</option> --}}
                    {{-- <option value="wet">wet</option> --}}
                </select>

                <label for="type1" class="mr-4 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">社店種別</label>
                <select id="type1" name="type1" class="w-28 h-8 rounded text-sm pt-1 border mr-2 mb-2" type="text">
                    <option value="" @if(\Request::get('type1') == '0') selected @endif >全社店</option>
                    {{-- <option value="dry">dry</option> --}}
                    <option value="co" @if(\Request::get('type1') == "co") selected @endif>社別</option>
                    <option value="sh" @if(\Request::get('type1') == "sh") selected @endif>店別</option>
                    {{-- <option value="wet">wet</option> --}}
                </select>
            </div>


                <div class="flex">
                @if(\Request::get('type1') == 'co' || \Request::get('type1') == 'sh')
                <label for="co_id" class="mr-6 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">社を指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="co_id" name="co_id" >
                <option value="" @if(\Request::get('co_id') == '0') selected @endif >選択なし</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @if(\Request::get('co_id') == $company->id ) selected @endif >{{ $company->co_name  }}</option>
                @endforeach
                </select>
                @endif
                @if(\Request::get('type1') == 'sh')
                <label for="sh_id" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">店を指定</label>
                <select class="w-32 h-8 rounded border text-sm items-center pt-1" id="sh_id" name="sh_id" >
                    <option value="" @if(\Request::get('sh_id') == '0') selected @endif >選択なし</option>
                    @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}" @if(\Request::get('sh_id') == $shop->id ) selected @endif >{{ $shop->shop_name  }}</option>
                    @endforeach
                </select>
                @endif
                </div>
            <div class="flex">
                <label for="brand_code" class="mr-3 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Brand指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-5 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >選択なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->brand_name  }}</option>
                    @endforeach
                </select>
                <label for="season_code" class="mr-5 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">季節指定</label>
                <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-2 " id="season_code" name="season_code" type="number" >
                    <option value="" @if(\Request::get('season_code') == '0') selected @endif >選択なし</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->season_id }}" @if(\Request::get('season_code') == $season->season_id ) selected @endif >{{ $season->season_name  }}</option>
                    @endforeach
                </select>

            </div>


        {{-- <div class="ml-2 md:ml-4">
            <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('sales_transition') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
        </div> --}}
        </form>



        <div class="ml-0 py-0 md:w-1/2 border">
            <div class=" w-full  sm:px-0 lg:px-0 border mt-0 ml-0">
                <div class='border bg-gray-100 h-6'>

                    　期間累計　：　　{{ number_format(round($total->total)/1000) }}千円　

                </div>
            </div>
        </div>


    </x-slot>

        <div class="py-6 border">
        <div class="md:w-1/2 sm:px-4 lg:px-4 border">
            <table class="mx-auto table-auto bg-white w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">月・週・日</th>

                    <th class="w-1/4 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">月売上(千円)</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($datas as $data)
                <tr>
                    <td class="w-1/4 md:px-4 py-1">{{ $data->date }}</td>

                    <td class="w-1/4 pr-24 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->total)/1000)}}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>


<script>

const type2 = document.getElementById('type2')
type2.addEventListener('change', function(){
this.form.submit()
    })

const type1 = document.getElementById('type1')
type1.addEventListener('change', function(){
this.form.submit()
    })

const YM1 = document.getElementById('YM1')
YM1.addEventListener('change', function(){
this.form.submit()
    })

const YM2 = document.getElementById('YM2')
YM2.addEventListener('change', function(){
this.form.submit()
})

const brand = document.getElementById('brand_code')
brand.addEventListener('change', function(){
this.form.submit()
})

const season = document.getElementById('season_code')
season.addEventListener('change', function(){
this.form.submit()
})

const company = document.getElementById('co_id')
company.addEventListener('change', function(){
this.form.submit()
})

const shop = document.getElementById('sh_id')
shop.addEventListener('change', function(){
this.form.submit()
})






</script>

</x-app-layout>



